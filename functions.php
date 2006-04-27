<?

/**
 * assigns some standard variables to smarty templates
 */
function smarty_std(){
  global $smarty;
  $smarty->assign('USER',$_SESSION['ldapab']['username']);
}

/**
 * Uses Username and Password from Session to initialize the LDAP handle
 * If it fails it redirects to login.php
 */
function ldap_login(){
  global $conf;
  if(!empty($_SESSION['ldapab']['username'])){
    // existing session! Check if valid
    if($_SESSION['ldapab']['browserid'] != auth_browseruid()){
      //session hijacking detected
      header('Location: login.php?username=');
      exit;
    }
  } elseif ($conf['httpd_auth'] && !empty($_SERVER['PHP_AUTH_USER'])) {
    // use HTTP auth if wanted and possible
  	$_SESSION['ldapab']['username'] = $_SERVER['PHP_AUTH_USER'];
  	$_SESSION['ldapab']['password'] = $_SERVER['PHP_AUTH_PW'];
  } elseif ($_COOKIE['ldapabauth']) {
    // check persistent cookie
    $cookie = base64_decode($_COOKIE['ldapabauth']);
    $cookie = x_Decrypt($cookie,get_cookie_secret());
    list($u,$p) = unserialize($cookie);
    $_SESSION['ldapab']['username'] = $u;
    $_SESSION['ldapab']['password'] = $p;
  }

  if(!do_ldap_bind($_SESSION['ldapab']['username'],
                   $_SESSION['ldapab']['password'],
                   $_SESSION['ldapab']['binddn'])){
    header('Location: login.php?username=');
    exit;
  }
}

/**
 * Creates a global LDAP connection handle called $LDAP_CON
 */
function do_ldap_bind($user,$pass,$dn=""){
  global $conf;
  global $LDAP_CON;
  
  //create global connection to LDAP if necessary
  if(!$LDAP_CON){
    if (!empty($conf['ldapurl'])){
      $LDAP_CON = ldap_connect($conf['ldapurl']);
    }else{
      $LDAP_CON = ldap_connect($conf['ldapserver'],$conf['ldapport']);
    }
    if(!$LDAP_CON){
      die("couldn't connect to LDAP server");
    }
  }

  if(empty($dn)){
    //anonymous bind to lookup users
    //blank binddn or blank bindpw will result in anonymous bind
    if(!ldap_bind($LDAP_CON,$conf['anonbinddn'],$conf['anonbindpw'])){
      die("can not bind for user lookup");
    }
  
    //when no user was given stay connected anonymous
    if(empty($user)){
      set_session('','','');
      return true;
    }

    //get dn for given user
    $filter = str_replace('%u',$user,$conf['userfilter']);
    $sr = ldap_search($LDAP_CON, $conf['usertree'], $filter);;
    $result = ldap_get_entries($LDAP_CON, $sr);
    if($result['count'] != 1){
      set_session('','','');
      return false;
    }
    $dn = $result[0]['dn'];
  }

  //bind with dn
  if(ldap_bind($LDAP_CON,$dn,$pass)){
    //bind successful -> set up session
    set_session($user,$pass,$dn);
    return true;
  }
  //bind failed -> remove session
  set_session('','','');
  return false;
}

/**
 * Builds a pseudo UID from browser and IP data
 *
 * This is neither unique nor unfakable - still it adds some
 * security. Using the first part of the IP makes sure
 * proxy farms like AOLs are stil okay.
 *
 * @author  Andreas Gohr <andi@splitbrain.org>
 *
 * @return  string  a MD5 sum of various browser headers
 */
function auth_browseruid(){
  $uid  = '';
  $uid .= $_SERVER['HTTP_USER_AGENT'];
  $uid .= $_SERVER['HTTP_ACCEPT_ENCODING'];
  $uid .= $_SERVER['HTTP_ACCEPT_LANGUAGE'];
  $uid .= $_SERVER['HTTP_ACCEPT_CHARSET'];
  $uid .= substr($_SERVER['REMOTE_ADDR'],0,strpos($_SERVER['REMOTE_ADDR'],'.'));
  return md5($uid);
}


/**
 * saves user data to Session and cookies
 */
function set_session($user,$pass,$dn){
  global $conf;

  $rand = rand();
  $_SESSION['ldapab']['username']  = $user;
  $_SESSION['ldapab']['binddn']    = $dn;
  $_SESSION['ldapab']['password']  = $pass;
  $_SESSION['ldapab']['browserid'] = auth_browseruid();

  // (re)set the persistant auth cookie
  if($user == ''){
    setcookie('ldapabauth','',time()+60*60*24*365);
  }elseif($_REQUEST['remember']){
    $cookie = serialize(array($user,$pass));
    $cookie = x_Encrypt($cookie,get_cookie_secret());
    $cookie = base64_encode($cookie);
    setcookie('ldapabauth',$cookie,time()+60*60*24*365);
  }
}

/**
 * Creates a random string to encrypt persistant auth
 * cookies the string is stored inside the cache dir
 */
function get_cookie_secret(){
  $file = dirname(__FILE__).'/cache/.htcookiesecret.php';
  if(@file_exists($file)){
    return md5(trim(file($file)));
  }

  $secret = '<?php #'.(rand()*time()).'?>';
  if(!$fh = fopen($file,'w')) die("Couldn't write to $file");
  if(fwrite($fh, $secret) === FALSE) die("Couldn't write to $file");
  fclose($fh);

  return md5($secret);
}

/**
 * binary safe function to get all search result data.
 * It will use ldap_get_values_len() instead and build the array
 * note: it's similar with the array returned by ldap_get_entries()
 * except it has no "count" elements
 *
 * @author: Original code by Ovidiu Geaboc <ogeaboc@rdanet.com>
 */
function ldap_get_binentries($conn,$srchRslt){
  if(!@ldap_count_entries($conn,$srchRslt)){
    return null;
  }
  $entry = ldap_first_entry($conn, $srchRslt);
  $i=0;
  do {
    $dn = ldap_get_dn($conn,$entry);
    $attrs = ldap_get_attributes($conn, $entry);
    for($j=0; $j<$attrs['count']; $j++) {
      $vals = ldap_get_values_len($conn, $entry,$attrs[$j]);
      for($k=0; $k<$vals['count']; $k++){
        $data[$i][$attrs[$j]][$k]=$vals[$k];
      }
    }
    $data[$i]['dn']=$dn;
    $i++;
  }while ($entry = ldap_next_entry($conn, $entry));

  return $data;
}

/**
 * loads ldap names and their cleartext meanings from
 * entries.conf file and returns it as hash
 */
function namedentries($flip=false){
  global $conf;

  $entries['dn']                         = 'dn';
  $entries['sn']                         = 'name';
  $entries['givenName']                  = 'givenname';
  $entries['title']                      = 'title';
  $entries['o']                          = 'organization';
  $entries['physicalDeliveryOfficeName'] = 'office';
  $entries['postalAddress']              = 'street';
  $entries['postalCode']                 = 'zip';
  $entries['l']                          = 'location';
  $entries['telephoneNumber']            = 'phone';
  $entries['facsimileTelephoneNumber']   = 'fax';
  $entries['mobile']                     = 'mobile';
  $entries['pager']                      = 'pager';
  $entries['homePhone']                  = 'homephone';
  $entries['homePostalAddress']          = 'homestreet';
  $entries['jpegPhoto']                  = 'photo';
  $entries['labeledURI']                 = 'url';
  $entries['description']                = 'note';
  $entries['manager']                    = 'manager';
  $entries['cn']                         = 'displayname';

  if($conf['extended']){
    $entries['anniversary']              = 'anniversary';
  }
  if($conf['openxchange']){
    $entries['mailDomain']               = 'domain';
    $entries['userCountry']              = 'country';
    $entries['birthDay']                 = 'birthday';
    $entries['IPPhone']                  = 'ipphone';
    $entries['OXUserCategories']         = 'categories';
    $entries['OXUserInstantMessenger']   = 'instantmessenger';
    $entries['OXTimeZone']               = 'timezone';
    $entries['OXUserPosition']           = 'position';
    $entries['relClientCert']            = 'certificate';
  }

  if($flip){
    $entries = array_reverse($entries);
    $entries = array_flip($entries);
  }
  return $entries;
}

/**
 * Creates an array for submission to ldap from websitedata
 */
function prepare_ldap_entry($in){
  global $conf;

  //check dateformat
  if(!preg_match('/\d\d\d\d-\d\d-\d\d/',$in['anniversary'])){
    $in['anniversary']='';
  }

  $entries = namedentries(true);
  foreach(array_keys($in) as $key){
    if(empty($entries[$key])){
      $keyname=$key;
    }else{
      $keyname=$entries[$key];
    }
    if(is_array($in[$key])){
      $out[$keyname] = $in[$key];
    }else{
      $out[$keyname][] = $in[$key];
    }
  }

  //standard Objectclass
  $out['objectclass'][] = 'inetOrgPerson';
  if($conf['extended']){
    $out['objectclass'][] = 'contactPerson';
  }
  if($conf['openxchange']){
    $out['objectclass'][] = 'OXUserObject';
  }

  return clear_array($out);
}

/**
 * remove empty element from arrays recursively
 *
 * @author Original by <xntx@msn.com>
 */
function clear_array ( $a ) {
  if ($a !== array()) {
    $b = array();
    foreach ( $a as $key => $value ) {
        if (is_array($value)) {
          if (clear_array($value) !== false) {
            $b[$key] = clear_array ( $value );
          }
        } elseif ($value !== '') {
          $b[$key] = $value;
        }
    }
    if ($b !== array()) {
        return $b;
    } else {
        return false;
    }
  } else {
    return false;
  }
}

/**
 * deletes an entryfrom ldap - optional with recursion
 *
 * @author Original by <gabriel@hrz.uni-marburg.de>
 */
function ldap_full_delete($ds,$dn,$recursive=false){
  if($recursive == false){
    return(ldap_delete($ds,$dn));
  }else{
    //searching for sub entries
    $sr=ldap_list($ds,$dn,"ObjectClass=*",array(""));
    $info = ldap_get_entries($ds, $sr);
    for($i=0;$i<$info['count'];$i++){
      //deleting recursively sub entries
      $result=myldap_delete($ds,$info[$i]['dn'],$recursive);
      if(!$result){
        //return result code, if delete fails
        return($result);
      }
    }
    return(ldap_delete($ds,$dn));
  }
}

/**
 * Returns all User Accounts as assoziative array
 */
function get_users(){
  global $conf;
  global $LDAP_CON;

  $sr = ldap_list($LDAP_CON,$conf['usertree'],"ObjectClass=inetOrgPerson");
  $result = ldap_get_binentries($LDAP_CON, $sr);
  if(count($result)){
    foreach ($result as $entry){
      if(!empty($entry['sn'][0])){
        $users[$entry['dn']] = $entry['givenName'][0]." ".$entry['sn'][0];
      }
    }
  }
  return $users; 
}

/**
 * makes sure the given DN contains exactly one space
 * after each ,
 */
function normalize_dn($dn){
  $dn = preg_replace('/,/',', ',$dn);
  $dn = preg_replace('/,\s+/',', ',$dn);
  return $dn;
}

/**
 * Merges the given classes with the existing ones
 */
function ldap_store_objectclasses($dn,$classes){
  global $conf;
  global $LDAP_CON;

  $sr     = ldap_search($LDAP_CON,$dn,"objectClass=*",array('objectClass'));
  $result = ldap_get_binentries($LDAP_CON, $sr);
  $set    = $result[0]['objectClass'];
  $set    = array_unique_renumber(array_merge((array)$set,(array)$classes));
  $add['objectClass'] = $set;

  $r = @ldap_mod_replace($LDAP_CON,$dn,$add);
  tpl_ldaperror();

/*  print '<pre>';
  print_r($set);
  print '</pre>';*/
}

/**
 * escape parenthesises in given string
 */
function ldap_filterescape($string){
  return strtr($string,array('('=>'\(', ')'=>'\)'));
}

/**
 * Queries public and private addressbooks, combining the
 * results
 *
 * @todo This function should be used where ever possible, replacing
 *       lots of duplicate code
 */
function ldap_queryabooks($filter,$types){
  global $conf;
  global $LDAP_CON;

  // make sure $types is an array
  if(!is_array($types)){
    $types = explode(',',$types);
    $types = array_map('trim',$types);
  }

  $results = array();
  $result1 = array();
  $result2 = array();

  // public addressbook
  $sr      = ldap_list($LDAP_CON,$conf['publicbook'],
                       $filter,$types);
  $result1 = ldap_get_binentries($LDAP_CON, $sr);
  ldap_free_result($sr);

  // private addressbook
  if(!empty($_SESSION['ldapab']['binddn'])){
    $sr      = @ldap_list($LDAP_CON,$conf['privatebook'].
                          ','.$_SESSION['ldapab']['binddn'],
                          $filter,$types);
    $result2 = ldap_get_binentries($LDAP_CON, $sr);
  }

  // return merged results
  return array_merge((array)$result1,(array)$result2);
}

/**
 * Makes array unique and renumbers the entries
 *
 * @author <kay_rules@yahoo.com>
 */
function array_unique_renumber($somearray){
   $tmparr = array_unique($somearray);
   $i=0;
   foreach ($tmparr as $v) {
       $newarr[$i] = $v;
       $i++;
   }
   return $newarr;
}

/**
 * Simple XOR encryption
 *
 * @author Dustin Schneider
 * @link http://www.phpbuilder.com/tips/item.php?id=68
 */
function x_Encrypt($string, $key){
    for($i=0; $i<strlen($string); $i++){
        for($j=0; $j<strlen($key); $j++){
            $string[$i] = $string[$i]^$key[$j];
        }
    }
    return $string;
}

/**
 * Simple XOR decryption
 *
 * @author Dustin Schneider
 * @link http://www.phpbuilder.com/tips/item.php?id=68
 */
function x_Decrypt($string, $key){
    for($i=0; $i<strlen($string); $i++){
        for($j=0; $j<strlen($key); $j++){
            $string[$i] = $key[$j]^$string[$i];
        }
    }
    return $string;
}

/**
 * Decodes UTF8 recursivly for the given array
 *
 * @deprecated
 */
function utf8_decode_array(&$array) {
  trigger_error('deprecated utf8_decode_array called',E_USER_WARNING);

  foreach (array_keys($array) as $key) {
    if($key === 'dn') continue;
    if($key === 'jpegPhoto') continue;
    if (is_array($array[$key])) {
      utf8_decode_array($array[$key]);
    }else {
      $array[$key] = utf8_decode($array[$key]);
    }
  }
}

/**
 * Encodes the given array to UTF8 recursively
 *
 * @deprecated
 */
function utf8_encode_array(&$array) {
  trigger_error('deprecated utf8_encode_array called',E_USER_WARNING);

  foreach (array_keys($array) as $key) {
    if($key === 'dn') continue;
    if($key === 'jpegPhoto') continue;
    if (is_array($array[$key])) {
      utf8_encode_array($array[$key]);
    }else {
      $array[$key] = utf8_encode($array[$key]);
    }
  }
}


?>

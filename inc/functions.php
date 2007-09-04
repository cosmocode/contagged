<?php

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
  } elseif (!empty($_COOKIE['ldapabauth'])) {
    // check persistent cookie
    $cookie = base64_decode($_COOKIE['ldapabauth']);
    $cookie = x_Decrypt($cookie,get_cookie_secret());
    list($u,$p) = unserialize($cookie);
    $_SESSION['ldapab']['username'] = $u;
    $_SESSION['ldapab']['password'] = $p;
  }

  if(empty($_SESSION['ldapab']) ||
     !do_ldap_bind($_SESSION['ldapab']['username'],
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

  if($conf['ldapv3']) ldap_set_option($LDAP_CON, LDAP_OPT_PROTOCOL_VERSION, 3);

  if(empty($dn)){
    //anonymous bind to lookup users
    //blank binddn or blank bindpw will result in anonymous bind
    if(!@ldap_bind($LDAP_CON,$conf['anonbinddn'],$conf['anonbindpw'])){
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
  if(@ldap_bind($LDAP_CON,$dn,$pass)){
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
  if (empty($_SERVER['HTTP_USER_AGENT']))      { $_SERVER['HTTP_USER_AGENT']='USER_AGENT'; }
  if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) { $_SERVER['HTTP_ACCEPT_ENCODING']='ACCEPT_ENCODING'; }
  if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { $_SERVER['HTTP_ACCEPT_LANGUAGE']='ACCEPT_LANGUAGE'; }
  if (empty($_SERVER['HTTP_ACCEPT_CHARSET']))  { $_SERVER['HTTP_ACCEPT_CHARSET']='ACCEPT_CHARSET'; }
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

  // (re)set the persistent auth cookie
  if($user == ''){
    setcookie('ldapabauth','',time()+60*60*24*365);
  }elseif(!empty($_REQUEST['remember'])){
    $cookie = serialize(array($user,$pass));
    $cookie = x_Encrypt($cookie,get_cookie_secret());
    $cookie = base64_encode($cookie);
    setcookie('ldapabauth',$cookie,time()+60*60*24*365);
  }
}

/**
 * Creates a random string to encrypt persistent auth
 * cookies; the string is stored inside the cache dir
 */
function get_cookie_secret(){
  $file = dirname(__FILE__).'/../cache/.htcookiesecret.php';
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
    trigger_error('deprecated namedentries called',E_USER_WARNING);
}

/**
 * Creates an array for submission to ldap from websitedata
 */
function prepare_ldap_entry($in){
  global $conf;
  global $FIELDS;
  global $OCLASSES;

  //check dateformats
  if(!preg_match('/\d\d\d\d-\d\d-\d\d/',$in['anniversary'])) $in['anniversary']='';
  if(!preg_match('/\d\d\d\d-\d\d-\d\d/',$in['birthday'])) $in['birthday']='';

  // we map all internal names to the configured LDAP attributes here
  foreach($in as $key => $value){
    if($FIELDS[$key]){
        // normal mapped field
        $out[$FIELDS[$key]][] = $value;
    }elseif($FIELDS["_$key"]){
        // mapped multi field
        if(is_array($value)){
            $out[$FIELDS["_$key"]] = $value;
        }else{
            $out[$FIELDS["_$key"]][] = $value; //shouldn't happen, but to be sure
        }
    }else{
        // no mapping found we ignore it
    }
  }

  // add the Objectclasses
  $out['objectclass'] = $OCLASSES;

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
  $users = array();
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
 * Escape a string to be used in a LDAP filter
 *
 * Ported from Perl's Net::LDAP::Util escape_filter_value
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function ldap_filterescape($string){
  return preg_replace('/([\x00-\x1F\*\(\)\\\\])/e',
                            '"\\\\\".join("",unpack("H2","$1"))',
                            $string);
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

/**
 * Returns all the fields used in the template
 *
 * Returned fields are already decoded to LDAP internals
 */
function get_fields_from_template($tpl){
    global $smarty;
    global $FIELDS;
    $tpl  = $smarty->template_dir.'/'.$tpl;
    $data = @file_get_contents($tpl);
    $matches = array();
    preg_match_all('/\$entry\.(\w+)/',$data,$matches);
    $matches = array_unique((array) $matches[1]);
    $return  = array();
    foreach($matches as $f){
        if($FIELDS[$f]){
            $return[] = $FIELDS[$f];
        }elseif($FIELDS["_$f"]){
            $return[] = $FIELDS["_$f"];
        }
    }
    return $return;
}

?>

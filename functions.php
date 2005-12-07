<?

/**
 * assigns some standard variables to smarty templates
 */
function smarty_std(){
  global $smarty;
  $smarty->assign('USER',$_SESSION[ldapab][username]);
}

/**
 * Uses Username and Password from Session to initialize the LDAP handle
 * If it fails it redirects to login.php
 */
function ldap_login(){
  if(!empty($_SESSION[ldapab][username])){
    //existing session! Check if valid
    if($_COOKIE[ldapabconid] != $_SESSION[ldapab][conid]){
      //session hijacking detected
       header('Location: login.php?username=');
       exit;
    }
  }

  if(!do_ldap_bind($_SESSION[ldapab][username],
                   $_SESSION[ldapab][password],
                   $_SESSION[ldapab][binddn])){
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
  
  //create global connection to LDAP if nessessary
  if(!$LDAP_CON){
    $LDAP_CON = ldap_connect($conf[ldapserver]);
    if(!$LDAP_CON){
      die("couldn't connect to LDAP server");
    }
  }

  if(empty($dn)){
    //anonymous bind to lookup users
    if(!ldap_bind($LDAP_CON)){
      die("can not bind anonymously");
    }
  
    //when no user was given stay connected anonymous
    if(empty($user)){
      set_session('','','');
      return true;
    }

    //get dn for given user
    $filter = str_replace('%u',$user,$conf[userfilter]);
    $sr = ldap_search($LDAP_CON, $conf[usertree], $filter);;
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
 * saves user data to Session
 */
function set_session($user,$pass,$dn){
  $rand = rand();
  $_SESSION[ldapab][username]=$user;
  $_SESSION[ldapab][binddn]  =$dn;
  $_SESSION[ldapab][password]=$pass;
  $_SESSION[ldapab][conid]   =$rand;
  setcookie('ldapabconid',$rand,time()+60*60*24);
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

  $entries[dn]                         = 'dn';
  $entries[sn]                         = 'name';
  $entries[givenName]                  = 'givenname';
  $entries[title]                      = 'title';
  $entries[o]                          = 'organization';
  $entries[physicalDeliveryOfficeName] = 'office';
  $entries[postalAddress]              = 'street';
  $entries[postalCode]                 = 'zip';
  $entries[l]                          = 'location';
  $entries[telephoneNumber]            = 'phone';
  $entries[facsimileTelephoneNumber]   = 'fax';
  $entries[mobile]                     = 'mobile';
  $entries[pager]                      = 'pager';
  $entries[homePhone]                  = 'homephone';
  $entries[homePostalAddress]          = 'homestreet';
  $entries[jpegPhoto]                  = 'photo';
  $entries[labeledURI]                 = 'url';
  $entries[description]                = 'note';
  $entries[manager]                    = 'manager';
  $entries[cn]                         = 'displayname';

  if($conf[extended]){
    $entries[anniversary]              = 'anniversary';
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
  if(!preg_match('/\d\d\d\d-\d\d-\d\d/',$in[anniversary])){
    $in[anniversary]='';
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
  $out[objectclass][] = 'inetOrgPerson';
  if($conf[extended]){
    $out[objectclass][] = 'contactPerson';
  }

  utf8_encode_array($out);

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

  $sr = ldap_list($LDAP_CON,$conf[usertree],"ObjectClass=inetOrgPerson");
  $result = ldap_get_binentries($LDAP_CON, $sr);
  if(count($result)){
    foreach ($result as $entry){
      if(!empty($entry[sn][0])){
        $users[$entry[dn]] = $entry[givenName][0]." ".$entry[sn][0];
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
  $set    = $result[0][objectClass];
  $set    = array_unique_renumber(array_merge($set,$classes));
  $add[objectClass] = $set;

  $r = @ldap_mod_replace($LDAP_CON,$dn,$add);
  tpl_ldaperror();

/*  print '<pre>';
  print_r($set);
  print '</pre>';*/
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
 * Decodes UTF8 recursivly for the given array
 */
function utf8_decode_array(&$array) {
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
 */
function utf8_encode_array(&$array) {
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

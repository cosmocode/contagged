<?php

/**
 * assigns some standard variables to smarty templates
 */
function tpl_std(){
  global $smarty;
  global $lang;
  global $conf;
  global $FIELDS;

  if(empty($_SESSION['ldapab']['username'])){
    $_SESSION['ldapab']['username'] = '';
  }
  if(empty($_SESSION['ldapab']['binddn'])){
    $_SESSION['ldapab']['binddn'] = '';
  }

  $smarty->assign('user',$_SESSION['ldapab']['username']);
  $smarty->assign('binddn',$_SESSION['ldapab']['binddn']);
  if(!empty($_SESSION['ldapab']['lastlocation'])){
    $smarty->assign('home',$_SESSION['ldapab']['lastlocation']);
  }else{
     $smarty->assign('home','index.php');
  }
  $smarty->assign('conf',$conf);
  $smarty->assign('lang',$lang);
  $smarty->assign('fields',$FIELDS);
}

/**
 * assigns all the interesting data from an ldap result to
 * the smarty template
 */
function tpl_entry($in){
  global $smarty;
  global $conf;
  global $RFIELDS;

  $out=array();

  // handle named entries
  foreach($RFIELDS as $key => $name){
    if(empty($in[$key])) continue;

    // keep arrays for multi fields
    if($name{0} == '_'){
        $name  = substr($name,1);
        if(is_array($in[$key])){
            $out[$name] = $in[$key];
        }else{
            $out[$name] = array($in[$key]);
        }
    }else{
        if(is_array($in[$key])){
            $out[$name] = $in[$key][0];
        }else{
            $out[$name] = $in[$key];
        }
    }
  }

  // set the type
  if (empty($out['dn'])) { $out['dn']=''; }
  $out['dn']          = normalize_dn($out['dn']);
  $conf['publicbook'] = normalize_dn($conf['publicbook']);
  if($out['dn']){
      if(strstr($out['dn'],$conf['publicbook'])){
          $out['type'] = 'public';
      }else{
          $out['type'] = 'private';
      }
  }

  // join marker field to markers
  if(is_array($out['marker'])) $out['markers'] = join(', ',$out['marker']);

/*
print '<pre>';
print_r($out);
print '</pre>';
*/

  $smarty->assign('entry',$out);
}

/**
 * assigns the last LDAP error to the template
 */
function tpl_ldaperror($message=""){
  global $LDAP_CON;
  global $__LDAPERROR__;
  global $smarty;
  $errno = ldap_errno($LDAP_CON);
  if($errno){
    $__LDAPERROR__ .= ldap_err2str($errno);
    if(!empty($message)){
      $__LDAPERROR__ .= "($message)";
    }elseif($errno == 4){
      $__LDAPERROR__ .= "(You need to increase this limit in your server config)";
    }
    $__LDAPERROR__ .= '<br />';
  }
  $smarty->assign("LDAPERRORS",$__LDAPERROR__);
}

/**
 * assigns all markers to the template
 */
function tpl_markers(){
  global $conf;
  global $LDAP_CON;
  global $smarty;

  if(!$conf['extended']) return;

  $markers = array();

  $sr = ldap_list($LDAP_CON,$conf['publicbook'],"ObjectClass=inetOrgPerson",array("marker"));
  $result1 = ldap_get_binentries($LDAP_CON, $sr);
  //check users private addressbook
  if(!empty($_SESSION['ldapab']['binddn']) && $conf['privatebook']){
    $sr = @ldap_list($LDAP_CON,
                    $conf['privatebook'].','.$_SESSION['ldapab']['binddn'],
                    "ObjectClass=inetOrgPerson",array("marker"));
    $result2 = ldap_get_binentries($LDAP_CON, $sr);
  }else{
    $result2 = '';
  }
  $result = array_merge((array)$result1,(array)$result2);

  if(count($result)){
    foreach ($result as $entry){
      if(!empty($entry['marker']) && count($entry['marker'])){
        foreach($entry['marker'] as $marker){
          array_push($markers, $marker);
        }
      }
    }
  }
  $markers = array_unique($markers);
  sort($markers,SORT_STRING);

  $smarty->assign('markers',$markers);
}

/**
 * Assigns all distinct organization names to the template
 */
function tpl_orgs(){
  global $conf;
  global $LDAP_CON;
  global $smarty;
  global $FIELDS;

  $orgs = array();
  $result = ldap_queryabooks("ObjectClass=inetOrgPerson",array($FIELDS['organization']));

  if(count($result)){
    foreach ($result as $entry){
      if(!empty($entry[$FIELDS['organization']][0])){
        array_push($orgs, $entry[$FIELDS['organization']][0]);
      }
    }
  }
  $orgs = array_unique($orgs);
  natcasesort($orgs);
  $smarty->assign('orgs',$orgs);
}

/**
 * assigns all categories to the template
 */
function tpl_categories(){
  global $conf;
  global $LDAP_CON;
  global $smarty;

  if(!$conf['openxchange']) return;

  $categories = array();

  $sr = ldap_list($LDAP_CON,$conf['publicbook'],"ObjectClass=OXUserObject",array("OXUserCategories"));
  $result1 = ldap_get_binentries($LDAP_CON, $sr);
  //check users private addressbook
  if(!empty($_SESSION['ldapab']['binddn']) && $conf['privatebook']){
    $sr = @ldap_list($LDAP_CON,
                    $conf['privatebook'].','.$_SESSION['ldapab']['binddn'],
                    "ObjectClass=OXUserObject",array("OXUserCategories"));
    $result2 = ldap_get_binentries($LDAP_CON, $sr);
  }
  $result = array_merge((array)$result1,(array)$result2);

  if(count($result)){
    foreach ($result as $entry){
      if(count($entry['OXUserCategories'])){
        foreach($entry['OXUserCategories'] as $category){
          array_push($categories, $category);
        }
      }
    }
  }
  $categories = array_unique($categories);
  sort($categories,SORT_STRING);

  $smarty->assign('categories',$categories);
}

/**
 * assigns all timezones to the template
 */
function tpl_timezone(){
  global $conf;
  global $LDAP_CON;
  global $smarty;

  if(!$conf['openxchange']) return;

  $timezone = array();

  $sr = ldap_list($LDAP_CON,$conf['publicbook'],"ObjectClass=OXUserObject",array("OXTimeZone"));
  $result1 = ldap_get_binentries($LDAP_CON, $sr);
  //check users private addressbook
  if(!empty($_SESSION['ldapab']['binddn']) && $conf['privatebook']){
    $sr = @ldap_list($LDAP_CON,
                    $conf['privatebook'].','.$_SESSION['ldapab']['binddn'],
                    "ObjectClass=OXUserObject",array("OXTimeZone"));
    $result2 = ldap_get_binentries($LDAP_CON, $sr);
  }
  $result = array_merge((array)$result1,(array)$result2);

  if(count($result)){
    foreach ($result as $entry){
      if(count($entry['OXTimeZone'])){
        foreach($entry['OXTimeZone'] as $tz){
          array_push($timezone, $tz);
        }
      }
    }
  }
  $timezone = array_unique($timezone);
  sort($timezone,SORT_STRING);

  $smarty->assign('timezone',$timezone);
}

/**
 * assigns all countries to the template
 */
function tpl_country(){
  global $conf;
  global $LDAP_CON;
  global $smarty;

  if(!$conf['openxchange']) return;

  $country = array();

  $sr = ldap_list($LDAP_CON,$conf['publicbook'],"ObjectClass=OXUserObject",array("userCountry"));
  $result1 = ldap_get_binentries($LDAP_CON, $sr);
  //check users private addressbook
  if(!empty($_SESSION['ldapab']['binddn']) && $conf['privatebook']){
    $sr = @ldap_list($LDAP_CON,
                    $conf['privatebook'].','.$_SESSION['ldapab']['binddn'],
                    "ObjectClass=OXUserObject",array("userCountry"));
    $result2 = ldap_get_binentries($LDAP_CON, $sr);
  }
  $result = array_merge((array)$result1,(array)$result2);

  if(count($result)){
    foreach ($result as $entry){
      if(count($entry['userCountry'])){
        foreach($entry['userCountry'] as $c){
          array_push($country, $c);
        }
      }
    }
  }
  $country = array_unique($country);
  sort($country,SORT_STRING);

  $smarty->assign('country',$country);
}

?>

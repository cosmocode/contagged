<?

/**
 * assigns some standard variables to smarty templates
 */
function tpl_std(){
  global $smarty;
  global $lang;
  global $conf;

  $smarty->assign('user',$_SESSION[ldapab][username]);
  $smarty->assign('binddn',$_SESSION[ldapab][binddn]);
  if(!empty($_SESSION[ldapab][lastlocation])){
    $smarty->assign('home',$_SESSION[ldapab][lastlocation]);
  }else{
     $smarty->assign('home','index.php');
  }
  $smarty->assign('conf',$conf);
  $smarty->assign('lang',$lang);
  $smarty->assign('dfexample',$dfexample);
}

/**
 * assigns all the interesting data from an ldap result to
 * the smarty template
 */
function tpl_entry($in){
  global $smarty;
  global $conf;
  $entries = namedentries();


  //handle named entries
  foreach(array_keys($entries) as $key){
    if($in[$key]){
      if(is_array($in[$key])){
        $out[$entries[$key]] = $in[$key][0];
      }else{
        $out[$entries[$key]] = $in[$key];
      }
    }
  }

  //set the type
  $out['dn']        = normalize_dn($out['dn']);
  $conf[publicbook] = normalize_dn($conf[publicbook]);
  if($out['dn']){
    if(strstr($out['dn'],$conf[publicbook])){
      $out[type] = 'public';
    }else{
      $out[type] = 'private';
    }
  }

  //mail entries are handled special
  $out['mail'] = $in['mail'];
  if ($conf[extended]){
    //handle marker special in extended mode
    $out['marker'] = $in['marker'];
  }

  //decode array
  utf8_decode_array($out);

/*print '<pre>';
print_r($out);
print '</pre>';*/

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
    }
    $__LDAPERROR__ .= '\n';
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

  if(!$conf[extended]) return;

  $markers = array();

  $sr = ldap_list($LDAP_CON,$conf[publicbook],"ObjectClass=inetOrgPerson",array("marker"));
  $result1 = ldap_get_binentries($LDAP_CON, $sr);
  //check users private addressbook
  if(!empty($_SESSION[ldapab][binddn])){
    $sr = @ldap_list($LDAP_CON,
                    $conf[privatebook].','.$_SESSION[ldapab][binddn],
                    "ObjectClass=inetOrgPerson",array("marker"));
    $result2 = ldap_get_binentries($LDAP_CON, $sr);
  }
  $result = array_merge($result1,$result2);

  if(count($result)){
    foreach ($result as $entry){
      if(count($entry['marker'])){
        foreach($entry['marker'] as $marker){
          array_push($markers, $marker);
        }
      }
    }
  }
  $markers = array_unique($markers);
  sort($markers,SORT_STRING);
 
  utf8_decode_array($markers);
  $smarty->assign('markers',$markers);
}

/**
 * Assigns all distinct organization names to the template
 */
function tpl_orgs(){
  global $conf;
  global $LDAP_CON;
  global $smarty;

  $orgs = array();

  $sr = ldap_list($LDAP_CON,$conf[publicbook],"ObjectClass=inetOrgPerson",array("o"));
  $result1 = ldap_get_binentries($LDAP_CON, $sr);
  //check users private addressbook
  if(!empty($_SESSION[ldapab][binddn])){
    $sr = @ldap_list($LDAP_CON,
                    $conf[privatebook].','.$_SESSION[ldapab][binddn],
                    "ObjectClass=inetOrgPerson",array("o"));
    $result2 = ldap_get_binentries($LDAP_CON, $sr);
  }
  $result = array_merge($result1,$result2);

  if(count($result)){
    foreach ($result as $entry){
      if(!empty($entry[o][0])){
        array_push($orgs, $entry[o][0]);
      }
    }
  }
  $orgs = array_unique($orgs);
  sort($orgs,SORT_STRING);
  utf8_decode_array($orgs);
  $smarty->assign('orgs',$orgs);
}

?>

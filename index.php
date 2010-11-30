<?php

  require_once('inc/init.php');
  ldap_login();

  // select entry template
  if(!empty($_REQUEST['export']) && $_REQUEST['export'] == 'csv'){
    $entrytpl = 'list_csv_entry.tpl';
  }elseif(!empty($_REQUEST['export']) && $_REQUEST['export'] == 'map'){
    $entrytpl = 'list_map_entry.tpl';
  }elseif(!empty($_REQUEST['export']) && $_REQUEST['export'] == 'print'){
    $entrytpl = 'list_print_entry.tpl';
  }else{
    $entrytpl = 'list_entry.tpl';
  }

  // check which fields are needed
  $fields = get_fields_from_template($entrytpl);


  //prepare filter
  $ldapfilter = _makeldapfilter();

  // fetch results
  $result = ldap_queryabooks($ldapfilter,$fields);

  //prepare templates
  tpl_std();
  if (empty($_REQUEST['filter'])) $_REQUEST['filter']='';
  if (empty($_REQUEST['marker'])) $_REQUEST['marker']='';
  if (empty($_REQUEST['search'])) $_REQUEST['search']='';
  $smarty->assign('filter',$_REQUEST['filter']);
  $smarty->assign('marker',$_REQUEST['marker']);
  $smarty->assign('search',$_REQUEST['search']);
  $smarty->assign('org',$_REQUEST['org']);

  $list = '';
  if(count($result)==1 && $_REQUEST['search']){
    //only one result on a search -> display page
    header("Location: entry.php?dn=".rawurlencode($result[0]['dn']));
    exit;
  }elseif(count($result)){
    $keys = array_keys($result);
    uksort($keys,"_namesort");
    foreach($keys as $key){
      tpl_entry($result[$key]);
      $list .= $smarty->fetch($entrytpl);
    }
  }
  $smarty->assign('list',$list);

  //display templates
  if(!empty($_REQUEST['export'])){
    if ($conf['userlogreq'] && $user == ''){
      header("HTTP/1.1 401 Access Denied");
      echo '<h1>Access Denied</h1>';
      exit();
    }

    if($_REQUEST['export'] == 'csv'){
      header("Content-Type: text/csv");
      header('Content-Disposition: Attachement; filename="contagged_export.csv"');
      $smarty->display('list_csv.tpl');
      exit;
    }elseif($_REQUEST['export'] == 'map'){
      header('Content-Type: text/html; charset=utf-8');
      $smarty->display('list_map.tpl');
      exit;
    }elseif($_REQUEST['export'] == 'print'){
      header('Content-Type: text/html; charset=utf-8');
      $smarty->display('list_print.tpl');
      exit;
    }
  }else{
    //save location in session
    $_SESSION['ldapab']['lastlocation']=$_SERVER["REQUEST_URI"];

    header('Content-Type: text/html; charset=utf-8');
    $smarty->display('list.tpl');
  }

  //------- functions -----------//

  /**
   * callback function to sort entries by name
   * uses global $result
   */
  function _namesort($a,$b){
    global $result;
    global $FIELDS;
    if (empty($result[$a][$FIELDS['givenname']])) { $result[$a][$FIELDS['givenname']]=''; }
    if (empty($result[$b][$FIELDS['givenname']])) { $result[$b][$FIELDS['givenname']]=''; }
    $x = $result[$a][$FIELDS['name']][0].$result[$a][$FIELDS['givenname']][0];
    $y = $result[$b][$FIELDS['name']][0].$result[$b][$FIELDS['givenname']][0];
    return(strcasecmp($x,$y));
  }


  /**
   * Creates an LDAP filter from given request variables search or filter
   */
  function _makeldapfilter(){
    global $FIELDS;
    global $conf;

    //handle given filter

    if (empty($_REQUEST['filter'])) { $_REQUEST['filter']=''; }
    if (empty($_REQUEST['search'])) { $_REQUEST['search']=''; }
    if (empty($_REQUEST['org'])) { $_REQUEST['org']=''; }
    if (empty($_REQUEST['marker'])) { $_REQUEST['marker']=''; }
    if(is_numeric($_REQUEST['search'])) $number = $_REQUEST['search'];
    $filter = ldap_filterescape($_REQUEST['filter']);
    $search = ldap_filterescape($_REQUEST['search']);
    $org    = ldap_filterescape($_REQUEST['org']);
    $marker = ldap_filterescape($_REQUEST['marker']);
    $_SESSION['ldapab']['filter'] = $_REQUEST['filter'];
    if(empty($filter)) $filter='a';

    if(!empty($marker)){
      // Search by tag
      $ldapfilter = '(&(objectClass=contactPerson)';
      $marker = explode(',',$marker);
      foreach($marker as $m){
        $m = trim($m);
        $ldapfilter .= '('.$FIELDS['_marker'].'='.$m.')';
      }
      $ldapfilter .= ')';
    }elseif($number){
      // Search by telephone number
      $filter = '';
      // add wildcards between digits to compensate for any formatting
      $length = strlen($number);
      for($i=0; $i <$length; $i++){
        $filter .= '*'.$number{$i};
      }
      $filter .= '*';
      $ldapfilter = '(&'.
                        '(objectClass=inetOrgPerson)'.
                        '(|'.
                            '(|'.
                                '('.$FIELDS['phone'].'='.$filter.')'.
                                '('.$FIELDS['homephone'].'='.$filter.')'.
                            ')'.
                            '('.$FIELDS['mobile'].'='.$filter.')'.
                        ')'.
                    ')';
    }elseif(!empty($search)){
      // Search name and organization
      $search = trim($search);
      $words=preg_split('/\s+/',$search);
      $filter='';
      foreach($words as $word){
        $wordfilter='';
        foreach($conf['searchfields'] as $field) {
          $wordfilter .= '('.$field.'=*'.$word.'*)';
        }
        for($i=0; $i <count($conf['searchfields']); $i++){
          $wordfilter = '(|'.$wordfilter.')';
        }
        $filter .= '(&'.$wordfilter.')';
      }
      $ldapfilter = "(&(objectClass=inetOrgPerson)$filter)";
    }elseif(!empty($org)){
      // List organization members
      $ldapfilter = '(&(objectClass=inetOrgPerson)('.$FIELDS['organization']."=$org))";
    }elseif($filter=='other'){
      // Alphabetic listing of last names
      $other='';
      for ($i=ord('a');$i<=ord('z');$i++){
        $other .= '(!('.$FIELDS['name'].'='.chr($i).'*))';
      }
      $ldapfilter = "(&(objectClass=inetOrgPerson)$other)";
    }elseif($filter=='\2a'){ //escaped asterisk
      // List all
      $ldapfilter = "(objectClass=inetOrgPerson)";
    }else{
      // Search by last name start
      $ldapfilter = '(&(objectClass=inetOrgPerson)('.$FIELDS['name']."=$filter*))";
    }
    return $ldapfilter;
  }
?>

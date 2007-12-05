<?php
  require_once('inc/init.php');
  ldap_login();

  if ($conf['userlogreq'] && $user == ''){
    header('Location: login.php');
    exit();
  }
  //prepare templates
  tpl_std();
  $smarty->assign('tagcloud',tag_cloud());
  //display templates
  header('Content-Type: text/html; charset=utf-8');
  $smarty->display('tags.tpl');

  function tag_cloud(){
    global $conf;
    global $LDAP_CON;
    global $FIELDS;
    if(!$FIELDS['_marker']) return;

    $result = ldap_queryabooks('(objectClass=inetOrgPerson)',$FIELDS['_marker']);
    $max = 0;
    $min = 999999999;
    $tags = array();
    foreach ($result as $entry){
      if(!empty($entry[$FIELDS['_marker']]) && count($entry[$FIELDS['_marker']])){
        foreach($entry[$FIELDS['_marker']] as $marker){
          $marker = strtolower($marker);
          if (empty($tags[$marker])) { $tags[$marker]=0; }
          $tags[$marker] += 1;
          if($tags[$marker] > $max) $max = $tags[$marker];
          if($tags[$marker] < $min) $min = $tags[$marker];
        }
      }
    }
    ksort($tags);
    tag_cloud_weight(&$tags,$min,$max,6);

    $out = '';
    foreach($tags as $tag => $cnt){
      $out .= '<a href="index.php?marker='.rawurlencode($tag).'" class="cloud_'.$cnt.'">';
      $out .= htmlspecialchars($tag).'</a> ';
    }

    return $out;
  }

  /**
   * Calculate weights for a nicer tagcloud distribution
   */
  function tag_cloud_weight(&$tags,$min,$max,$levels){
    // calculate tresholds
    $tresholds = array();
    for($i=0; $i<=$levels; $i++){
        $tresholds[$i] = pow($max - $min + 1, $i/$levels);
    }

    // assign weights
    foreach($tags as $tag => $cnt){
        foreach($tresholds as $tresh => $val){
            if($cnt <= $val){
                $tags[$tag] = $tresh;
                break;
            }
            $tags[$tag] = $levels;
        }
    }
  }



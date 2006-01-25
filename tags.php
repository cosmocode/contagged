<?
  require_once('init.php');
  ldap_login();

  //prepare templates
  tpl_std();
  $smarty->assign('tagcloud',tag_cloud());
  //display templates
  header('Content-Type: text/html; charset=utf-8');
  $smarty->display('tags.tpl');

  function tag_cloud(){
    global $conf;
    global $LDAP_CON;
    if(!$conf[extended]) return;
    
    $result = ldap_queryabooks('(objectClass=contactPerson)','marker');

    $max = 0;
    $tags = array();
    foreach ($result as $entry){
      if(count($entry['marker'])){
        foreach($entry['marker'] as $marker){
          $marker = strtolower($marker);
          $tags[$marker] += 1;
          if($tags[$marker] > $max) $max = $tags[$marker];
        }
      }
    }
    ksort($tags);

    $out = '';
    foreach($tags as $tag => $cnt){
      $pct = round($cnt * 20 / $max); // percents from 0 to 20

      $out .= '<a href="index.php?marker='.rawurlencode($tag).'" class="tc'.$pct.'">';
      $out .= htmlspecialchars($tag).'</a> ';
    }

    return $out;
  }

?>

<?
require_once('init.php');
ldap_login();

header('Content-Type: text/html; charset=utf-8');

if($_REQUEST['taglookup']){
  ajax_taglookup($_REQUEST['taglookup']);
}elseif($_REQUEST['addnote']){
  ajax_addnote($_REQUEST['addnote'],$_REQUEST['note']);
}elseif($_REQUEST['settags']){
  ajax_settags($_REQUEST['settags'],$_REQUEST['tags']);
}

/**
 * Add a note to the existing notes
 */
function ajax_addnote($dn,$note){
  global $conf;
  global $LDAP_CON;

  // fetch the existing note
  $result = ldap_search($LDAP_CON,$dn,'(objectClass=inetOrgPerson)',array('description'));
  if(ldap_count_entries($LDAP_CON,$result)){
    $result = ldap_get_binentries($LDAP_CON, $result);
  }
  $note = $note."\n\n".$result[0]['description'][0];
  $note = preg_replace("!\n\n\n+!","\n\n",$note);

  $entry['description'] = $note;
  ldap_modify($LDAP_CON,$dn,$entry);


  require_once(dirname(__FILE__).'/smarty/plugins/modifier.noteparser.php');
  print smarty_modifier_noteparser($note);
}

/**
 * Sett tags for a contact
 */
function ajax_settags($dn,$tags){
  global $conf;
  global $LDAP_CON;
  if(!$conf[extended]) return;

  $tags = explode(',',$tags);
  $tags = array_map('trim',$tags);
  $tags = array_unique($tags);

  $entry['marker'] = $tags;
  ldap_modify($LDAP_CON,$dn,$entry);

  foreach ($tags as $tag){
    print '<a href="index.php?marker=';
    print rawurlencode($tag);
    print '" class="tag">';
    print htmlspecialchars($tag);
    print '</a> ';
  }
}

/**
 * Find all tags (markers) starting with the given
 * string
 */
function ajax_taglookup($tag){
  global $conf;
  global $LDAP_CON;
  if(!$conf[extended]) return;

  $search = ldap_filterescape($tag);
  $filter = "(&(objectClass=contactPerson)(marker=$search*))";
  $result = ldap_queryabooks($filter,'marker');

  if(!count($result)) return;

  $tags = array();
  foreach ($result as $entry){
    if(count($entry['marker'])){
      foreach($entry['marker'] as $marker){
        if(preg_match('/^'.preg_quote($tag,'/').'/i',$marker)){
          array_push($tags, strtolower($marker));
        }
      }
    }
  }

  $tags = array_unique($tags);
  sort($tags,SORT_STRING);

  print '<ul>';
  foreach($tags as $out){
    print '<li>'.htmlspecialchars($out).'</li>';
  }
  print '</ul>';
}

?>

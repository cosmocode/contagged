<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>ConTagged - {$lang.ldapab}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--
  <link rel="stylesheet" type="text/css" href="templates/layout.css" />
  <link rel="stylesheet" type="text/css" href="templates/style.css" />
-->

    <link rel="stylesheet" type="text/css" href="styles/layout.css" />
    <link rel="stylesheet" type="text/css" href="styles/design.css" />
    <link rel="stylesheet" type="text/css" href="styles/scripted.css" />

    <script src="scripts/jquery.js" type="text/javascript"></script>
    <script src="scripts/interface/iutil.js" type="text/javascript"></script>
    <script src="scripts/interface/iautocompleter.js" type="text/javascript"></script>
    <script src="scripts/interface/imagebox.js" type="text/javascript"></script>
    <script src="scripts/formatDate.js" type="text/javascript"></script>

    {if $conf.gmapkey}
    <script src="https://maps.google.com/maps?file=api&amp;v=2&amp;key={$conf.gmapkey}" type="text/javascript"></script>
    <script src="scripts/maps.js" type="text/javascript"></script>
    {/if}

    <script src="scripts/gui.js" type="text/javascript"></script>

    <script type="text/javascript">
        {if $entry.dn}
            DN = '{$entry.dn|escape:javascript}';
        {/if}
        {if $user}
            USER = '{$user|escape:javascript}';
        {/if}
    </script>
</head>
<body>

<div id="ldapab">
    {if $LDAPERRORS != ''}
        <div class="ldaperror" onclick="this.style.display = 'none'">
            <h3>{$lang.err_ldap}</h3>
            <p>{$LDAPERRORS}</p>
        </div>
    {/if}


    <div id="titlerow">
        <div class="logo">
            <a href="{$home}">ConTagged</a>
            <span>- {$lang.ldapab}</span>
        </div>

        <div class="search">
            {include file="search.tpl"}
        </div>
    </div>

    <div id="toolbar">
        {include file="toolbar.tpl"}
    </div>


    <div id="filterrow">
        <ul>
            {foreach from=$lettertabs item=letter}
            <li><a href="index.php?filter={$letter}">{$letter}</a></li>
            {/foreach}
            <li><a href="index.php?filter=other">#</a></li>
            <li><a href="index.php?filter=*">*</a></li>
        </ul>
    </div>

  <div id="content">


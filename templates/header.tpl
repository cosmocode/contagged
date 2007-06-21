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
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={$conf.gmapkey}" type="text/javascript"></script>
    <script src="scripts/maps.js" type="text/javascript"></script>
    {/if}

    <script src="scripts/gui.js" type="text/javascript"></script>

    <script type="text/javascript">
        {if $LDAPERRORS != ''}
            window.alert('{$lang.err_ldap}:\n\n{$LDAPERRORS|escape:quotes}');
        {/if}
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
            <li><a href="index.php?filter=a">A</a></li>
            <li><a href="index.php?filter=b">B</a></li>
            <li><a href="index.php?filter=c">C</a></li>
            <li><a href="index.php?filter=d">D</a></li>
            <li><a href="index.php?filter=e">E</a></li>
            <li><a href="index.php?filter=f">F</a></li>
            <li><a href="index.php?filter=g">G</a></li>
            <li><a href="index.php?filter=h">H</a></li>
            <li><a href="index.php?filter=i">I</a></li>
            <li><a href="index.php?filter=j">J</a></li>
            <li><a href="index.php?filter=k">K</a></li>
            <li><a href="index.php?filter=l">L</a></li>
            <li><a href="index.php?filter=m">M</a></li>
            <li><a href="index.php?filter=n">N</a></li>
            <li><a href="index.php?filter=o">O</a></li>
            <li><a href="index.php?filter=p">P</a></li>
            <li><a href="index.php?filter=q">Q</a></li>
            <li><a href="index.php?filter=r">R</a></li>
            <li><a href="index.php?filter=s">S</a></li>
            <li><a href="index.php?filter=t">T</a></li>
            <li><a href="index.php?filter=u">U</a></li>
            <li><a href="index.php?filter=v">V</a></li>
            <li><a href="index.php?filter=w">W</a></li>
            <li><a href="index.php?filter=x">X</a></li>
            <li><a href="index.php?filter=y">Y</a></li>
            <li><a href="index.php?filter=z">Z</a></li>
            <li><a href="index.php?filter=other">#</a></li>
            <li><a href="index.php?filter=*">*</a></li>
        </ul>
    </div>




  <div id="content">


<!--
<table width="100%">
<tr>
  <td class="headrow" colspan="2">
    <table width="100%"><tr>
      <td align="left" valign="bottom">
      </td>
      <td align="right" valign="bottom">
        <a href="help.php"><img src="pix/help.png" border="0" width="16" height="16" align="middle">{$lang.help}</a>
      </td>
    </tr></table>
  </td>
</tr>
<tr>
  <td width="30">
  </td>
  <td>
-->

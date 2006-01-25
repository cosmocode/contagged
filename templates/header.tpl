<html>
<head>
  <title>LDAPab - {$lang.ldapab}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <link rel="stylesheet" type="text/css" href="templates/layout.css" />

  <link rel="stylesheet" type="text/css" href="templates/style.css" />

  <script src="js/prototype.js" type="text/javascript"></script>
  <script src="js/scriptaculous.js" type="text/javascript"></script>
  <script src="js/formatDate.js" type="text/javascript"></script>
  <script src="js/gui.js" type="text/javascript"></script>

  {if $LDAPERRORS != ''}
  <script>
    window.alert('{$lang.err_ldap}:\n\n{$LDAPERRORS|escape:quotes}');
  </script>
  {/if}
</head>
<body>

<div id="ldapab">

  <div id="titlerow">
    <a href="{$home}" class="logo">LDAPab</a>
    <span class="logosmall">- {$lang.ldapab}</span>
  </div>

  <div id="filterrow">
    {include file="filter.tpl"}
  </div>

  <div id="toolbar">
    {include file="toolbar.tpl"}
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

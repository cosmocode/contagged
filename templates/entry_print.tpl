<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>ConTagged - {$lang.ldapab}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" type="text/css" href="styles/layout.css" />
    <link rel="stylesheet" type="text/css" href="styles/design.css" />
    <link rel="stylesheet" type="text/css" href="styles/scripted.css" />

</head>
<body>
  <div id="content" class="print">
    {if $conf.userlogreq == 0 || $user != ''}
      {include file="list_print_entry.tpl"}
    {/if}
  </div>
</body>
</html>

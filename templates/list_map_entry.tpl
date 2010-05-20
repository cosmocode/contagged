
{ldelim}
    adr: '{$entry.street|escape:javascript}, {$entry.zip|escape:javascript} {$entry.location|escape:javascript}, {$entry.country|escape:javascript}',
    info: '\x3ch4\x3e\x3ca href="entry.php?dn={$entry.dn|escape:url}"\x3e'+
          '{$entry.givenname|escape:javascript} {$entry.name|escape:javascript}\x3c/a\x3e\x3c/h4\x3e'+
          '{$entry.organization|escape:javascript}\x3cbr /\x3e'+
          '{$entry.street|escape:javascript}\x3cbr /\x3e'+
          '{$entry.zip|escape:javascript} {$entry.location|escape:javascript}'
{rdelim},



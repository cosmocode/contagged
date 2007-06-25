
{ldelim}
    adr: '{$entry.street|escape:javascript}, {$entry.zip|escape:javascript} {$entry.location|escape:javascript}',
    info: '\x3ch4\x3e{$entry.organization|escape:javascript}\x3c/h4\x3e{$entry.street|escape:javascript}\x3cbr /\x3e{$entry.zip|escape:javascript} {$entry.location|escape:javascript}'
{rdelim},



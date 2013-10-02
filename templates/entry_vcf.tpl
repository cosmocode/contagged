BEGIN:VCARD
VERSION:2.1
N:{$entry.name};{$entry.givenname};;{$entry.title}
FN:{$entry.givenname} {$entry.name}
{if $entry.organisation or $entry.office}ORG;ENCODING=QUOTED-PRINTABLE:{$entry.organisation|escape:qp};{$entry.office|escape:qp}
{/if}
{if $entry.note}NOTE;ENCODING=QUOTED-PRINTABLE:{$entry.note|escape:qp}
{/if}
{if $entry.phone}TEL;WORK;VOICE;ENCODING=QUOTED-PRINTABLE:{$entry.phone|escape:qp}
{/if}
{if $entry.homephone}TEL;HOME;VOICE;ENCODING=QUOTED-PRINTABLE:{$entry.homephone|escape:qp}
{/if}
{if $entry.mobile}TEL;CELL;VOICE;ENCODING=QUOTED-PRINTABLE:{$entry.mobile|escape:qp}
{/if}
{if $entry.fax}TEL;WORK;FAX;ENCODING=QUOTED-PRINTABLE:{$entry.fax|escape:qp}
{/if}
{if $entry.pager}TEL;WORK;PAGER;ENCODING=QUOTED-PRINTABLE:{$entry.pager|escape:qp}
{/if}
{foreach from=$entry.mail item=mail}
EMAIL;INTERNET:{$mail}
{/foreach}
{if $entry.street or $entry.locatin or $entry.plz or $entry.country}ADR;WORK;ENCODING=QUOTED-PRINTABLE:;;{$entry.street|escape:qp};{$entry.location|escape:qp};;{$entry.plz|escape:qp};{$entry.country|escape:qp}
{/if}
{if $entry.homestreet}ADR;HOME;ENCODING=QUOTED-PRINTABLE:;;{$entry.homestreet|escape:qp}
{/if}
{if $entry.url}URL;WORK:{$entry.url}
{/if}
{if $entry.birthday}BDAY:{$entry.birthday}
{/if}
END:VCARD

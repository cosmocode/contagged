BEGIN:VCARD
VERSION:2.1
N:{$entry.name};{$entry.givenname};;{$entry.title}
FN:{$entry.givenname} {$entry.name}
ORG;ENCODING=QUOTED-PRINTABLE:{$entry.organisation|escape:qp};{$entry.office|escape:qp}
NOTE;ENCODING=QUOTED-PRINTABLE:{$entry.note|escape:qp}
TEL;WORK;VOICE;ENCODING=QUOTED-PRINTABLE:{$entry.phone|escape:qp}
TEL;HOME;VOICE;ENCODING=QUOTED-PRINTABLE:{$entry.homephone|escape:qp}
TEL;CELL;VOICE;ENCODING=QUOTED-PRINTABLE:{$entry.mobile|escape:qp}
TEL;WORK;FAX;ENCODING=QUOTED-PRINTABLE:{$entry.fax|escape:qp}
TEL;WORK;PAGER;ENCODING=QUOTED-PRINTABLE:{$entry.pager|escape:qp}
{foreach from=$entry.mail item=mail}
EMAIL;INTERNET:{$mail}
{/foreach}
ADR;WORK;ENCODING=QUOTED-PRINTABLE:;;{$entry.street|escape:qp};{$entry.location|escape:qp};;{$entry.plz|escape:qp};{$entry.country|escape:qp}
ADR;HOME;ENCODING=QUOTED-PRINTABLE:;;{$entry.homestreet|escape:qp}
URL;WORK:{$entry.url}
BDAY:{$entry.birthday}
END:VCARD

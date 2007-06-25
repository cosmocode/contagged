{include file="header.tpl"}

<script type="text/javascript">

    gmap_data = [
        {ldelim}
            adr: '{$entry.street|escape:javascript}, {$entry.zip|escape:javascript} {$entry.location|escape:javascript}',
            info: '\x3ch4\x3e{$lang.business|escape:javascript}\x3c/h4\x3e{$entry.street|escape:javascript}\x3cbr /\x3e{$entry.zip|escape:javascript} {$entry.location|escape:javascript}'
        {rdelim},
        {ldelim}
            adr: '{$entry.homestreet|replace:"\n":", "|escape:javascript}',
            info: '\x3ch4\x3c{$lang.private|escape:javascript}\x3c/h4\x3e{$entry.homestreet|replace:"\n":"\x3cbr /\x3e"|escape:javascript}'
        {rdelim}
    ];
</script>

<div id="map">

    <h1>
    <img src="pix/{$entry.type|h}.png" border="0" width="22" height="22"
    align="middle" title="{$entry.type|h}" alt="" />
    {$entry.givenname|h} {$entry.name|h}
    </h1>


    <div id="google_map"></div>



</div>

{include file="footer.tpl"}

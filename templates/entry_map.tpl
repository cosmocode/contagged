{include file="header.tpl"}

<script type="text/javascript">

    gmap_data = [
        {ldelim}
            adr: '{$entry.street|escape:javascript}, {$entry.zip|escape:javascript} {$entry.location|escape:javascript}',
            info: '<h4>{$lang.business|escape:javascript}</h4>{$entry.street|escape:javascript}<br />{$entry.zip|escape:javascript} {$entry.location|escape:javascript}'
        {rdelim},
        {ldelim}
            adr: '{$entry.homestreet|replace:"\n":", "|escape:javascript}',
            info: '<h4>{$lang.private|escape:javascript}</h4>{$entry.homestreet|replace:"\n":"<br />"|escape:javascript}'
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

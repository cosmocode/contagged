{include file="header.tpl"}

<div id="mapcontainer">
    <h1>
    <img src="pix/{$entry.type|h}.png" border="0" width="22" height="22"
    align="middle" title="{$entry.type|h}" alt="" />
    {$entry.givenname|h} {$entry.name|h}
    </h1>

{if $coords|@count}
    <div id="map"></div>
    <script type="text/javascript">
var coords = {$coords|@json_encode};
{literal}
$(document).ready(function() {
    drawMap(coords);
});
{/literal}
    </script>
{else}
    <p>No coordinates</p>
{/if}
</div>

{include file="footer.tpl"}

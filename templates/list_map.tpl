{include file="header.tpl"}

<div id="mapcontainer">
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

{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" width="100%" align="center" class="list">
{foreach from=$orgs item=org}
    <tr>
        <td class="result" width="20">
            <img src="pix/cog.png" border="0" width="16" height="16" align="middle" />
        </td>
        <td class="result">
            <a href="index.php?org={$org|escape:url}">{$org|h}</a><br>
        </td>
    </tr>
{foreachelse}
    <tr>
        <td align="center">
            <br /><br />{$lang.err_noentries}<br /><br />
        </td>
    </tr>
{/foreach}
</table>

{include file="footer.tpl"}

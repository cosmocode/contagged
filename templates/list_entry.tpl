{if $entry.type}
<tr class="{cycle values="even,odd"}">
    <td width="25">
        <img src="pix/{$entry.type|h}.png" border="0" width="16" height="16" align="middle"
         alt="{$entry.type|h}" title="{$entry.type|h}" />
    </td>
    <td>
        <b><a href="entry.php?dn={$entry.dn|escape:url}">{$entry.name|h}, {$entry.givenname|h}</a></b>
    </td>
    <td>
        <a href="index.php?org={$entry.organization|escape:url}">{$entry.organization|h}</a>&nbsp;
    </td>
    <td>
        {$entry.phone|h}&nbsp;
    </td>
    <td>
        <a href="mailto:{$entry.mail[0]|h}">{$entry.mail[0]|h}</a>&nbsp;
    </td>
    <td width="16">
        {if $entry.photo}
            <a href="img.php?dn={$entry.dn|escape:url}&amp;.jpg" rel="imagebox" target="_blank"
               title="{$entry.givenname|escape} {$entry.name|escape}"><img src="pix/image.png"
               border="0" width="16" height="16" align="middle" alt="{$lang.photo|h}"></a>
        {else}
            &nbsp;
        {/if}
    </td>
</tr>
{/if}

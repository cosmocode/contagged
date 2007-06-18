{if $entry.type}
<tr>
  <td class="result" width="25">
    <img src="pix/{$entry.type|h}.png" border="0" width="16" height="16" align="middle" title="{$entry.type|h}">
  </td>
  <td class="result">
    <b><a href="entry.php?dn={$entry.dn|escape:url}">{$entry.name|h}, {$entry.givenname|h}</a></b>
  </td>
  <td class="result">
    <a href="index.php?org={$entry.organization|escape:url}">{$entry.organization|h}</a>&nbsp;
  </td>
  <td class="result">
    {$entry.phone|h}&nbsp;
  </td>
  <td class="result">
    <a href="mailto:{$entry.mail[0]|h}">{$entry.mail[0]|h}</a>&nbsp;
  </td>
  <td class="result" width="16">
    {if $entry.photo}
      <a href="img.php?dn={$entry.dn|escape:url}&amp;.jpg" rel="imagebox" target="_blank" title="{$entry.givenname|escape} {$entry.name|escape}"><img src="pix/image.png" border="0" width="16" height="16" align="middle" title="{$lang.photo|h}"></a>
    {else}
      &nbsp;
    {/if}
  </td>
</tr>
{/if}

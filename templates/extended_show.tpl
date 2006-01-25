<b>{$lang.extended}</b>
<dl><dd>
  <table>
    <tr>
    {if $entry.anniversary}
    <tr>
      <td align="right">{$lang.anniversary}:</td>
      <td>{$entry.anniversary|date_format:$conf.dateformat}</td>
    </tr>
    {/if}
    {if $entry.marker}
    <tr>
      <td valign="top" align="right">{$lang.marker}:</td>
      <td>
        {foreach from=$entry.marker item=marker}
          <a href="index.php?marker={$marker|escape:url}" class="tag">{$marker}</a> 
        {/foreach}
      </td>
    </tr>
    {/if}
  </table>
</dd></dl>

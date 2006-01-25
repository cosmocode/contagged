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
    <tr>
      <td valign="top" align="right">
        {$lang.marker}:
      </td>
      <td id="tedit_insert">

        <span id="tedit_out">
        {foreach from=$entry.marker item=marker}
          <a href="index.php?marker={$marker|escape:url}" class="tag">{$marker}</a> 
        {/foreach}
        </span>
        {if $user}
          <img src="pix/tag_blue_edit.png" align="right" width="16" height="16" onclick="tedit_showEditor('{$dn}')" class="click" />
        {/if}
      </td>
    </tr>
  </table>
</dd></dl>

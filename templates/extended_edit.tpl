
  <tr>
    <td colspan="2"><b>{$lang.extended}</b></td>
  </tr>
  <tr>
    <td align="right" valign="top" nowrap="nowrap">{$lang.anniversary}:<br><span class="hint">({$lang.msg_dateformat})</span></td>
    <td><input type="text" class="input" name="entry[anniversary]" value="{$entry.anniversary|escape}" maxlength="10"></td>
  </tr>

  <tr>
    <td align="right" valign="top" nowrap="nowrap">{$lang.marker}:</td>
    <td>
      <select name="entry[marker][]" size="5" class="input" multiple="multiple">
        {html_options values=$markers output=$markers selected=$entry.marker}
      </select>
      <input type="text" class="inputbr" name="entry[marker][]"><br>
      <input type="text" class="inputbr" name="entry[marker][]"><br>
    </td>

  </tr>


  <tr>
    <td colspan="2"><b>{$lang.extended}</b></td>
  </tr>
  <tr>
    <td align="right" valign="top" nowrap="nowrap">{$lang.anniversary}:<br><span class="hint">({$lang.msg_dateformat})</span></td>
    <td><input type="text" class="input" name="entry[anniversary]" value="{$entry.anniversary|escape}" maxlength="10"></td>
  </tr>

  <tr>
    <td align="right" valign="top" nowrap="nowrap">{$lang.marker}:<br><span class="hint">({$lang.msg_tagsep})</span></td>
    <td>
      <textarea class="input" name="entry[markers]" id="tageditlookup">{$entry.markers|escape}</textarea><div id="tageditresult" class="autocomplete"></div>
    </td>

  </tr>

<tr>
<td width="50%" valign="top" align="center">
  <table>
  <tr>
    <td colspan="2"><b>{$lang.openxchange}</b></td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.birthday}:</td>
    <td><input type="text" class="input" name="entry[birthday]" value="{$entry.birthday|escape}"></td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.ipphone}:</td>
    <td><input type="text" class="input" name="entry[ipphone]" value="{$entry.ipphone|escape}"></td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.instantmessenger}:</td>
    <td><input type="text" class="input" name="entry[instantmessenger]" value="{$entry.instantmessenger|escape}"></td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.domain}:</td>
    <td><input type="text" class="input" name="entry[domain]" value="{$entry.domain|escape}"></td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.certificate}:</td>
    <td>
      <textarea name="entry[certificate]" rows=6 cols=28 onClick="this.form.elements['entry[certificate]'].select();">{$entry.certificate|escape}</textarea>
      <br>
      <input type=button name=clearCert value="Clear" onClick="if (confirm('Are you sure?')) this.form.elements['entry[certificate]'].value='';">
    </td>
  </tr>
  </table>
</td>
<td width="50%" valign="top" align="center">
  <table>
  <tr>
    <td colspan="2"><b>{$lang.moreopenxchange}</b></td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.country}:</td>
    <td>
      <select name="entry[country][]" class="input">
        <option value="">--- {$lang.select} ---</option>
        {html_options values=$country output=$country selected=$entry.country}
      </select><br>
      <input type="text" class="inputbr" name="entry[country][]"><br>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.timezone}:</td>
    <td>
      <select name="entry[timezone][]" class="input">
        <option value="">--- {$lang.select} ---</option>
        {html_options values=$timezone output=$timezone selected=$entry.timezone}
      </select><br>
      <input type="text" class="inputbr" name="entry[timezone][]"><br>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">{$lang.categories}:</td>
    <td>
      <select name="entry[categories][]" size="5" class="input" multiple="multiple">
        {html_options values=$categories output=$categories selected=$entry.categories}
      </select><br>
      <input type="text" class="inputbr" name="entry[categories][]"><br>
      <input type="text" class="inputbr" name="entry[categories][]"><br>
    </td>

  </tr>
</table>
</td>
</tr>

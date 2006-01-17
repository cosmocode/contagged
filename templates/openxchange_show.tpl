<table width="100%">
  <tr>
      <td width="50%" valign="top">
	<b>{$lang.openxchange}</b>
<dl><dd>
  <table>
    {if $entry.birthday}
    <tr>
      <td align="right">{$lang.birthday}:</td>
      <td>{$entry.birthday}</td>
    </tr>
    {/if}
    {if $entry.ipphone}
    <tr>
      <td align="right">{$lang.ipphone}:</td>
      <td>{$entry.ipphone}</td>
    </tr>
    {/if}
    {if $entry.instantmessenger}
    <tr>
      <td align="right">{$lang.instantmessenger}:</td>
      <td>{$entry.instantmessenger}</td>
    </tr>
    {/if}
    {if $entry.domain}
    <tr>
      <td align="right">{$lang.domain}:</td>
      <td>{$entry.domain}</td>
    </tr>
    {/if}
    {if $entry.certificate}
    <tr>
      <td align="right">{$lang.certificate}:</td>
      <td><form>
        <textarea rows=3 cols=28 name='certificate' onClick='this.form.certificate.select();'>{$entry.certificate}</textarea>
	</form></td>
    </tr>
    {/if}
  </table>
</dd></dl>
</td>
      <td width="50%" valign="top">
	<b>{$lang.moreopenxchange}</b>
<dl><dd>
<table >
    {if $entry.country}
    <tr>
      <td align="right">{$lang.country}:</td>
      <td>{$entry.country}</td>
    </tr>
    {/if}
    {if $entry.timezone}
    <tr>
      <td align="right">{$lang.timezone}:</td>
      <td>{$entry.timezone}</td>
    </tr>
    {/if}
    {if $entry.categories}
    <tr>
      <td valign="top" align="right">{$lang.categories}:</td>
      <td>
        {foreach from=$entry.categories item=categories}
          {$categories}<br>
        {/foreach}
      </td>
    </tr>
    {/if}
</table>
</dd></dl>
      </td>
  </tr>
</table>

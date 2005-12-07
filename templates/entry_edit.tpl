<h1>
  {if $entry.type != ''}
  <img src="pix/{$entry.type}.png" border="0" width="22" height="22" align="middle" title="{$entry.type}">
  {/if}
  {$entry.givenname} {$entry.name}
</h1>

<form action="entry.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="dn" value="{$entry.dn|escape}" />
<input type="hidden" name="save" value="1" />

{include file="ldaperror.tpl"}

<table width="100%">
  <tr>
    <td valign="top" width="50%" align="center">
      <table>
        <tr>
          <td colspan="2"><b>{$lang.business}</b></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.name}:</td>
          <td><input type="text" class="input" name="entry[name]" value="{$entry.name|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.givenname}:</td>
          <td><input type="text" class="input" name="entry[givenname]" value="{$entry.givenname|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.title}:</td>
          <td><input type="text" class="input" name="entry[title]" value="{$entry.title|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.organization}:</td>
          <td>
            <input type="text" class="input" name="entry[organization]" value="{$entry.organization|escape}">
          </td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap"></td>
          <td>
            <select onChange="document.forms[0].elements['entry[organization]'].value=this.options[this.selectedIndex].value" class="input">
              <option value="">--- {$lang.select} ---</option>
              {html_options values=$orgs output=$orgs}
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.office}:</td>
          <td><input type="text" class="input" name="entry[office]" value="{$entry.office|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.street}:</td>
          <td><input type="text" class="input" name="entry[street]" value="{$entry.street|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.zip}:</td>
          <td><input type="text" class="input" name="entry[zip]" value="{$entry.zip|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.location}:</td>
          <td><input type="text" class="input" name="entry[location]" value="{$entry.location|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.phone}:</td>
          <td><input type="text" class="input" name="entry[phone]" value="{$entry.phone|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.fax}:</td>
          <td><input type="text" class="input" name="entry[fax]" value="{$entry.fax|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.pager}:</td>
          <td><input type="text" class="input" name="entry[pager]" value="{$entry.pager|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.manager}:</td>
          <td>
            <select name="entry[manager]" class="input">
              <option value="">--- {$lang.select} ---</option>
              {html_options options=$managers selected=$entry.manager}
            </select>
          </td>
        </tr>

      {if $conf.extended}
        {include file="extended_edit.tpl"}
      {/if}
      </table>
    </td>
    
    <td valign="top" width="50%" align="center">
      
      
      <table>
        <tr>
          <td colspan="2"><b>{$lang.private}</b></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.homestreet}:</td>
          <td><textarea name="entry[homestreet]" class="input" rows="2" cols="30">{$entry.homestreet|escape}</textarea></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.homephone}:</td>
          <td><input type="text" class="input" name="entry[homephone]" value="{$entry.homephone|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.mobile}:</td>
          <td><input type="text" class="input" name="entry[mobile]" value="{$entry.mobile|escape}"></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.url}:</td>
          <td><input type="text" class="input" name="entry[url]" value="{$entry.url|escape}"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{$lang.photo}:<br /><span class="hint">({$lang.msg_jpegonly})</span></td>
          <td>{if $entry.photo}
                <input type="checkbox" class="radio" name="delphoto" id="delphoto" value="1">
                <label for="delphoto">{$lang.delphoto}</label>
                <br />
              {/if}
            <input type="file" class="input" name="photoupload">
          </td>
        </tr>
        

        <tr>
          <td colspan="2"><b>{$lang.mail}</b></td>
        </tr>

        {foreach from=$entry.mail item=mail}      
          <tr>
            <td align="right" valign="top" nowrap="nowrap">{counter}:</td>
            <td><input type="text" class="input" name="entry[mail][]" value="{$mail|escape}"></td>
          </tr>
        {/foreach}
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{counter}:</td>
          <td><input type="text" class="input" name="entry[mail][]" value=""></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">{counter}:</td>
          <td><input type="text" class="input" name="entry[mail][]" value=""></td>
        </tr>

        <tr>
          <td colspan="2"><b>{$lang.note}</b></td>
        </tr>

        <tr>
          <td colspan="2" align="right"><textarea class="input" rows="6" cols="30" name="entry[note]" class="note">{$entry.note|escape}</textarea></td>
        </tr>

      </table>
    </td>
  </tr>
  {if $entry.dn == ''}
  <tr>
    <td colspan="2" align="center">
      {$lang.msg_addto}<br>
      <table><tr><td>
      <input type="radio" name="type" value="public" id="typepublic" class="radio" checked="checked">
      <label for="typepublic"><img src="pix/public.png" border="0" width="22" height="22" align="middle">{$lang.publicbook}</label><br>
      <input type="radio" name="type" value="private" id="typeprivate" class="radio">
      <label for="typeprivate"><img src="pix/private.png" border="0" width="22" height="22" align="middle">{$lang.privatebook}</label>
      </td></tr></table>
    </td>
  </tr>
  {/if}
  <tr>
    <td colspan="2" align="center"><br><input type="submit" class="input" value="{$lang.submit}"></td>
  </tr>
</table>

</form>

<br>

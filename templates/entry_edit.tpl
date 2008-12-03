{include file="header.tpl"}

<div id="edit">

<h1>
  {if $entry.type != ''}
  <img src="pix/{$entry.type|h}.png" border="0" width="22" height="22" align="middle" title="{$entry.type|h}" alt="" />
  {/if}
  {$entry.givenname|h} {$entry.name|h}
</h1>

<form action="entry.php" method="post" enctype="multipart/form-data" accept-charset="utf-8">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="dn" value="{$entry.dn|h}" />
<input type="hidden" name="save" value="1" />

<table width="100%">
<!--  -->
  <tr>
    <td valign="top" width="50%" align="center">
      <table width="100%">
{* always required ... if $fields.name *}
        <tr>
          <th>{$lang.name}:</th>
          <td><input type="text" class="input ac" name="entry[name]" value="{$entry.name|h}" id="firstfield" /></td>
        </tr>
{* /if *}
{if $fields.givenname}
        <tr>
          <th>{$lang.givenname}:</th>
          <td><input type="text" class="input ac" name="entry[givenname]" value="{$entry.givenname|h}" /></td>
        </tr>
{/if}
{if $fields.title}
        <tr>
          <th>{$lang.title}:</th>
          <td><input type="text" class="input ac" name="entry[title]" value="{$entry.title|h}" /></td>
        </tr>
{/if}
      </table>
    </td>

    <td valign="top" width="50%" align="center">
    </td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr>
    <td valign="top" width="50%" align="center">
      <table width="100%">
        <tr>
          <td colspan="2"><h3>{$lang.business}</h3></td>
        </tr>
{if $fields.organization}
        <tr>
          <th>{$lang.organization}:</th>
          <td>
            <input type="text" class="input ac" name="entry[organization]" value="{$entry.organization|h}" />
          </td>
        </tr>
{/if}
{if $fields.office}
        <tr>
          <th>{$lang.office}:</th>
          <td><input type="text" class="input ac" name="entry[office]" value="{$entry.office|h}" /></td>
        </tr>
{/if}
{if $fields.street}
        <tr>
          <th>{$lang.street}:</th>
          <td><input type="text" class="input ac" name="entry[street]" value="{$entry.street|h}" /></td>
        </tr>
{/if}
{if $fields.zip}
        <tr>
          <th>{$lang.zip}:</th>
          <td><input type="text" class="input ac" name="entry[zip]" value="{$entry.zip|h}" /></td>
        </tr>
{/if}
{if $fields.location}
        <tr>
          <th>{$lang.location}:</th>
          <td><input type="text" class="input ac" name="entry[location]" value="{$entry.location|h}" /></td>
        </tr>
{/if}
{if $fields.state}
        <tr>
          <th>{$lang.state}:</th>
          <td><input type="text" class="input ac" name="entry[state]" value="{$entry.state|h}" /></td>
        </tr>
{/if}
{if $fields.country}
        <tr>
          <td align="right" valign="top">{$lang.country}:</td>
          <td>
            <input type="text" class="input ac" name="entry[country]" value="{$entry.country|h}" /><br />
          </td>
        </tr>
{/if}
{if $fields.phone}
        <tr>
          <th>{$lang.phone}:</th>
          <td><input type="text" class="input" name="entry[phone]" value="{$entry.phone|h}" /></td>
        </tr>
{/if}
{if $fields.switchboard}
        <tr>
          <th>{$lang.switchboard}:</th>
          <td><input type="text" class="input" name="entry[switchboard]" value="{$entry.switchboard|h}" /></td>
        </tr>
{/if}
{if $fields.fax}
        <tr>
          <th>{$lang.fax}:</th>
          <td><input type="text" class="input" name="entry[fax]" value="{$entry.fax|h}" /></td>
        </tr>
{/if}
{if $fields.pager}
        <tr>
          <th>{$lang.pager}:</th>
          <td><input type="text" class="input" name="entry[pager]" value="{$entry.pager|h}" /></td>
        </tr>
{/if}
{if $fields.manager}
        <tr>
          <th>{$lang.manager}:</th>
          <td>
            <select name="entry[manager]" class="input">
              <option value="">--- {$lang.select} ---</option>
              {html_options options=$managers selected=$entry.manager}
            </select>
          </td>
        </tr>
{/if}
{if $fields.position}
        <tr>
          <td align="right" valign="top">{$lang.position}:</td>
          <td><input type="text" class="input ac" name="entry[position]" value="{$entry.position|h}" /></td>
        </tr>
{/if}
{if $fields.department}
        <tr>
          <td align="right" valign="top">{$lang.department}:</td>
          <td><input type="text" class="input ac" name="entry[department]" value="{$entry.department|h}" /></td>
        </tr>
{/if}

      </table>
    </td>

    <td valign="top" width="50%" align="center">

      <table width="100%">
        <tr>
          <td colspan="2"><h3>{$lang.private}</h3></td>
        </tr>
{if $fields.homestreet}
        <tr>
          <th>{$lang.homestreet}:</th>
          <td><textarea name="entry[homestreet]" class="input" rows="2" cols="30">{$entry.homestreet|h}</textarea></td>
        </tr>
{/if}
{if $fields.homephone}
        <tr>
          <th>{$lang.homephone}:</th>
          <td><input type="text" class="input" name="entry[homephone]" value="{$entry.homephone|h}" /></td>
        </tr>
{/if}
{if $fields.mobile}
        <tr>
          <th>{$lang.mobile}:</th>
          <td><input type="text" class="input" name="entry[mobile]" value="{$entry.mobile|h}" /></td>
        </tr>
{/if}
{if $fields.photo}
        <tr>
          <th>{$lang.photo}:<br /><span class="hint">({$lang.msg_jpegonly})</span></th>
          <td>{if $entry.photo}
                <input type="checkbox" class="radio" name="delphoto" id="delphoto" value="1" />
                <label for="delphoto">{$lang.delphoto}</label>
                <br />
              {/if}
            <input type="file" class="input" name="photoupload" />
          </td>
        </tr>
{/if}
{if $jpegError}
        <tr>
          <th></th><td>{$jpegError}</td>
        </tr>
{/if}
{if $fields.birthday}
        <tr>
          <td align="right" valign="top">{$lang.birthday}:<br /><span class="hint">({$lang.msg_dateformat})</span></td>
          <td><input type="text" class="input" name="entry[birthday]" value="{$entry.birthday|h}" maxlength="10" /></td>
        </tr>
{/if}
{if $fields.anniversary}
        <tr>
          <th>{$lang.anniversary}:<br /><span class="hint">({$lang.msg_dateformat})</span></th>
          <td><input type="text" class="input" name="entry[anniversary]" value="{$entry.anniversary|h}" maxlength="10" /></td>
        </tr>
{/if}
{if $fields.spouse}
        <tr>
          <th>{$lang.spouse}:</th>
          <td><input type="text" class="input" name="entry[spouse]" value="{$entry.spouse|h}" /></td>
        </tr>
{/if}

        <tr>
          <td colspan="2"><h3>{$lang.communication}</h3></td>
        </tr>
{if $fields.url}
        <tr>
          <th>{$lang.url}:</th>
          <td><input type="text" class="input" name="entry[url]" value="{$entry.url|h}" /></td>
        </tr>
{/if}
{if $fields._mail}
        {foreach from=$entry.mail|smarty:nodefaults item=mail}
        <tr>
          <th>{$lang.mail} {counter}:</th>
          <td><input type="text" class="input" name="entry[mail][]" value="{$mail}" /></td>
        </tr>
        {/foreach}
        <tr>
          <th>{$lang.mail} {counter}:</th>
          <td><input type="text" class="input" name="entry[mail][]" value="" /></td>
        </tr>
        <tr>
          <th>{$lang.mail} {counter}:</th>
          <td><input type="text" class="input" name="entry[mail][]" value="" /></td>
        </tr>
{/if}
{if $fields.instantmessenger}
        <tr>
          <td align="right" valign="top">{$lang.instantmessenger}:</td>
          <td><input type="text" class="input" name="entry[instantmessenger]" value="{$entry.instantmessenger|h}" /></td>
        </tr>
{/if}
{if $fields.ipphone}
        <tr>
          <td align="right" valign="top">{$lang.ipphone}:</td>
          <td><input type="text" class="input" name="entry[ipphone]" value="{$entry.ipphone|h}" /></td>
        </tr>
{/if}

      </table>
    </td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="2"><h3>{$lang.extended}</h3></td>
  </tr>

  <tr>
    <td width="50%" valign="top" align="center">
      <table width="100%">
{if $fields.note}
        <tr>
          <td align="right" valign="top">{$lang.note}:</td>
          <td align="right"><textarea rows="6" cols="30" name="entry[note]" class="input note">{$entry.note|h}</textarea></td>
        </tr>
{/if}

      </table>
    </td>
    <td width="50%" valign="top" align="center">
      <table width="100%">
{if $fields._marker}
        <tr>
          <th>{$lang.marker}:<br /><span class="hint">({$lang.msg_tagsep})</span></th>
          <td>
            <textarea class="input" name="entry[markers]" id="tageditlookup">{$entry.markers|h}</textarea>
          </td>
        </tr>
{/if}
{if $fields.certificate}
        <tr>
          <td align="right" valign="top">{$lang.certificate}:</td>
          <td>
            <textarea name="entry[certificate]" class="input" rows="6" cols="28" onClick="this.form.elements['entry[certificate]'].select();">{$entry.certificate|h}</textarea>
            <br /><input type="button" name="clearCert" value="Clear" onclick="if (confirm('Are you sure?')) this.form.elements['entry[certificate]'].value='';" />
          </td>
        </tr>
{/if}
{if $fields.domain}
        <tr>
          <td align="right" valign="top">{$lang.domain}:</td>
          <td><input type="text" class="input" name="entry[domain]" value="{$entry.domain|h}" /></td>
        </tr>
{/if}
{if $fields.timezone}
        <tr>
          <td align="right" valign="top">{$lang.timezone}:</td>
          <td>
            <input type="text" class="inputbr" name="entry[timezone]" value="{$entry.timezone|h}" /><br />
          </td>
        </tr>
{/if}
      </table>
    </td>
  </tr>

  {if $entry.dn == ''}
  <tr>
    <td colspan="2" align="center">
      {$lang.msg_addto}<br />
      <table><tr><td>
      <input type="radio" name="type" value="public" id="typepublic" class="radio" checked="checked" />
      <label for="typepublic"><img src="pix/public.png" border="0" width="16" height="16" align="middle" />{$lang.publicbook}</label><br />
      <input type="radio" name="type" value="private" id="typeprivate" class="radio" />
      <label for="typeprivate"><img src="pix/private.png" border="0" width="16" height="16" align="middle" />{$lang.privatebook}</label>
      </td></tr></table>
    </td>
  </tr>
  {/if}

  <tr>
    <td colspan="2" align="center"><br /><input type="submit" class="button" value="{$lang.submit}" /></td>
  </tr>
</table>

</form>

</div>

{include file="footer.tpl"}

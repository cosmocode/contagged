{include file="header.tpl"}

{if $entry.photo != ''}
  <a href="img.php?dn={$entry.dn|escape:url}"><img src="img.php?dn={$entry.dn|escape:url}" align="right" class="photo" width="130" /></a>
{/if}
<h1>
  <img src="pix/{$entry.type}.png" border="0" width="22" height="22" align="middle" title="{$entry.type}" />
  {$entry.givenname} {$entry.name}
</h1>

<table width="100%" {if $user}ondblclick="window.location.href='entry.php?dn={$entry.dn|escape:url}&mode=edit'"{/if}>
  <tr>
    <td valign="top" width="50%">
      <b>{$lang.business}</b>
      <dl><dd>
        <table>
          <tr>
            <td colspan="2">
              {$entry.title} {$entry.givenname} {$entry.name}<br>
{if $entry.organization}
              <a href="index.php?org={$entry.organization|escape:url}">{$entry.organization}</a><br>
{/if}
{if $entry.office}
              {$entry.office}<br>
{/if}
{if $entry.street}
              {$entry.street}<br>
{/if}
{if $entry.location}
              {$entry.zip} {$entry.location}<br>
{/if}
{if $entry.state}
              {$entry.state}
{/if}
{if $entry.country}
              {$entry.country}
{/if}
              <br><br>
            </td>
          </tr>
{if $entry.phone}
          <tr>
            <td align="right">{$lang.phone}:</td>
            <td>{$entry.phone}</td>
          </tr>
{/if}
{if $entry.switchboard}
          <tr>
            <td align="right">{$lang.switchboard}:</td>
            <td>{$entry.switchboard}</td>
          </tr>
{/if}
{if $entry.fax}
          <tr>
            <td align="right">{$lang.fax}:</td>
            <td>{$entry.fax}</td>
          </tr>
{/if}
{if $entry.pager}
          <tr>
            <td align="right">{$lang.pager}:</td>
            <td>{$entry.pager}</td>
          </tr>
{/if}
{if $managername}
          <tr>
            <td align="right">{$lang.manager}:</td>
            <td>{$managername}</td>
          </tr>
{/if}
{if $entry.position}
          <tr>
            <td align="right">{$lang.position}:</td>
            <td>{$entry.position}</td>
          </tr>
{/if}
{if $entry.department}
          <tr>
            <td align="right">{$lang.department}:</td>
            <td>{$entry.department}</td>
          </tr>
{/if}

        </table>
      </dd></dl>


    </td>


    <td valign="top" width="50%">
      <b>{$lang.private}</b>
      <dl><dd>
        <table>
          <tr>
            <td colspan="2">
              {$entry.title} {$entry.givenname} {$entry.name}<br>
{if $entry.homestreet}
              {$entry.homestreet|nl2br}<br><br>
{/if}
            </td>
          </tr>
{if $entry.homephone}
          <tr>
            <td align="right">{$lang.homephone}:</td>
            <td>{$entry.homephone}</td>
          </tr>
{/if}
{if $entry.mobile}
          <tr>
            <td align="right">{$lang.mobile}:</td>
            <td>{$entry.mobile}</td>
          </tr>
{/if}
{if $entry.url}
          <tr>
            <td align="right">{$lang.url}:</td>
            <td><a href="{$entry.url}" target="_blank">{$entry.url}</a></td>
          </tr>
{/if}
{if $entry.birthday}
          <tr>
            <td align="right">{$lang.birthday}:</td>
            <td>{$entry.birthday}</td>
          </tr>
{/if}
{if $entry.anniversary}
          <tr>
            <td align="right">{$lang.anniversary}:</td>
            <td>{$entry.anniversary|date_format:$conf.dateformat}</td>
          </tr>
{/if}
{if $entry.spouse}
          <tr>
            <td align="right">{$lang.spouse}:</td>
            <td>{$entry.spouse}</td>
          </tr>
{/if}

        </table>
      </dd></dl>

      <b>{$lang.communication}</b>
      <dl><dd>
        <table>
{if $entry.mail}
          <tr>
            <td valign="top" align="right">{$lang.mail}:</td>
            <td>
              {foreach from=$entry.mail item=mail}
                <a href="mailto:{$mail}">{$mail}</a><br>
              {/foreach}
            </td>
          </tr>
{/if}
{if $entry.instantmessenger}
          <tr>
            <td align="right">{$lang.instantmessenger}:</td>
            <td>{$entry.instantmessenger}</td>
          </tr>
{/if}
{if $entry.ipphone}
          <tr>
            <td align="right">{$lang.ipphone}:</td>
            <td>{$entry.ipphone}</td>
          </tr>
{/if}

        </table>
      </dd></dl>

    </td>
  </tr>
</table>

<hr noshade="noshade" size="1" />

<table width="100%">
  <tr>
    <td width="50%" valign="top">
      <b>{$lang.extended}</b>
      <dl><dd>
        <table>
{if $fields._marker}
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
{/if}
{if $fields.note}
          <tr>
            <td valign="top" align="right">
              {$lang.note}:
            </td>
            <td>
              {if $user}
                 <img src="pix/phone.png" width="16" height="16" onclick="nedit_showEditor('call','{$entry.dn}','{$user|escape:javascript}');" class="click" />
                 <img src="pix/email.png" width="16" height="16" onclick="nedit_showEditor('mail','{$entry.dn}','{$user|escape:javascript}');" class="click" />
                 <img src="pix/arrow_right.png" width="16" height="16" onclick="nedit_showEditor('todo','{$entry.dn}','{$user|escape:javascript}');" class="click" />
                 <img src="pix/note.png" width="16" height="16" onclick="nedit_showEditor('note','{$entry.dn}','{$user|escape:javascript}');" class="click" />
              {/if}
              <dl class="notes"><dd id="nedit_insert">
                {$entry.note|noteparser}
              </dd></dl>
            </td>
          </tr>
{/if}
        </table>
      </dd></dl>
    </td>
    <td width="50%" valign="top">
      <dl><dd>
        <table >
{if $entry.certificate}
         <tr>
           <td align="right">{$lang.certificate}:</td>
           <td><form>
              <textarea rows=3 cols=28 name='certificate' onClick='this.form.certificate.select();'>{$entry.certificate}</textarea>
            </form></td>
          </tr>
{/if}
{if $entry.domain}
          <tr>
            <td align="right">{$lang.domain}:</td>
            <td>{$entry.domain}</td>
          </tr>
{/if}
{if $entry.timezone}
          <tr>
            <td align="right">{$lang.timezone}:</td>
            <td>{$entry.timezone}</td>
          </tr>
{/if}
        </table>
      </dd></dl>
    </td>
  </tr>
</table>



{include file="footer.tpl"}

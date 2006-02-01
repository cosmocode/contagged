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
              {if $entry.organization}<a href="index.php?org={$entry.organization|escape:url}">{$entry.organization}</a><br>{/if}
              {if $entry.office}{$entry.office}<br>{/if}
              {if $entry.street}{$entry.street}<br>{/if}
              {if $entry.location}{$entry.zip} {$entry.location}<br>{/if}
              <br>
            </td>
          </tr>
          {if $entry.phone}
          <tr>
            <td align="right">{$lang.phone}:</td>
            <td>{$entry.phone}</td>
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
        </table>
      </dd></dl>
    </td>


    <td valign="top" width="50%">
      <b>{$lang.private}</b>
      <dl><dd>
        <table>
          <tr>
            <td colspan="2">
              {if $entry.homestreet}
                {$entry.givenname} {$entry.name}<br>
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
        </table>
      </dd></dl>

      {if $managername}
      <b>{$lang.manager}</b>
      <dl><dd>
        <table>
          <tr>
            <td colspan="2">
              {$managername}
            </td>
          </tr>
        </table>
      </dd></dl>
      {/if}
              
      {if $conf.extended}
        {include file="extended_show.tpl"}
      {/if}
    </td>
  </tr>
</table>

<hr noshade="noshade" size="1" />
<b>{$lang.note}</b>

{if $user}
 <img src="pix/phone.png" width="16" height="16" onclick="nedit_showEditor('call','{$entry.dn}','{$user|escape:javascript}');" class="click" />
 <img src="pix/email.png" width="16" height="16" onclick="nedit_showEditor('mail','{$entry.dn}','{$user|escape:javascript}');" class="click" />
 <img src="pix/arrow_right.png" width="16" height="16" onclick="nedit_showEditor('todo','{$entry.dn}','{$user|escape:javascript}');" class="click" />
 <img src="pix/note.png" width="16" height="16" onclick="nedit_showEditor('note','{$entry.dn}','{$user|escape:javascript}');" class="click" />
{/if}

<dl class="notes"><dd id="nedit_insert">
  {$entry.note|noteparser}
</dd></dl>

{if $conf.openxchange}
  {include file="openxchange_show.tpl"}
{/if}

{include file="footer.tpl"}

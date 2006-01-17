<h1>
  <img src="pix/{$entry.type}.png" border="0" width="22" height="22" align="middle" title="{$entry.type}">
  {$entry.givenname} {$entry.name}
</h1>
{if $entry.photo != ''}
  <img src="img.php?dn={$entry.dn|escape:url}" align="right" class="photo">
{/if}

{include file="ldaperror.tpl"}


<table width="100%">
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
      {if $conf.extended}
        {include file="extended_show.tpl"}
      {/if}
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

      {if $entry.note}      
      <b>{$lang.note}</b>
      <dl><dd>
        {$entry.note|nl2br}
      </dd></dl>
      {/if}


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
              
    </td>
  </tr>
</table>
      {if $conf.openxchange}
        {include file="openxchange_show.tpl"}
      {/if}
<br><br><br>


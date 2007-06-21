{include file="header.tpl"}

<div id="show">

{if $entry.photo != ''}
    <a href="img.php?dn={$entry.dn|escape:url}&amp;.jpg" rel="imagebox"
       title="{$entry.givenname|h} {$entry.name|h}"><img src="img.php?dn={$entry.dn|escape:url}"
       align="right" class="photo" width="130" /></a>
{/if}
<h1>
    <img src="pix/{$entry.type|h}.png" border="0" width="22" height="22"
    align="middle" title="{$entry.type|h}" alt="" />
    {$entry.givenname|h} {$entry.name|h}
</h1>

<table width="100%" class="show"
       {if $user}ondblclick="window.location.href='entry.php?dn={$entry.dn|escape:url}&amp;mode=edit'"{/if}>
  <tr>
    <td valign="top" width="50%">
      <h3>{$lang.business}</h3>
        <table width="100%">
          <tr>
            <td colspan="2">

              {$entry.title|h} {$entry.givenname|h} {$entry.name|h}<br />
{if $entry.organization}
              <a href="index.php?org={$entry.organization|escape:url}">{$entry.organization|h}</a><br />
{/if}
{if $entry.office}
              {$entry.office|h}<br />
{/if}
{if $entry.street}
              {$entry.street|h}<br />
{/if}
{if $entry.location}
              {$entry.zip|h} {$entry.location|h}<br />
{/if}
{if $entry.state}
              {$entry.state|h}
{/if}
{if $entry.country}
              {$entry.country|h}
{/if}
              </br /><br />
            </td>
          </tr>
{if $entry.phone}
          <tr>
            <th>{$lang.phone}:</th>
            <td>{$entry.phone|h}</td>
          </tr>
{/if}
{if $entry.switchboard}
          <tr>
            <th>{$lang.switchboard}:</th>
            <td>{$entry.switchboard|h}</td>
          </tr>
{/if}
{if $entry.fax}
          <tr>
            <th>{$lang.fax}:</th>
            <td>{$entry.fax|h}</td>
          </tr>
{/if}
{if $entry.pager}
          <tr>
            <th>{$lang.pager}:</th>
            <td>{$entry.pager|h}</td>
          </tr>
{/if}
{if $managername}
          <tr>
            <th>{$lang.manager}:</th>
            <td>{$managername}</td>
          </tr>
{/if}
{if $entry.position}
          <tr>
            <th>{$lang.position}:</th>
            <td>{$entry.position|h}</td>
          </tr>
{/if}
{if $entry.department}
          <tr>
            <th>{$lang.department}:</th>
            <td>{$entry.department|h}</td>
          </tr>
{/if}

        </table>

    </td>


    <td valign="top" width="50%">
      <h3>{$lang.private}</h3>
        <table width="100%">
          <tr>
            <td colspan="2">
              {$entry.title|h} {$entry.givenname|h} {$entry.name|h}<br />
{if $entry.homestreet}
              {$entry.homestreet|h|nl2br}<br /><br />
{/if}
            </td>
          </tr>
{if $entry.homephone}
          <tr>
            <th>{$lang.homephone}:</th>
            <td>{$entry.homephone|h}</td>
          </tr>
{/if}
{if $entry.mobile}
          <tr>
            <th>{$lang.mobile}:</th>
            <td>{$entry.mobile|h}</td>
          </tr>
{/if}
{if $entry.birthday}
          <tr>
            <th>{$lang.birthday}:</th>
            <td>{$entry.birthday|h}</td>
          </tr>
{/if}
{if $entry.anniversary}
          <tr>
            <th>{$lang.anniversary}:</th>
            <td>{$entry.anniversary|date_format:$conf.dateformat|h}</td>
          </tr>
{/if}
{if $entry.spouse}
          <tr>
            <th>{$lang.spouse}:</th>
            <td>{$entry.spouse|h}</td>
          </tr>
{/if}

        </table>

      <h3>{$lang.communication}</h3>
        <table width="100%">
{if $entry.url}
          <tr>
            <th>{$lang.url}:</th>
            <td><a href="{$entry.url|http}" target="_blank">{$entry.url|h}</a></td>
          </tr>
{/if}
{if $entry.mail}
          <tr>
            <th>{$lang.mail}:</th>
            <td>
              {foreach from=$entry.mail item=mail}
                <a href="mailto:{$mail|escape:url}">{$mail|h}</a><br />
              {/foreach}
            </td>
          </tr>
{/if}
{if $entry.instantmessenger}
          <tr>
            <th>{$lang.instantmessenger}:</th>
            <td>{$entry.instantmessenger|h}</td>
          </tr>
{/if}
{if $entry.ipphone}
          <tr>
            <th>{$lang.ipphone}:</th>
            <td>{$entry.ipphone|h}</td>
          </tr>
{/if}

        </table>

    </td>
  </tr>
</table>

<h3>{$lang.extended}</h3>

<table width="100%" class="show">
  <tr>
    <td width="50%" valign="top">
        <table width="100%">
{if $fields.note}
          <tr>
            <th>
              {$lang.note}:
              <div {if $user}id="noteedit"{/if}>&nbsp;</div>
            </th>
            <td>
              <div id="notes">
                {$entry.note|noteparser}
                &nbsp;
              </div>
            </td>
          </tr>
{/if}
        </table>
    </td>
    <td width="50%" valign="top">
        <table width="100%">
{if $fields._marker}
          <tr>
            <th>
              {$lang.marker}:
              <div {if $user}id="tagedit"{/if}>&nbsp;</div>
            </th>
            <td>
              <span id="taglist">
                {foreach from=$entry.marker item=marker}
                  <a href="index.php?marker={$marker|escape:url}" class="tag">{$marker|h}</a>
                {/foreach}
                &nbsp;
              </span>
            </td>
          </tr>
{/if}
{if $entry.certificate}
         <tr>
           <th>{$lang.certificate}:</th>
           <td><form>
              <textarea rows=3 cols=28 name='certificate' onClick='this.form.certificate.select();'>{$entry.certificate|h}</textarea>
            </form></td>
          </tr>
{/if}
{if $entry.domain}
          <tr>
            <th>{$lang.domain}:</th>
            <td>{$entry.domain|h}</td>
          </tr>
{/if}
{if $entry.timezone}
          <tr>
            <th>{$lang.timezone}:</th>
            <td>{$entry.timezone|h}</td>
          </tr>
{/if}
        </table>
    </td>
  </tr>
</table>


</div>

{include file="footer.tpl"}

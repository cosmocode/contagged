<tr>
    <form action="entry.php" method="post" target="_import_" accept-charset="utf-8">
    <td class="result">
        <b>{$entry.name}, {$entry.givenname}</b>
    </td>
    <td>
        <input type="hidden" name="save" value="1" />
        <input type="hidden" name="entry[name]" value="{$entry.name|escape}" />
        <input type="hidden" name="entry[givenname]" value="{$entry.givenname|escape}" />
        <input type="hidden" name="entry[title]" value="{$entry.title|escape}" />
        <input type="hidden" name="entry[organization]" value="{$entry.organization|escape}" />
        <input type="hidden" name="entry[office]" value="{$entry.office|escape}" />
        <input type="hidden" name="entry[street]" value="{$entry.street|escape}" />
        <input type="hidden" name="entry[zip]" value="{$entry.zip|escape}" />
        <input type="hidden" name="entry[location]" value="{$entry.location|escape}" />
        <input type="hidden" name="entry[phone]" value="{$entry.phone|escape}" />
        <input type="hidden" name="entry[fax]" value="{$entry.fax|escape}" />
        <input type="hidden" name="entry[pager]" value="{$entry.pager|escape}" />
        <input type="hidden" name="entry[homestreet]" value="{$entry.homestreet|escape}" />
        <input type="hidden" name="entry[homephone]" value="{$entry.homephone|escape}" />
        <input type="hidden" name="entry[mobile]" value="{$entry.mobile|escape}" />
        <input type="hidden" name="entry[url]" value="{$entry.url|escape}" />
        {foreach from=$entry.mail item=mail}
        <input type="hidden" name="entry[mail][]" value="{$mail|escape}" />
        {/foreach}
        <input type="hidden" name="entry[note]" value="{$entry.note|escape}" />
        <input type="hidden" name="entry[anniversary]" value="{$entry.anniversary|escape}" />
    </td>
    <td class="result" align="right">
        <button name="type" value="public">
            <img src="pix/public.png" border="0" width="16" height="16" align="middle">{$lang.publicbook}
        </button>
        <button name="type" value="private">
            <img src="pix/private.png" border="0" width="16" height="16" align="middle">{$lang.privatebook}
        </button>
  </td>
  </form>
</tr>


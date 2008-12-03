<tr>
    <td class="result">
        <b>{$entry.name|h}, {$entry.givenname|h}</b><br />
        <i>{$entry.organization|h} {$entry.street|h} {$entry.zip|h} {$entry.location|h}</i>
    </td>
    <td class="result" align="right">
        <form action="entry.php" method="post" target="import" accept-charset="utf-8">
        <input type="hidden" name="save" value="1" />
        <input type="hidden" name="entry[name]" value="{$entry.name|h}" />
        <input type="hidden" name="entry[givenname]" value="{$entry.givenname|h}" />
        <input type="hidden" name="entry[title]" value="{$entry.title|h}" />
        <input type="hidden" name="entry[organization]" value="{$entry.organization|h}" />
        <input type="hidden" name="entry[office]" value="{$entry.office|h}" />
        <input type="hidden" name="entry[street]" value="{$entry.street|h}" />
        <input type="hidden" name="entry[zip]" value="{$entry.zip|h}" />
        <input type="hidden" name="entry[location]" value="{$entry.location|h}" />
        <input type="hidden" name="entry[country]" value="{$entry.country|h}" />
        <input type="hidden" name="entry[state]" value="{$entry.state|h}" />
        <input type="hidden" name="entry[phone]" value="{$entry.phone|h}" />
        <input type="hidden" name="entry[fax]" value="{$entry.fax|h}" />
        <input type="hidden" name="entry[pager]" value="{$entry.pager|h}" />
        <input type="hidden" name="entry[homestreet]" value="{$entry.homestreet|h}" />
        <input type="hidden" name="entry[homephone]" value="{$entry.homephone|h}" />
        <input type="hidden" name="entry[mobile]" value="{$entry.mobile|h}" />
        <input type="hidden" name="entry[url]" value="{$entry.url|h}" />
        {foreach from=$entry.mail item=mail}
        <input type="hidden" name="entry[mail][]" value="{$mail|h}" />
        {/foreach}
        <input type="hidden" name="entry[note]" value="{$entry.note|h}" />
        <input type="hidden" name="entry[birthday]" value="{$entry.birthday|h}" />
	{if $conf.privatebook}
        <button name="type" value="public" class="button">
            <img src="pix/public.png" border="0" width="16" height="16" align="middle" alt="" />{$lang.publicbook}
        </button>
        <button name="type" value="private" class="button">
            <img src="pix/private.png" border="0" width="16" height="16" align="middle" alt="" />{$lang.privatebook}
        </button>
	{else}
           <input type="hidden" name="type" value="public" />
	{/if}
        </form>
    </td>
</tr>



<form action="import.php" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="import">
    <fieldset>
        <legend>{$lang.upload}</legend>
        <label for="upload">{$lang.msg_uploadvcf}:</label>
        <input name="userfile" type="file" class="upload" id="upload" />
        <input type="submit" value="{$lang.upload}" class="button" />
    </fieldset>
</form>

<table cellspacing="0" cellpadding="0" width="100%" align="center" class="list">
{if $list == ''}
    <tr>
      <td align="center">
          {$error}
      </td>
    </tr>
{else}
    {* $list is a concatenation of multiple importVCF_entry.tpl *}
    {$list}
{/if}
</table>

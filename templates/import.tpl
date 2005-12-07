<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td class="filterrow">
  <form action="import.php" method="post" enctype="multipart/form-data">
    {$lang.msg_uploadvcf}: <input name="userfile" type="file" class="upload" />
    <input type="submit" value="{$lang.upload}" class="searchbutton"/>
  </form>
</td>
</tr>
</table>

<br><br>
<table cellspacing="0" cellpadding="0" width="100%" align="center">
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
<br><br><br>

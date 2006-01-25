<ul>

  <!-- help -->
  <li>
    <a href="help.php"><img src="pix/help.png" border="0"
       width="16" height="16" align="middle" title="{$lang.help}" /></a>
  </li>

  <!-- company list -->
  <li>
    <a href="orgs.php"><img src="pix/book_open.png" border="0"
       width="16" height="16" align="middle" title="{$lang.orgs}" /></a>
  </li>

  <li class="sep"></li>

  {if $user} <!-- new contact -->
    <li>
      <a href="entry.php?mode=edit"><img src="pix/page_add.png" border="0"
         width="16" height="16" align="middle" title="{$lang.new}"></a>
    </li>
  {/if}

  <li class="sep"></li>

  {if $dn} <!-- begin contact page functions -->
    {if $user} <!-- begin editing functions -->

      <!-- edit/show, copy, delete -->
      {if $smarty.request.mode == 'edit'}
        <li>
          <a href="entry.php?dn={$dn|escape:url}"><img src="pix/page_red.png"
           border="0" width="16" height="16" align="middle" title="{$lang.show}" /></a>
        </li>
      {elseif $smarty.request.mode != 'copy'}
        <li>
          <a href="entry.php?dn={$dn|escape:url}&mode=edit"><img src="pix/page_edit.png"
             border="0" width="16" height="16" align="middle" title="{$lang.edit}" /></a>
        </li>
        <li>
          <a href="entry.php?dn={$dn|escape:url}&mode=copy"><img src="pix/page_copy.png"
             border="0" width="16" height="16" align="middle" title="{$lang.copy}" /></a>
        </li>
        <li>
          <a href="entry.php?dn={$dn|escape:url}&del=1" onclick="return confirm('{$lang.msg_reallydel}');"><img
             src="pix/page_delete.png" border="0" width="16" height="16" align="middle"
             title="{$lang.delete}" /></a>
        </li>
      {/if}

    {/if} <!-- end editing functions -->

    <!-- vcf export -->
    <li>
      <a href="entry.php?dn={$dn|escape:url}&mode=vcf"><img src="pix/vcard.png"
         border="0" width="16" height="16" align="middle" title="{$lang.vcfexport}" /></a>
    </li>

    <li class="sep"></li>
  {/if} <!-- end contact page functions -->


  {if $list} <!-- export -->
    <li>
      <a href="index.php?filter={$filter|escape:url}&marker={$marker|escape:url}&search={$search|escape:url}&export=csv"><img src="pix/page_white_excel.png" border="0" width="16" height="16" align="middle" title="{$lang.csvexport}" /></a>
    </li>
  {/if}

  {if $user} <!-- import -->
    <li>
      <a href="import.php"><img src="pix/page_in.png" border="0"
         width="16" height="16" align="middle" title="{$lang.vcfimport}" /></a>
    </li>
  {/if}

  

</ul>

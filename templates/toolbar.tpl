<ul>

  <!-- help -->
  <li>
    <a href="help.php" class="ed_help">{$lang.help}</a>
  </li>

  <!-- company list -->
  <li>
    <a href="orgs.php" class="ed_orgs">{$lang.orgs}</a>
  </li>

  <li class="sep">&nbsp;</li>

  {if $user} <!-- new contact -->
    <li>
      <a href="entry.php?mode=edit" class="ed_new">{$lang.new}</a>
    </li>
  {/if}

  <li class="sep">&nbsp;</li>

  {if $dn} <!-- begin contact page functions -->
    {if $user} <!-- begin editing functions -->

      <!-- edit/show, copy, delete -->
      {if $smarty.request.mode == 'edit'}
        <li>
          <a href="entry.php?dn={$dn|escape:url}" class="ed_show">{$lang.show}</a>
        </li>
      {elseif $smarty.request.mode != 'copy'}
        <li>
          <a href="entry.php?dn={$dn|escape:url}&amp;mode=edit" class="ed_edit">{$lang.edit}</a>
        </li>
        <li>
          <a href="entry.php?dn={$dn|escape:url}&amp;mode=copy" class="ed_copy">{$lang.copy}</a>
        </li>
        <li>
          <a href="entry.php?dn={$dn|escape:url}&amp;del=1" onclick="return confirm('{$lang.msg_reallydel}');"
             class="ed_delete">{$lang.delete}</a>
        </li>
      {/if}

    {/if} <!-- end editing functions -->

    <!-- vcf export -->
    <li>
      <a href="entry.php?dn={$dn|escape:url}&amp;mode=vcf" class="ed_vcfexport">{$lang.vcfexport}</a>
    </li>

    <li class="sep">&nbsp;</li>
  {/if} <!-- end contact page functions -->


  {if $list} <!-- export -->
    <li>
      <a href="index.php?filter={$filter|escape:url}&amp;marker={$marker|escape:url}&amp;search={$search|escape:url}&amp;export=csv"
         class="ed_csvexport">{$lang.csvexport}</a>
    </li>
  {/if}

  {if $user} <!-- import -->
    <li>
      <a href="import.php" class="ed_vcfimport">{$lang.vcfimport}</a>
    </li>
  {/if}



</ul>

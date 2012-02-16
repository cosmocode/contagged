<ul>
  {if $user or !$conf.userlogreq}

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
        <li>
          <a href="entry.php?dn={$dn|escape:url}&amp;mode=print" class="ed_printexport">{$lang.printexport}</a>
        </li>

        <!-- qr code -->
        <li>
          <a href="{$entry.qrcode}" rel="imagebox" class="ed_qrcode" title="{$lang.qrcode}">{$lang.qrcode}</a>
        </li>

        <!-- show on map -->
        {if $smarty.request.mode == 'map' && $conf.gmapkey}
            <li>
              <a href="entry.php?dn={$dn|escape:url}" class="ed_show">{$lang.show}</a>
            </li>
        {elseif $conf.gmapkey}
            <li>
              <a href="entry.php?dn={$dn|escape:url}&amp;mode=map" class="ed_map">{$lang.map}</a>
            </li>
        {/if}

        <li class="sep">&nbsp;</li>
      {/if} <!-- end contact page functions -->


      {if $list} <!-- export -->
        {if $smarty.request.export != 'map' && $conf.gmapkey}
            <li>
              <a href="index.php?filter={$filter|escape:url}&amp;marker={$marker|escape:url}&amp;search={$search|escape:url}&amp;org={$org|escape:url}&amp;export=map" class="ed_map">{$lang.map}</a>
            </li>
        {/if}
        <li>
          <a href="index.php?filter={$filter|escape:url}&amp;marker={$marker|escape:url}&amp;search={$search|escape:url}&amp;org={$org|escape:url}&amp;export=csv"
             class="ed_csvexport">{$lang.csvexport}</a>
        <li>
          <a href="index.php?filter={$filter|escape:url}&amp;marker={$marker|escape:url}&amp;search={$search|escape:url}&amp;org={$org|escape:url}&amp;export=print"
             class="ed_printexport">{$lang.printexport}</a>

        </li>
      {/if}

      {if $user} <!-- import -->
        <li>
          <a href="import.php" class="ed_vcfimport">{$lang.vcfimport}</a>
        </li>
      {/if}

  {/if}

  <!-- help -->
  <li class="right">
    <a href="help.php" class="ed_help">{$lang.help}</a>
  </li>



</ul>

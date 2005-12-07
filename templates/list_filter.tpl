<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td class="filterrow">
  <a href="index.php?filter=a">A</a>
  <a href="index.php?filter=b">B</a>
  <a href="index.php?filter=c">C</a>
  <a href="index.php?filter=d">D</a>
  <a href="index.php?filter=e">E</a>
  <a href="index.php?filter=f">F</a>
  <a href="index.php?filter=g">G</a>
  <a href="index.php?filter=h">H</a>
  <a href="index.php?filter=i">I</a>
  <a href="index.php?filter=j">J</a>
  <a href="index.php?filter=k">K</a>
  <a href="index.php?filter=l">L</a>
  <a href="index.php?filter=m">M</a>
  <a href="index.php?filter=n">N</a>
  <a href="index.php?filter=o">O</a>
  <a href="index.php?filter=p">P</a>
  <a href="index.php?filter=q">Q</a>
  <a href="index.php?filter=r">R</a>
  <a href="index.php?filter=s">S</a>
  <a href="index.php?filter=t">T</a>
  <a href="index.php?filter=u">U</a>
  <a href="index.php?filter=v">V</a>
  <a href="index.php?filter=w">W</a>
  <a href="index.php?filter=x">X</a>
  <a href="index.php?filter=y">Y</a>
  <a href="index.php?filter=z">Z</a>
  <a href="index.php?filter=other">#</a>
</td>
{if $conf.extended}
<td class="filterrow" align="right">
  <form method="get" action="index.php" style="display:inline">
    <select name="marker" class="searchfield">
      <option value="">--- {$lang.marker} ---</option>
      {html_options values=$markers output=$markers selected=$smarty.request.marker}
    </select>
    <input type="submit" value="{$lang.search}" class="searchbutton">
  </form>
</td>
{/if}
<td class="filterrow" align="right">
  <form method="get" action="index.php" style="display:inline">
    <input type="text" name="search" class="searchfield" value="{$smarty.request.search}">
    <input type="submit" value="{$lang.search}" class="searchbutton">
  </form>
</td>
</tr>
</table>

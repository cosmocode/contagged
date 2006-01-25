<ul class="x">
  <li><a href="index.php?filter=a">A</a></li>
  <li><a href="index.php?filter=b">B</a></li>
  <li><a href="index.php?filter=c">C</a></li>
  <li><a href="index.php?filter=d">D</a></li>
  <li><a href="index.php?filter=e">E</a></li>
  <li><a href="index.php?filter=f">F</a></li>
  <li><a href="index.php?filter=g">G</a></li>
  <li><a href="index.php?filter=h">H</a></li>
  <li><a href="index.php?filter=i">I</a></li>
  <li><a href="index.php?filter=j">J</a></li>
  <li><a href="index.php?filter=k">K</a></li>
  <li><a href="index.php?filter=l">L</a></li>
  <li><a href="index.php?filter=m">M</a></li>
  <li><a href="index.php?filter=n">N</a></li>
  <li><a href="index.php?filter=o">O</a></li>
  <li><a href="index.php?filter=p">P</a></li>
  <li><a href="index.php?filter=q">Q</a></li>
  <li><a href="index.php?filter=r">R</a></li>
  <li><a href="index.php?filter=s">S</a></li>
  <li><a href="index.php?filter=t">T</a></li>
  <li><a href="index.php?filter=u">U</a></li>
  <li><a href="index.php?filter=v">V</a></li>
  <li><a href="index.php?filter=w">W</a></li>
  <li><a href="index.php?filter=x">X</a></li>
  <li><a href="index.php?filter=y">Y</a></li>
  <li><a href="index.php?filter=z">Z</a></li>
  <li><a href="index.php?filter=other">#</a></li>
  <li><a href="index.php?filter=*">*</a></li>
</ul>

{if $conf.openxchange}
  <div class="categories x">
    <form method="get" action="index.php">
      <select name="categories" class="searchfield">
        <option value="">--- {$lang.categories} ---</option>
        {html_options values=$categories output=$categories selected=$smarty.request.categories}
      </select>
      <input type="submit" value="{$lang.search}" class="searchbutton" />
    </form>
  </div>
{/if}

{if $conf.extended}
  <div class="tags x">
    <form method="get" action="index.php" accept-charset="utf-8">
      <a href="tags.php" class="tag">{$lang.marker}</a>:

      <input name="marker" class="searchfield" type="text" id="taglookup"
       value="{$smarty.request.marker|escape}" />
      <input type="submit" value="{$lang.search}" class="searchbutton" />
    </form>
  </div>
  <div id="tagresult" class="autocomplete"></div>
{/if}

<div class="search x">
  <form method="get" action="index.php" accept-charset="utf-8">
    <input type="text" name="search" class="searchfield"
     value="{$smarty.request.search|escape}" />
    <input type="submit" value="{$lang.search}" class="searchbutton" />
  </form>
</div>

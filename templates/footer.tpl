    </div>

    <div id="footrow">
        <div class="login">
            {if $user == ''}
                <a href="login.php" class="ed_login">{$lang.login}</a>
            {else}
                <a href="login.php?username=" class="ed_logout">{$lang.logout}</a>
            {/if}
        </div>

        {if $user == ''}
            {$lang.notloggedin}
        {else}
            {$lang.loggedinas} <b>{$user}</b>
        {/if}
  </div>
</div>

</body>
</html>


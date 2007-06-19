{include file="header.tpl"}


    <form action="login.php" method="post" accept-charset="utf-8" id="login">
        <fieldset>
            <legend>{$lang.login}</legend>

            <div class="msg">{$msg|h}</div>

            <div class="row">
                <input name="username" type="text" class="input" id="username" />
                <label for="username">{$lang.username}</label>
            </div>

            <div class="row">
                <input name="password" type="password" class="input" id="password" />
                <label for="password">{$lang.password}</label>
            </div>

            <div class="row">
                <input type="checkbox" name="remember" value="1" id="remember" />
                <label for="remember">{$lang.remember}</label>
            </div>

            <input type="submit" value="{$lang.login}" class="button" />

        </fieldset>
    </form>

{include file="footer.tpl"}

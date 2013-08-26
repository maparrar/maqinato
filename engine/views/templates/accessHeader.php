<?php
/** Header File
 * @package web @subpackage templates */
/**
 * Header template
 * 
 * @author https://github.com/maparrar/maqinato
 * @package web
 * @subpackage templates
 */
 ?>
<header>
    <h1 class="logo"><a href="<?php echo Router::path('root'); ?>">maqinato</a></h1>
    <div id="loginForm">
        <?php echo _("Email"); ?>: <input type="text" name="email"/><br />
        <?php echo _("Password"); ?>: <input type="password" name="password"/><br />
        <div id="login"><?php echo _("Login"); ?></div>
    </div>
</header>
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
    <div id="loginForm">
        <div class="field"><div class="name"><?php echo _("Email"); ?>:</div><input type="text" name="email"/></div>
        <div class="field"><div class="name"><?php echo _("Password"); ?>:</div><input type="password" name="password"/></div>
        <div id="login" class="field button"><?php echo _("Login"); ?></div>
    </div>
    <h1 class="logo"><a href="<?php echo Router::path('root'); ?>">maqinato</a></h1>
</header>
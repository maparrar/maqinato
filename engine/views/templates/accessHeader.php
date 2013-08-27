<?php
/** Header File
 * @package web @subpackage templates */
/**
 * Header template
 * 
 * @author https://github.com/maparrar/maqinato
 * @package views
 * @subpackage templates
 */
 ?>
<header>
    <h1 class="logo"><a href="<?php echo Router::path('root'); ?>">maqinato</a></h1>
    <div id="loginForm">
        <div class="field"><input type="text" id="email" placeholder="<?php echo _("Email"); ?>"/></div>
        <div class="field"><input type="password" id="password" placeholder="<?php echo _("Password"); ?>"/></div>
        <div id="login" class="field button"><?php echo _("Login"); ?></div>
    </div>
</header>
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
    <h1 class="logo"><a href="<?php echo Router::path('root'); ?>"><?php echo Maqinato::application(); ?></a></h1>
    <div id="links">
        <div id="logout" class="link cancel"><?php echo _("logout"); ?></div>
    </div>
</header>
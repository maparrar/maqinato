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
//Verifica si hay usuario registrado, sino, elimina la sesión y vuelve al root
if(!Maqinato::user()){
    Router::redirect("");
}
 ?>
<header>
    <h1 class="logo"><a href="<?php echo Router::path('root'); ?>">maqinato</a></h1>
    <div id="links">
        <div id="logout" class="link cancel"><?php echo _("logout"); ?></div>
    </div>
</header>
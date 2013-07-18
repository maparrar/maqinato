<?php
/** Footer File
 * @package web @subpackage templates */
/**
 * Footer template
 * 
 * @author https://github.com/maparrar/maqinato
 * @package web
 * @subpackage templates
 */
 ?>
<footer>
    <div class="container">
        <div class="sixteen columns">
            <div id="footer">
                <ul>
                    <li><a href="<?php echo Router::rel('views');  ?>pages/about.php">About</a></li>
                    <li><a href="<?php echo Router::rel('views');  ?>pages/contact-us.php">Contact us</a></li>
                    <li><a href="<?php echo Router::rel('views');  ?>pages/how-it-works.php">How it works</a></li>';
                    <li><a href="<?php echo Router::rel('views');  ?>pages/terms.php">Terms</a></li>
                    <li><a href="<?php echo Router::rel('views');  ?>pages/privacy.php">Privacy</a></li>
                    <li><a href="<?php echo Router::rel('views');  ?>pages/copyrigth.php">©<?php echo  date("Y"); ?> maqinato</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- MODALES COMUNES A TODAS LAS PÁGINAS -->
<section id="commonModals">
    <?php include_once Router::rel("views").'modals/activity.php'; ?>
</section>
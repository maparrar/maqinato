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
    <?php
        if(!class_exists('Router')) include_once '../../config/Router.php';
        if(Router::server()==Config::$analyticsServer){
            echo '<script type="text/javascript">';
                echo 'var _gaq = _gaq || [];';
                echo "_gaq.push(['_setAccount', 'UA-40320425-1']);";
                echo "_gaq.push(['_setDomainName', 'https://github.com/maparrar/maqinato']);";
                echo "_gaq.push(['_trackPageview']);";
                echo '(function() {';
                  echo "var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;";
                  echo "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
                  echo "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);";
                echo '})();';
            echo '</script>';
        }
    ?>
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
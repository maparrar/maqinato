<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="maqinato is an interactive social giving platform that empowers individuals to make a difference through everyday actions and donations, and allows them to share their contributions with a community of like-minded individuals. Through customized portfolios, people amplify their impact by rallying friends to support a set of causes chosen by them.">
        <meta name="keywords" content="Collect Donations,Volunteer Organizations,Volunteer,Online Donation Services,Social Giving,Donations,Donation online">
        <?php
            /**
             * Index Landing page file
             * @author https://github.com/maparrar/maqinato
             * @package views
             * @subpackage landing
             */
            session_start();
            if(!class_exists('Router')) include_once '../../config/Router.php';
            include_once Router::rel('controllers').'AccessController.php';
            include_once Router::rel('controllers').'ActivityController.php';
            include_once Router::rel('controllers').'DonationController.php';
            //Revisa si viene de https://github.com/maparrar/maqinato y redirecciona a https://www.https://github.com/maparrar/maqinato
            if(($_SERVER['SERVER_NAME']=="https://github.com/maparrar/maqinato"||$_SERVER['SERVER_NAME']=="www.https://github.com/maparrar/maqinato")&&!$_SERVER['HTTPS']){
                header("Location: " . "https://www.https://github.com/maparrar/maqinato/");
                exit();
            }
            //If session was started go to home page
            if (AccessController::checkSession()){
                header("Location: " . Router::rel('views') . "home/");
            }
            //Load the landing Activities
            $activities=ActivityController::landingPageActivities();
            
            //Revisa si la url trae una clave de invitación, si es así, pone los 
            //datos en la sesión para ser tomados durante el signup
            $email="";
            if(array_key_exists("email",$_GET)&&$_GET["email"]!=""&&array_key_exists("key",$_GET)&&$_GET["key"]!=""){
                //Pasa los datos para ser revisados en el registro
                $email=SecurityController::sanitizeString($_GET["email"]);
                $_SESSION["invitationData"]=array(
                            "email" =>  $email,
                            "key"   =>  SecurityController::sanitizeString($_GET["key"])
                        );
            }else{
                unset($_SESSION["invitationData"]);
            }
            
            //INCLUDE CSS SCRIPTS
            Router::css("reset", "jqueryui","skeleton","layout","landing","flexslider","structure","modals","bonslider","pages","search");
            
            //INCLUDE JS SCRIPTS
            //Basic
            Router::js("jquery","jqueryui","jqueryvalidate","modernizr","system","tools");
            //Components
            Router::js("security","ajaxCore","ajaxNonprofits","ajaxRecoveryPassword","ajaxSocial","access","daemons","landing","nonprofits","newsfeed","recoverPassword","placeholder","search");
            //Others
            Router::js("isotope","bonflip","flexslider","bonslider","scrollbar");
            //Write the main configuration variables in HTML to be readed from JS
            Router::configInHtml();
        ?>
        <script type="text/javascript">
            if($.browser.msie){
                if(parseFloat($.browser.version)<9){
                    window.location.replace("../../transactions/browser.php");
                }
            }
            $(document).ready(function(){
                window.system=new System();
                system.init({
                    access:true,
                    landing:true,
                    isInvitation:<?php echo $email===""?"false":"true"; ?>,
                });
                $("#loginFacebook").click(function(e){
                    e.preventDefault();
                    system.debug("Testing Facebook");
                    login();
                });
            });
        </script>
    </head>
    <body id="page" class="landing">
        <div id="fb-root"></div>
        <script>
            // Additional JS functions here
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '328991050550107', // App ID
                    channelUrl : '//www.https://github.com/maparrar/maqinato/vendors/facebook/channel.html', // Channel File
                    status     : true, // check login status
                    cookie     : true, // enable cookies to allow the server to access the session
                    xfbml      : true,  // parse XFBML
                    frictionlessRequests: true
                });
        
                // Additional init code here
                FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
//                        system.debug("Facebook Connected");
                    } else if (response.status === 'not_authorized') {
//                        system.debug("Facebook not authorized");
                    } else {
//                        system.debug("Facebook not logged in");
                    }
                });
            };
            // Load the SDK Asynchronously
            (function(d){
                var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement('script'); js.id = id; js.async = true;
                js.src = "//connect.facebook.net/en_US/all.js";
                ref.parentNode.insertBefore(js, ref);
            }(document));
        </script>        
        <div class="content-top">
            <header>
                <div class="container">
                    <div class="sixteen columns">
                        <div id="header-content">
                            <h1><a class="logo" href="">maqinato</a></h1>
                            <ul>
                                <li><a href="#" id="nonprofits-link" class="resaltado">Nonprofits</a></li>
                                <li><a href="<?php echo Router::rel("pages"); ?>how-it-works.php" class="normal">How it works</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container">
                <div class="eleven columns omega">
                    <div class="highlights">
                        <h2>
                            <img title="Give to the causes you care about share your impact" alt="Give to the causes you care about share your impact" src="<?php echo Router::rel("img"); ?>misc/home-highlights-01.png">
                        </h2>
                    </div>
                </div>
                <div class="five columns omega">
                    <div class="join-forms-content">
                        <!-- LOGIN FORM -->
                        <div id="login-form">
                            <div id="form">
                                <label for="email">Email</label>
                                <p><input type="text" placeholder="Email" name="email" id="email"></p>
                                <label for="pass">Password</label>
                                <p>
                                    <input type="password" placeholder="Password" name="pass" id="password">
                                    <input type="submit" value="LOG IN" class="btn-login" id="login">
                                </p>
                                <p>
                                    <input type="checkbox" value="keep-log" name="keep-log" class="keep-log" id="keep" checked="true">
                                    <label for="keep" class="txt-keep-log">Keep me logged in</label>
                                    <a id="forgot" href="" class="forgot-pass">Forgot your password?</a>
                                </p>
                            </div>
                            <p><a id="gotoSignup" class="btn-register" href="">Not register yet?</a></p>
                        </div>
                        <!-- REGISTRY FORM -->
                        <form class="user-registry" style="display:none;">
                            <!-- <h4 class="sign-up">Sign up</h4> -->
                            <p><input type="text" placeholder="Name" name="first-name" id="name"></p>
                            <p><input type="text" placeholder="Last Name" name="last-name" id="lastname"></p>
<!--                            <p>
                                <select name="sex" id="sex">
                                    <option value="">Gender</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </p>-->
                            <p><input type="text" placeholder="Email" name="email" id="email" value="<?php echo $email; ?>"></p>
                            <p>
                            <select id="country" name="country" placeholder="Country">
                                <option value="">Country</option>
                                <?php
                                    $countries=DonationController::countriesList();
                                    foreach ($countries as $country) {
                                        $countrySelected=$country->getCode();
                                        echo '<option value="'.$country->getCode().'">'.ucwords(strtolower($country->getName())).'</option>';
                                    }
                                ?>
                            </select>
                            </p>
                            <p>    
                            <input id="cityinput" idCity="" name="city"type="text" placeholder="City" autocomplete="off">
                                <div id="content">
                                    <div id="list">

                                    </div>
                                </div>
                            </p>
                            <p><input type="password" placeholder="Password" name="pass" id="new-password"></p>
                            <p><input type="password" placeholder="Confirm Password" name="cofirm-pass" id="confirm"></p>
                            <p><input id="signup" class="btn-join" type="submit" value="join us"></p>
                            <p class="copy_terms">By clicking the Sign Up button, you agree to our <a id="terms-link" acceptedTerms="false" href="">Terms and Conditions</a> 
                                and <a id="privacy-link" href="">Privacy Policy</a>
                            </p>
                                
                            <p class="last"><a id="login-show" href="">Already have an account?</a></p>
                            <!--<div class="fb-login-button" data-show-faces="true" data-width="200" data-max-rows="1"></div>-->
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- END CONTENT TOP -->
        <div class="main-content">
            <div class="container">
                <section id="why_maqinato">
                    <h2>Why maqinato?</h2>
                    <div id="why-maqinato">                        
                        <img src="<?php echo Router::rel("img"); ?>misc/why_maqinato.png">
                    </div>
                </section>
                <section id="our_allies">
                    <h2>Our allies</h2>
                    <div id="our-allies">                        
                        <div id="powered_by">
                            <h3>Power by</h3>
                            <img src="<?php echo Router::rel("img"); ?>misc/powerby-01.png">
                            <div class="secured">
                                <img src="<?php echo Router::rel("img"); ?>misc/glossy.png">
                                <img src="<?php echo Router::rel("img"); ?>misc/GoDaddy-SSL-Cert.jpg">
                            </div>
                        </div>
                        <div id="np_partners">
                            <h3>Partners</h3>
                            <div class="partners">
                                <?php
                                $list=DonationController::organizationsPartners();
                                foreach ($list as $organization){
                                    $url=Router::img('nonprofit/partners/'.strtolower($organization->getLogo()).'.jpg');
                                    $size=getimagesize($url);
                                    $width=$size[0];
                                    echo "<img width='".$width."' class='partner' src=".$url.">";     
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="what_people">
                    <h2>What people are up to</h2>
                    <section id="newsfeeds" class="sixteen columns omega">
                        <!-- LOAD ACTIVITIES  -->
                        <?php echo(ActivityController::htmlActivities($activities)); ?>
                    </section>
                </section>
                <section id="get_started">
                   <h2></h2>
                   <a id="getStarted"></a>
                </section>
            </div>
        </div>
        <?php include_once Router::rel("templates").'footer.php'; ?>
         <!-- LOAD THE HTML MODALS -->
        <section id="modals">
            <?php include_once Router::rel("views").'modals/nonprofits.php'; ?>
            <?php include_once Router::rel("views").'modals/recovery-password.php'; ?>
            <?php include_once Router::rel("views").'modals/termsmodal.php'; ?>
            <?php include_once Router::rel("views").'modals/privacy.php'; ?>
        </section>
    </body>
</html>
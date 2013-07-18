<!DOCTYPE html>
    <head>
        <base href="">  
        <meta charset="utf-8">
        <title>maqinato</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            echo '<base href="http://localhost/maqinato/views/home/">';
            /**
            * Index Home page file
             * @author https://github.com/maparrar/maqinato
             * @package views
             * @subpackage home
            */
            session_start();
            if(!class_exists('Router')) require_once '../../config/Router.php';
            include_once Router::rel('controllers').'AccessController.php';
            include_once Router::rel('controllers').'ActivityController.php';
            include_once Router::rel('controllers').'FolioController.php';
            include_once Router::rel('controllers').'SocialController.php';
            include_once Router::rel('controllers').'DonationController.php';
            //Revisa si viene de https://github.com/maparrar/maqinato y redirecciona a https://www.https://github.com/maparrar/maqinato
            if(($_SERVER['SERVER_NAME']=="https://github.com/maparrar/maqinato"||$_SERVER['SERVER_NAME']=="www.https://github.com/maparrar/maqinato")&&!$_SERVER['HTTPS']){
                header("Location: " . "https://www.https://github.com/maparrar/maqinato/views/home/");
                exit();
            }
            //If session wasn't started go to landing page and destroy the session   
            if (!AccessController::checkSession()){
                AccessController::destroy();
                header("Location: ".Router::rel('root'));
                exit();
            }else{
                //Load the logged User
                $user=AccessController::getSessionUser();
                
                //Redirige a validar, si la cuenta no se ha validado
                if(!AccessController::validatedAccount($user)){
                    header("Location: ".Router::rel('transactions')."validateAccount.php");
                }
                //Load the last Activities
                $activities=ActivityController::userActivities($user,0,Config::$activitiesLoadInit);
                //Get the last activity id
                $lastActivity=0;
                if(count($activities)>0){
                    $lastActivity=$activities[0]->getId();
                }
                //Load random folios
                $folios=FolioController::lastFolios(30);
                
                //Carga la combinación del Feature
                $featureCombination=DonationController::getCombination(Config::$featureCombination);
                if(!$featureCombination){
                    $featureCombination=DonationController::getCombination(66);
                }
                
                //Carga la combinación del Know
                $knowSentence=DonationController::randomKnow();
                $knowCombination=DonationController::getCombination($knowSentence->getSourceId());
                $knowArea=DonationController::getAreaFromCombination($knowCombination);
                
                //Carga la combinación del Know
                $doSentence=DonationController::randomDo();
                
                //Carga la lista de sugerencias no leídas
                $suggestions=SocialController::loadSuggestions("home");
                $htmlSuggests="";
                foreach ($suggestions as $suggestion){
                    $htmlSuggests.='
                        <div 
                            class="suggestion" 
                            id="suggestion'.$suggestion->getId().'" 
                            data-id="'.$suggestion->getId().'" 
                            data-page="'.$suggestion->getPage().'" 
                            data-element="'.$suggestion->getElement().'" 
                            data-position="'.$suggestion->getPosition().'" 
                            data-arrowPosition="'.$suggestion->getArrowPosition().'" 
                            data-height="'.$suggestion->getHeight().'" 
                            data-width="'.$suggestion->getWidth().'" 
                            data-image="'.$suggestion->getImage().'" 
                        >'.
                            '<div class="content">'.
                                $suggestion->getContent().
                            '</div>'.
                            '<div class="arrow"></div>'.
                        '</div>';
                }
            }
            //INCLUDE CSS SCRIPTS
            Router::css("reset", "jqueryui", "skeleton","layout","flexslider","linkparser","structure","home","modals","jcrop","bonslider","search","suggestions");
            //INCLUDE JS SCRIPTS
            //Basic
            Router::js("jquery","jqueryui","modernizr","system","tools");
            //Components
            Router::js("security","ajaxCore","ajaxSocial","access","daemons","notifications","uploader","home","newsfeed","ajaxDeeds","deeds","search","invite","suggestions","placeholder","stories","linkparser");

            //Others
            Router::js("isotope","bonflip","flexslider","bonslider","autosize");
            
            //Write the main configuration variables in HTML to be readed from JS
            Router::configInHtml();
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                //Id de una actividad pasada por URL al home (desde FB o Twitter)
                var externalActivityId=<?php 
                    if(array_key_exists("activity",$_GET)){
                        echo intval($_GET["activity"]);
                    }else{
                        echo 0;
                    }
                    ?>;
                var shareFb=<?php 
                    if(array_key_exists("fb",$_GET)){
                        echo intval($_GET["fb"]);
                    }else{
                        echo 0;
                    }
                    ?>;
                var shareTwitter=<?php 
                    if(array_key_exists("twitter",$_GET)){
                        echo intval($_GET["twitter"]);
                    }else{
                        echo 0;
                    }
                    ?>;
                var user='<?php 
                    if(array_key_exists("user",$_GET)&&$_GET["user"]=='new'){
                        echo $_GET["user"]; 
                    }else{
                        echo 0;
                    }
                    ?>';
                window.system=new System();
                system.init({
                    access:true,
                    home:true,
                    notifications:true,
                    session:true,
                    externalActivityId:externalActivityId,
                    shareFb:shareFb,
                    shareTwitter:shareTwitter,
                    userNew:user
                });
                                //sugerencias
//                var htmlsuggestions='<input type="checkbox" value="no-suggest" name="no-suggest" class="no-suggest"><div id="contentSuggess"><img class="imghome" src='+system.rel("img")+'suggess/home_suggess.png></div>';
//                var dialoggivingsuges=system.dialog({
//                    html:htmlsuggestions,
//                    height:screen.height,
//                    width:screen.width,
//                    styles:"homeSuggess",
//                    position:{
//                        my: "center center",
//                        at: "center center"
//                    },
//                    onOpen:function(){
//                        $("header").css("position","absolute");
//                        $("#top-bar").css("position","absolute");
//                        $(".modalSugges .ui-dialog-titlebar-close").css("display","none");
//                        $("#contentSuggess").click(function(){
//                            $("header").removeAttr("style");
//                            $("#top-bar").removeAttr("style");
//                            system.dialogClose(dialoggivingsuges);
//                        });
//                    }
//                });
            });
            $(window).load(function(){
                //system.debug("Load hi-res images!");
                //borrar x del dialogo de actividad
                $(".ui-dialog .ui-dialog-titlebar .ui-dialog-titlebar-close span").css("display","none");
            });
        </script>
    </head>
    <body id="page" class="home">
        <?php include_once Router::rel("templates").'header.php'; ?>
        <div id="suggestions" class="suggestionsContent">
            <?php echo $htmlSuggests;?>
        </div>
        <section id="top-bar">
            <div class="container">
                <div id="floater" class="sixteen columns omega">
                    <div id="search">
                        <input id="inputSearch" type="text" placeholder="Search">
                        <input id="buttonSearch" class="btn-search" type="submit" value="search">
                        <div id="content">
                            <div id="list">
                                <div id="list-friends"></div>
                                <div id="list-tags"></div>
                                <div id="list-nonprofits"></div> 
                            </div>
                            <div id="buttons">
                                <div id="invite">Invite by Email</div>
                            </div>
                        </div>
                    </div>
                    <a class="btn-good-deeds" href="">bondeed</a>
                    <a class="btn-show-stories" href="">stories</a>
                    <!-- UPLOADSTORY -->
                    <div id="stories" class="hideStories showStories">
                        <section class="upload-stories six columns">
                            <?php
                                echo('<h2>Share your story</h2>');
                                echo('<textarea placeholder="Paste a link & edit the title and description OR write your own story and add a title or an image."></textarea>');
                                echo('<div class="btn-content">');
        //                                            echo('<a href="">Add Files</a>');
        //                                            echo('<a href="">Add photo</a>');
        //                                            echo('<a href="">Add video</a>');
                                    echo('<a class="btn-upload-story" href="">upload</a>');
                                echo('</div>');
                            ?>
                        </section>
                    </div>
                    <a class="btn_start_giving" href="">Start Giving</a>
                </div>
            </div>
        </section>
        <div class="main-content">
            <div class="content-top">
                <div class="container">
                    <section id="portafolio" class="sixteen columns">
                        <div class="border-top"></div>
                        <h2 id="titleFolios">Folios</h2>
                        <h3>By creating a Folio you can save and share a particular Cause Mix. Folios enable you to donate to the same tags again and share it with<br/> others to gain more support. You can upload stories, BonDeeds, links, photos, videos, and track the progress of the Folio over time.</h3>
                        <div class="portafolioCarrusel flexslider carrusel">
                            <ul class="slides">
                                <!-- LOAD FOLIOS  -->
                                <?php echo FolioController::htmlFolios($folios); ?>
                            </ul>
                        </div>
                    </section>
                    <section id="highlights" class="sixteen columns">
                        <!-- FEATURE -->
                        <div class="box feature" combination="<?php echo $featureCombination->getId(); ?>">
                            <div class="head">
                                <h4>Feature</h4>
                                <div class="btn-flip"></div>
                            </div>
                            <ul class="faces">
                                <li class="front-face">
                                    <div class="box-content">
                                        <a class="box-content" href=""><img id="featureImage" src="<?php echo Router::img("featured/".Config::$featureImage); ?>"></a>
                                        <span class="give hover"></span>
                                    </div>
                                </li>
                                <li class="back-face" style="display:none;">
                                    <div class="info">
                                        <p><?php echo Config::$featureContent; ?></p>
                                    </div>
                                    <div class="footpoints-info">
                                        <p><span id="plusPoints">+<?php echo Config::$featurePoints; ?></span> extra points</p>
                                    </div>
                                    <div class="tags-info">
                                        <p><strong>Cause mix</strong></p>
                                        <div class="tag-container">
                                            <nav class="tags-menu">
                                                <div id="tagsFeature" class="tagsArea" >
                                                <?php
                                                    $slices=$featureCombination->getSlices();
                                                    foreach($slices as $slice){
                                                        $tag=DonationController::getTag($slice->getTag());
                                                        $area=DonationController::getTagArea($tag);
                                                        echo'<div id="tag'.$tag->getId().'" class="tag area'.$area->getId().' ui-draggable">'.$tag->getName().'</div>';
                                                    }
                                                ?>
                                                </div>
                                            </nav>
                                        </div>
                                    </div>
                                    <span class="give hover"></span>
                                </li>
                            </ul>
                        </div>
                        <div id="doKnow">
                            <!-- NEWSFEED BOX :: maqinato NEWS -->
                            <div id="do" class="box fp-news" combination="0">
                                <div class="box-title">
                                    <img src="<?php echo Router::rel('img').'areas/area_'.$doSentence->getSourceId().".png"; ?>">
                                    <span id="dospan">Do</span>
                                </div>
                                <div class="box-content">
                                    <p><?php echo $doSentence->getName(); ?></p>
                                </div>
                            </div>
                            <div id="know" class="box fp-news" combination="<?php echo $knowSentence->getSourceId(); ?>">
                                <div class="box-title">
                                    <img src="<?php echo Router::rel('img').'areas/area_'.$knowArea->getId().".png"; ?>">
                                    <span id="knowspan">Know</span>
                                </div>
                                <div class="box-content">
                                    <p><?php echo $knowSentence->getName(); ?></p>
                                </div>
                                <span class="btn-more"></span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>           
            <div class="content-bottom">
                <div class="container">
                    <div>                        
                        <div id="fliter-module-home" class="filter sixteen columns alpha">
                            <ul>
                                <li><a class="btn-fltr flt-all active" data-filter="*" href="">all</a></li>
                                <li><a class="btn-fltr flt-donations" data-filter=".giving" href="">giving</a></li>
                                <li><a class="btn-fltr flt-gooddeeds" data-filter=".deed" href="">bondeeds</a></li>
                                <li><a class="btn-fltr flt-news" data-filter=".story" href="">story</a></li>
                                <li><div class="filter-separador"></div></li>
                                <li>
                                    <a id="impactButton" class="btn-fltr-impact-area" href="">Impact areas<span></span></a>
                                    <div id="impactMenu" class="impact-sub">
                                        <select id="impactSelector">
                                            <option value="1">Protect the Planet</option>
                                            <option value="2">Power to the People</option>
                                            <option value="3">Learn, Grow, Create</option>
                                            <option value="4">Stay Healthy, Be Happy</option>
                                            <option value="5">Speak Your Mind!</option>
                                        </select>
                                        <div class="tags-filter">
                                            <h6>Tags:</h6>
                                            <div class="tag-container">
                                                <!-- Load the list of Tags for each Impact Area -->
                                                <?php
                                                //Load the combination data (Areas and Tags)
                                                $areas = DonationController::areasList();
                                                foreach ($areas as $area) {
                                                    $tags = $area->getTags();
                                                    echo('<div id="tags_area' . $area->getId() . '" class="tags_area">');
                                                    foreach ($tags as $tag) {
                                                        echo('<a id="tag' . $tag->getId() . '" class="tag area' . $area->getId() . '" href="">' . $tag->getName() . '</a>');
                                                    }
                                                    echo('</div>');
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <a id="impactApply" class="btn-fltr-apply" href="">apply</a>
                                        <a id="impactClear" class="btn-fltr-clear" href="">clear</a>
                                    </div>
                                </li>
<!--                                <li>
                                    <a id="sortButton" class="btn-fltr-sortby" href="">Sort by<span></span></a>
                                    <div id="sortMenu" class="sort-sub">
                                        <a id="impact" href="">Impact</a>
                                        <a id="popular" href="">Popular</a>
                                    </div>
                                </li>-->
                            </ul>
                        </div>
                        <section id="newsfeeds" class="sixteen columns omega">
                            <!-- LOAD ACTIVITIES  -->
                            <?php echo(ActivityController::htmlActivities($activities)); ?>
                        </section>
                    </div>
                </div>
            </div>
        </div> <!-- END MAIN CONTENT -->
        <!-- INCLUDE FOOTER   -->
        <?php include_once Router::rel("templates").'footer.php'; ?>
        <!-- LOAD THE HTML MODALS -->
        <section id="modals">
            <?php include_once Router::rel("views").'modals/deeds.php'; ?>
            <?php include_once Router::rel("views").'modals/crop.php'; ?>
            <?php include_once Router::rel("views").'modals/invite.php'; ?>
            <?php include_once Router::rel("views").'modals/stories.php'; ?>
        </section>
    </body>
</html>
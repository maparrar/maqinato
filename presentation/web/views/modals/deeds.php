<?php
    /** Good Deed modal File
     * @package web @subpackage templates */
    /**
     * @author https://github.com/maparrar/maqinato
     * @package web
     * @subpackage Modal
     */

    include_once Router::rel('controllers').'DeedController.php';
    //Carga la lista de sugerencias no leídas
    $deedSuggestions=SocialController::loadSuggestions("bondeeds");
    $htmlDeedSuggestions="";
    foreach ($deedSuggestions as $deedSuggestion){
        $htmlDeedSuggestions.='
            <div 
                class="suggestion" 
                id="suggestion'.$deedSuggestion->getId().'" 
                data-id="'.$deedSuggestion->getId().'" 
                data-page="'.$deedSuggestion->getPage().'" 
                data-element="'.$deedSuggestion->getElement().'" 
                data-position="'.$deedSuggestion->getPosition().'" 
                data-arrowPosition="'.$deedSuggestion->getArrowPosition().'" 
                data-height="'.$deedSuggestion->getHeight().'" 
                data-width="'.$deedSuggestion->getWidth().'" 
                data-image="'.$deedSuggestion->getImage().'" 
            >'.
                '<div class="content">'.
                    $deedSuggestion->getContent().
                '</div>'.
                '<div class="arrow"></div>'.
            '</div>';
    }
    
    
?>
<div id="deedSuggestions" class="suggestionsContent">
    <?php echo $htmlDeedSuggestions; ?>
</div>
<div id="modalDeeds">
<!-- MODAL GOOD DEED GENERATOR -->
    <div id="good-deed-generator">
        <h4><div id="bondeedTitle">bondeed</div></h4>
        <div class="upload-form">
                <!-- <p><span>Upload</span> <a class="btn-photo" href="">photo</a><a class="btn-video" href="">video</a></p> -->
            <h2 id="bondeed-explanation">
                 Share the good deeds you do every day and inspire others! Upload a photo, describe what you did, and choose the tags that are associated with your BonDeed.
            </h2>
            <div class="img-upload-container">
                <div class="controls">
                    <h6 id="bondeedUploadCaption">Upload</h6> 
                    <p><a href="" class="btn-photo">photo</a></p>
                </div>
                <div id="deedMedia"></div>
            </div>
            <label id="whyThisMattersCaption" class="txtarea">Why this matters to me...</label>
            <textarea id="whyMatters" placeholder=""></textarea>
            <a class="btn-good-deeds" href="">Upload bondeed</a>
        </div>
        <div class="tags-seleccion">
            <div class="tags-areas">
                <div class="area-indicator">
                    <h2 id="area1Caption">Protect the Planet</h2>
                </div>
                <div class="tag-container">
                    <?php
                        $area=1;
                        $tags=DeedController::tagsArea($area);
                        foreach ($tags as $tag) {
                            echo '<a href="" class="tag area'.$area.'" id="tag'.$tag->getId().'">'.$tag->getName().'</a>';
                        }
                    ?>
                </div>
            </div>
            <!--  -->
            <div class="tags-areas">
                <div class="area-indicator">
                    <!--<img alt="Protect the Planet" src="img/icons/icn-menu-power-people.png">-->
                    <h2>Power to the People</h2>
                </div>
                <div class="tag-container">
                    <?php
                        $area=2;
                        $tags=DeedController::tagsArea($area);
                        foreach ($tags as $tag) {
                            echo '<a href="" class="tag area'.$area.'" id="tag'.$tag->getId().'">'.$tag->getName().'</a>';
                        }
                    ?>
                </div>
            </div>
            <!--  -->
            <div class="tags-areas">
                <div class="area-indicator">
                    <!--<img alt="Protect the Planet" src="img/icons/icn-menu-learn-creat.png">-->
                    <h2>Learn, Grow, Create</h2>
                </div>
                <div class="tag-container">
                    <?php
                        $area=3;
                        $tags=DeedController::tagsArea($area);
                        foreach ($tags as $tag) {
                            echo '<a href="" class="tag area'.$area.'" id="tag'.$tag->getId().'">'.$tag->getName().'</a>';
                        }
                    ?>
                </div>
            </div>
            <div class="tags-areas">
                <div class="area-indicator">
                    <!--<img alt="Protect the Planet" src="img/icons/icn-menu-health.png">-->
                    <h2>Stay Healthy, Be Happy</h2>
                </div>
                <div class="tag-container">
                    <?php
                        $area=4;
                        $tags=DeedController::tagsArea($area);
                        foreach ($tags as $tag) {
                            echo '<a href="" class="tag area'.$area.'" id="tag'.$tag->getId().'">'.$tag->getName().'</a>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
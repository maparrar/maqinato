<?php
    /** Good Deed modal File
     * @package web @subpackage templates */
    /**
     * @author https://github.com/maparrar/maqinato
     * @package web
     * @subpackage Modal
     */
    include_once Router::rel('controllers').'DeedController.php';
?>
<div id="modalDeeds">
<!-- MODAL GOOD DEED GENERATOR -->
    <div id="good-deed-generator">
        <h4>Share your bondeed within this folio</h4>
        <div class="upload-form">
            <h2 id="bondeed-explanation">
                 Share the good deeds you do every day and inspire others! Upload a photo, describe what you did, and choose the tags that are associated with your BonDeed.
            </h2>            
                <!-- <p><span>Upload</span> <a class="btn-photo" href="">photo</a><a class="btn-video" href="">video</a></p> -->
            <div class="img-upload-container">
                <div class="controls">
                    <h6>Upload</h6> 
                    <p><a href="" class="btn-photo">photo</a></p>
                </div>
                <div id="deedMedia"></div>
            </div>
            <label class="txtarea">Why this matters to me...</label>
            <textarea id="whyMatters" placeholder=""></textarea>
            <a class="btn-good-deeds" href="">Upload bondeed</a>
        </div>
        <div class="tags-seleccion">
            <div class="tags-areas">
                <div class="area-indicator">
                    <!--<img alt="Protect the Planet" src="img/icons/icn-menu-protect-planet.png">-->
                    <h2>Protect the Planet</h2>
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
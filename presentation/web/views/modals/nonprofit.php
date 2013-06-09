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
<div id="modalNonprofit">
<!-- MODAL GOOD DEED GENERATOR -->
    <div id="Nonprofit-generator">
        <h4>Nonprofit Registration</h4>
        <div class="Nonprofit-form">
            <input id="Nonprofit-organization" type="text" amount="Organization name" placeholder="Organization name">
            
            <label class="txtarea">Why this matters to me...</label>
            <textarea id="whyMatters" placeholder="I love animals and love to make a difference and stand up for what I believe... I think that if everyone join this folio it Will make a difference"></textarea>
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
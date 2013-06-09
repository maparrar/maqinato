<?php
    /** Checkout modal File
     * @package web @subpackage templates */
    /**
     * @author https://github.com/maparrar/maqinato
     * @package web
     * @subpackage Modal
     */
?>
<div id="modalbadges">
<!-- MODAL GENERATOR -->
    <div id="badgesPage">
        <h5>Badges</h5>
        <div class="section">
            <div class="scroller">
                <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                <div class="viewport">
                    <div class="overview">
                        <ul id="list">
                        <?php
                        $badges=DonationController::badgesOfUser($profileUser);
                        $imagegris="_gris";
                        foreach ($badges as $badge) {
                            if(intval($badge->getPercent())==100){
                            $imagegris="";
                            }
                            echo '<li>                                        
                                <div id="'.strtolower(str_replace(' ','_',$badge->getName())).'" class="badges">
                                    <img src="'.Router::rel('img').'default/badges/'.strtolower(str_replace(' ','_',$badge->getName())).$imagegris.'.png" width="100" title="'.$badge->getPercent().'%">
                                    <div class="texto">
                                        <h4>'.$badge->getName().'</h4>
                                        <span>'.$badge->getDescription().'</span>
                                    </div>  
                                </div></li>' ; 
                        }?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>        
   </div>
</div>
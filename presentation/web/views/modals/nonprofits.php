<?php
    /** Good Deed modal File
     * @package web @subpackage templates */
    /**
     * @author https://github.com/maparrar/maqinato
     * @package web
     * @subpackage Modal
     */

    include_once Router::rel('controllers').'DonationController.php';
?>
<div id="modalNonprofits">
<!-- MODAL GOOD DEED GENERATOR -->
    <div id="nonprofits-generator">
        <h4>Nonprofits Registration</h4>
        <div id="subtitle"></div>
        <div id="content">
            <div class="upload-form-nonprofits">
               <input id="organization-name-nonprofit" class="text" type="text" placeholder="Organization Name">*
               <input id="mailing-address" class="text" type="text" placeholder="Mailing Address">
               <input id="ein" class="text" type="text" placeholder="EIN">*
               <input id="contact" class="text" type="text" placeholder="Contact">*
               <input id="phone" class="text" type="text" placeholder="Phone">
               <input id="contac-email-address" class="text" type="text" placeholder="Contac Email Address">*
               <input id="bank-name" class="text" type="text" placeholder="Bank Name">
               <input id="router-switch-number" class="text" type="text" placeholder="Router/switch number">
               <input id="bank-account-number" class="text" type="text" placeholder="Bank Account number">
               <input id="paypal-email-address" class="text" type="text" placeholder="PayPal Email Address">
               <select id="preferred-method" type="text">
                   <option value="0">Preferred method of receiving donations</option>
                   <option value="1">PayPal</option>
               </select>
            </div>
            <div class="related">
                <div id="title">Related impact areas and tags</div>
                <div id="areas">
                    <?php
                        $areas=  DonationController::areasList();
                        foreach ($areas as $area) {
                            if($area->getId()<6){
                                echo '<div class="area" id="area'.$area->getId().'">';
                                    echo '<label class="name"><input class="checkArea" type="checkbox"><span>'.$area->getName().'</span></label>';
                                    echo '<div class="tags">';
                                        foreach ($area->getTags() as $tag){
                                            echo '<a href="" class="tag area'.$area->getId().'" id="tag'.$tag->getId().'">'.$tag->getName().'</a>';
                                        }
                                    echo '</div>'; 
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <h3>*Required Fields</h3>
        <input id="send" class="btn-nonprofits" type="submit" value="Send">
    </div>
</div>
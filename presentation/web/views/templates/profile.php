<?php
/** Profile File
 * @package web @subpackage templates */

/**
 * Profile template
 * 
 * @author https://github.com/maparrar/maqinato
 * @package web
 * @subpackage templates
 */
    include_once Router::rel('controllers') . 'DonationController.php';
    include_once Router::rel('controllers') . 'SocialController.php';
    $daoCountry=new DaoCountry();
    $daoUser=new DaoUser();
    $daoDeed=new DaoDeed();
    $daoTag=new DaoTag();
 ?>
<section id="profile">
    <a class="close-profile" href="">X</a>
    <div class="wrapper">
        <div id="scrollbar">
            <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
            <div class="viewport">
                <div class="overview">
                    <!-- USER INFO -->
                    <div class="flip-container">
                        <div class="flip-card">
                            <div class="front face">
                                <div class="user-avatar">
                                    <div class="avatar-head">
                                        <h4>
                                            <a href="" class="user infoName" id="user<?php echo $user->getId(); ?>">
                                                <?php echo ($user->getName().' '.$user->getLastname()); ?>
                                            </a>
                                        </h4>
                                        <p id="infoCountry" class="country">
                                            <?php
                                                $country=DonationController::getCountry($user->getCountry());
                                                echo ucwords(strtolower($country->getName()));
                                            ?>
                                        </p>
                                        
                                        <?php 
                                            $date=DateTime::createFromFormat('Y-m-d H:i:s',$user->getBorn());
                                            echo '<p id="infoBorn" class="born" date="'.date_format($date,'Y-m-d').'">';
                                                echo date_format($date,'M d Y');
                                            echo '</p>';
                                        ?>
                                        
                                        <p id="infoIam" class="i-am"><?php echo $user->getIam(); ?></p>
                                        <a class="profile-edit btn-flip" href="">edit</a>
                                    </div>
                                    <div class="img-avatar-cont">
                                        <?php
                                            $image=Router::rel('data')."users/images/".$user->getId().".png";
                                            if(!file_exists($image)){
                                                $image=Router::rel('data')."users/images/default.png";
                                            }
                                        ?>
                                        <img src="<?php echo $image; ?>">
                                        <span class="img-btn-edit">
                                            <input id="fileupload" type="file" name="file" data-url="<?php echo Router::rel('web'); ?>ajax/social/jxUploadImage.php">
                                        </span>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="back face center">
                            <!-- EDIT USER INFO -->
                                <div id="user-edit">
                                    <div id="user-edit-form">
                                        <label for="name">Name</label>
                                        <p><input id="name" type="text" placeholder="Name" value="<?php echo $user->getName(); ?>"></p>
                                        <label for="lastname">Last Name</label>
                                        <p><input id="lastname" type="text" placeholder="Lastname" value="<?php echo $user->getLastname(); ?>"></p>
                                        <label for="born">Birthday</label>
                                        <p><input id="born" type="text" placeholder="Date of birth" value="<?php echo date_format($date,'M d Y'); ?>"></p>
                                        <label for="iam">Interest</label>
                                        <p><input id="iam" type="text" placeholder="I am (Animal friend, Environmentalist)" value="<?php echo $user->getIam(); ?>"></p>
                                        <label for="country">Country</label>
                                        <p>
                                            <select id="country" name="country" placeholder="Country"> 
                                                <?php
                                                    $countries=DonationController::countriesList();
                                                    foreach ($countries as $country) {
                                                        if($country->getId()==$user->getCountry()){
                                                            echo '<option value="'.$country->getId().'" selected="selected">'.ucwords(strtolower($country->getName())).'</option>';
                                                        }else{
                                                            echo '<option value="'.$country->getId().'">'.ucwords(strtolower($country->getName())).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </p>
                                        <label for="city">City</label>
                                        <p>
                                            <select id="city" name="city" placeholder="City"> 
                                                <?php
                                                    $countries=DonationController::countriesList();
                                                    foreach ($countries as $country) {
                                                        if($country->getId()==$user->getCountry()){
                                                            echo '<option value="'.$country->getId().'" selected="selected">'.ucwords(strtolower($country->getName())).'</option>';
                                                        }else{
                                                            echo '<option value="'.$country->getId().'">'.ucwords(strtolower($country->getName())).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </p>
                                    </div>
                                    <a id="cancel" class="profile-edit btn-flip" href="">cancel</a>
                                    <a id="editInfo" class="profile-edit btn-flip" href="">save</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- FOOTPOINTS MODULE -->
                    <div class="footpoints-module">
                        <h4><span>
                            <?php
                                $footpoints=DonationController::footpointsUser($user->getId());
                                if(!$footpoints){$footpoints=0;}
                                echo $footpoints;
                            ?></span> points</h4>
                        <p class="total-currency">$ <span>
                            <?php 
                                $given=DonationController::amountUser($user->getId());
                                if(!$given){$given=0;}
                                echo round($given);
                            ?>
                        </span> given</p>
                        <div class="areas-given">
                            <?php
                                $sumation=0;
                                for($i=0;$i<4;$i++){
                                    $percent=DonationController::percentUserToArea($user->getId(),$i+1);
                                    $sumation+=$percent;
                                    echo '<div>';
                                        echo '<img src="'.Router::rel('web').'js/combination/img/areas/'.($i+1).'.png">';
                                        echo '<p>'.round(100*$percent,1).'%</p>';
                                    echo '</div>';
                                }
                                echo '<div>';
                                    echo '<img src="'.Router::rel('web').'js/combination/img/areas/'.($i+1).'.png">';
                                    echo '<p>'.round(100*(1-$sumation),1).'%</p>';
                                echo '</div>';
                            ?>
                        </div>
                    </div>
                <!-- GOOD DEED MODULE -->
                    <div id="good-deed-module">
                        <h4><a id="user<?php echo $user->getId(); ?>" class="user" href="">Good Deeds</a></h4>
                        <p><span>
                                <?php 
                                    $deeds=$daoDeed->listing($user->getId());
                                    echo count($deeds); 
                                ?> uploaded
                            </span>&nbsp;<span>0 likes</span></p>
                        <div class="tag-container">
                            <?php 
                                $tags=array();
                                foreach ($deeds as $deed) {
                                    $tagsDeed=$deed->getTags();
                                    foreach ($tagsDeed as $tagDeed) {
                                        array_push($tags,$tagDeed);
                                    }
                                }
                                $added=array();
                                foreach ($tags as $tag){
                                    $exist=false;
                                    foreach ($added as $add) {
                                        if($add==$tag->getId()){
                                            $exist=true;
                                        }
                                    }
                                    if(!$exist){
                                        $added[]=$tag->getId();
                                        $area=$daoTag->getArea($tag,false);
                                        echo '<p class="area'.$area->getId().'">'.$tag->getName().'</p>';
                                    }
                                }
                            ?>
                        </div>
                    </div>

                <!-- BADGES MODULE -->
                    <div class="badges-indicator">
                        <h4><a id="user<?php echo $user->getId(); ?>" class="user" href="">Badges</a></h4>
                        <?php
                            $badges=DonationController::userEarnedBadges($user->getId());
                            foreach ($badges as $badge){
                                echo '<img title="'.$badge->getName().'" class="miniprofileBadge" src="'.Router::rel('data').'badges/'.$badge->getLogo().'.png">';
                            }
                        ?>
                    </div>

                <!-- FRIENDS MODULE -->
                    <div class="friends-module">
                        <h4>Friends you may know</h4>
                        <div class="friends-img-cont">
                            <?php 
                                $users=SocialController::suggestedFriends($user->getId());
                                foreach ($users as $rndUser){
                                    echo '<a id="user'.$rndUser->getId().'" class="user" title="'.$rndUser->name().'">'.
                                            '<img src="'.Router::rel('data').'users/thumbnails/'.$rndUser->getId().'.jpg">'.
                                        '</a>';
                                }
                            ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <!-- MODALS  -->
    <div class="modals">
        <div id="goog-deed-modal">
            <img src="<?php echo Router::rel('web'); ?>img/fpo/good-deeds.png">
        </div>
    </div>
</section>
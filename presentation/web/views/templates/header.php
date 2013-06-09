<?php
/** Header File
 * @package web @subpackage templates */
/**
 * Header template
 * 
 * @author https://github.com/maparrar/maqinato
 * @package web
 * @subpackage templates
 */
 ?>
<header>
    <div class="container">
        <div class="sixteen columns">
            <div id="header-content">
                <div id="profile-handel">
                    <?php 
                        if(@$user){
                            echo '<div id="user'.$user->getId().'" class="user-img user">'.
                                    '<img src="'.Router::img("users/thumbnails/".$user->getId().".jpg").'">'.
                                '</div>';
                        }
                    ?>
                    <div id="<?php
                            if(@$user){
                                echo "user".$user->getId();
                            }
                        ?>" class="user-info user">
                        <?php
                            if(@$user){
//                                if($user->getType()->getName()!=3){
                                    echo '<div class="notifications notifications-empty">0</div>';
                                    echo '<div class="user-notification-content notifScroller">';
                                        echo '<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>';
                                        
                                        echo '<div class="viewport">';
                                            echo '<div class="overview">';
                                                echo '<ul id="list"></ul>';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
//                                }
                            }
                        ?>
                        <?php
                            if(@$user){
                                echo '<div class="user-name" id="user'.$user->getId().'">';
                                    echo $user->getName() . ' ' . $user->getLastname();
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
                <h1><a class="logo" href="<?php echo Router::rel('root'); ?>">maqinato</a></h1>
                <ul>
                    <?php
                        if(@$user){
                            echo '<li><a href="'.Router::rel('views').'friends/index.php?userid='.$user->getId().'">Friends</a></li>';
                            echo '<li><a href="'.Router::rel('views').'settings/">Settings</a></li>';
                            echo '<li><a href="" id="logout">Log out</a></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
        //Vendors
        Router::js("scrollbar");
    ?>
</header>
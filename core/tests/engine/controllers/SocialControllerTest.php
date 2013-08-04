<?php
/** SocialController File
 * @package controllers @subpackage social */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'dal/core/DaoUser.php';
include_once Router::rel('models').'dal/social/DaoStory.php';
include_once Router::rel('models').'dal/social/DaoNotification.php';
include_once Router::rel('models').'dal/social/DaoContactUs.php';
include_once Router::rel('models').'dal/social/DaoLike.php';
include_once Router::rel('models').'dal/social/DaoFolio.php';
include_once Router::rel('models').'dal/social/DaoInvitation.php';
include_once Router::rel('models').'dal/social/DaoSuggestion.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'ActivityController.php';
include_once Router::rel('controllers').'SecurityController.php';
include_once Router::rel('controllers').'MediaController.php';
/**
 * SocialController Class
 *
 * This class manages Social interactions with the system. Any data received is 
 * verified by the security manager. The data returned are also verified.
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package controllers
 * @subpackage social
 */
class SocialController {
    /**
     * Get an User Object registered in the system
     * @param string user email
     * @param int User identification [optional]
     * @return User User loaded
     */
    public static function getUser($email,$id=0){
        $response=false;
        $dao=new DaoUser();
        if(SecurityController::isemail($email)){
            $response=$dao->read($email);
        }else if(SecurityController::isint($id)){
            $response=$dao->read("",$id);
            if($response){
                if(!$response->getEmail()){
                    $response=false;
                }
            }
        }
        return $response;
    }
    /**
     * Return a list of Users, searching in: name, lastname, email
     * @param string Keyword to search
     * @param int $max máxima cantidad de resultados a cargar
     * @param bool True if need return the registered user
     * @return User[] List of Users that match with the keyword
     */
    public static function searchUsers($keyword,$max=10,$self=false){
        $response=null;
        if (AccessController::checkSession()){
            if(SecurityController::isint($max)){
                $dao=new DaoUser();
                $user=AccessController::getSessionUser();
                if($self===false){
                    $response=$dao->searchUsers(SecurityController::sanitizeString($keyword),$user->getId(),$max,$user->getId());
                }else{
                    $response=$dao->searchUsers(SecurityController::sanitizeString($keyword),$user->getId(),$max);
                }
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."variable max must be an integer");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Send a request from the Session User to the input user. Return:
     *      - "already" if are friends
     *      - "friend" if the input user already sent a request
     *      - "request" if the request was sent to the input user
     *      - "resend" if the request already was sent and need resend
     *      - null if cannot sent the request
     *      - Error object if the session isn't valid or another error
     * Also send the notification
     * @param int Identification of the user that want to send the request
     * @return mixed Value with the request result
     */
    public static function requestFriend($friendId){
        $response=null;
        if (AccessController::checkSession()){
            if(SecurityController::isint($friendId)){
                $dao=new DaoUser();
                $user=AccessController::getSessionUser();
                $friend=self::getUser("",$friendId);
                //Create the notification
                $notification=new Notification();
                $notification->setType("friendship");
                //Evaluate the state of the friendship
                $state=$dao->isFriend($user->getId(),$friend->getId());
                if($state===true){
                    $response="already";
                }elseif($state===false){
                    $dao->requestFriend($user->getId(),$friend->getId());
                    $response="request";
                    $notification->addParameter("type","request");
                }elseif($state==="received"){
                    //Marca como aceptado en la base de datos
                    $dao->acceptFriend($user->getId(),$friend->getId());
                    //Verifica si se aplica el beneficio de los 5 USD por 10 amigos
                    self::checkTenFriends($user);
                    self::checkTenFriends($friend);
                    //If accept the frienship, create an Activity to all friends of both new friends
                    ActivityController::activityFromFrienship($user,$friend);
                    //Associate the past activities to both users
                    ActivityController::associatePastActivities($user,$friend);
                    $response="friend";
                    $notification->addParameter("type","received");
                }elseif($state==="sent"){
                    $response="resend";
                    $notification->addParameter("type","resend");
                }
                $notification->addParameter("sender",$user->getId());
                $notification->addParameter("recipient",$friendId);
                $notification->setRecipient($friend);
                self::sendNotification($notification,array($friend));
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Crea automáticamente una amistad entre el usuario A y el B, además envía 
     * las notificaciones
     * @param $inviter Persona que invitó
     * @param $guest Persona invitada
     * @return bool True si se pudo ejecutar la operación
     */
    public static function makeFriendsByInvitation($inviter,$guest){
        $response=null;
        if (AccessController::checkSession()){
            if(SecurityController::isclass($inviter,"User")&&SecurityController::isclass($guest,"User")){
                $dao=new DaoUser();
                //Crea la amistad en la base de datos, si no existe
                if(!$dao->isFriend($inviter->getId(),$guest->getId())){
                    $dao->requestFriend($inviter->getId(),$guest->getId());
                    $dao->acceptFriend($guest->getId(),$inviter->getId());
                }
                //Create the notification
                $notification=new Notification();
                $notification->setType("friendship");
                //If accept the frienship, create an Activity to all friends of both new friends
                ActivityController::activityFromFrienship($inviter,$guest);
                //Associate the past activities to both users
                ActivityController::associatePastActivities($inviter,$guest);
                $notification->addParameter("type","received");
                $notification->addParameter("sender",$guest->getId());
                $notification->addParameter("recipient",$inviter->getId());
                $notification->setRecipient($inviter);
                self::sendNotification($notification,array($inviter));
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Se ejecuta cuando un usuario acepta una solicitud de amistad para ver si aplica
     * para recibir los 5USD cuando completa 10 amigos. Además crea el badge de pioneer
     * @param User $user Objeto de usuario del que se quiere verificar si puede recibir los 5USD
     */
    private static function checkTenFriends($user){
        $dao=new DaoUser();
        $daoMov=new DaoMovement();
        $daoUserType=new DaoUserType();
        if($user->getType()->getName()==="new"){
            $numberFriends=count($dao->listOfStandardFriends($user));
            if($numberFriends===10){
                //Si el usuario es "new" se convierte a "standard" y se le agregan 5USD a la bolsa
                $user->setType($daoUserType->readFromName("standard"));
                $dao->updateType($user);
                //Crea el movimiento de entrada de dinero
                $movement=new Movement(0,5,$user->getId());
                $movement->setType($daoMov->getMovementTypeByName("invitations"));
                $daoMov->create($movement);
                //Agrega los puntos al badge de Pioneer
                $daoBad=new DaoBadge();
                $badge=$daoBad->addPointsToPioneerBadge($user,300);
                //Si retorna un badge obtenido, crea la actividad y la notificación
                if($badge){
                    self::showNewBadges($user,array($badge));
                }
            }
        }
    }
    /**
     * Se ejecuta cuando un usuario acepta una solicitud de amistad para ver si aplica
     * para recibir los 5USD cuando completa 10 amigos. Además crea el badge de pioneer
     * @param User $user Objeto de usuario del que se quiere verificar cuantos amigos tiene
     */
    public static function standardFriends($user){
        $dao=new DaoUser();
        if(SecurityController::isclass($user,"User")){
            $numberFriends=count($dao->listOfStandardFriends($user));
            return $numberFriends;
        }
        return false;
    }
    /**
     * Delete the friendhip between the registered user and the friend id provided
     * @param int Friend id
     * @return true if the friendship was removed, false otherwise
     */
    public static function deleteFriend($friendId){
        $response=false;
        if (AccessController::checkSession()){
            if(SecurityController::isint($friendId)){
                $dao=new DaoUser();
                $user=AccessController::getSessionUser();
                $response=$dao->removeFriendship($user->getId(),$friendId);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Trying to use a non int value for friend id");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Return:
     *      - true if the user A is friend of B
     *      - false if the user A is not friend of B
     *      - "sent" if A sent an invitation to B
     *      - "received" if A received an invitation from B
     * @param User User A
     * @param User User B
     * @return mixed Boolean or string if are or not friends, or string with request state
     */
    public static function isFriend($userA,$userB){
        $response=null;
        if (AccessController::checkSession()){
            $dao=new DaoUser();
            if(SecurityController::isint($userA->getId())&&SecurityController::isint($userB->getId())){
                $response=$dao->isFriend($userA->getId(),$userB->getId());
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Return the list of friend of an User
     * @param int Identification of the user that want return friends
     * @return User[] List of friends
     */
    public static function listOfFriends($userId){
        $response=null;
        if (AccessController::checkSession()){
            if(SecurityController::isint($userId)){
                $dao=new DaoUser();
                $user=self::getUser("",$userId);
                $response=$dao->listOfFriends($user);
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Return a list of N suggested friends
     * @param int User id
     * @param int Quantity of suggested friends
     * @return User[] List of N suggested friends
     */
    public static function suggestedFriends($idUser,$number=5){
        $users=array();
        if (AccessController::checkSession()){
            if(SecurityController::isint($idUser)&&SecurityController::isint($number)){
                $daoUser=new DaoUser();
                $users=$daoUser->suggestedFriends($idUser,$number);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Trying to use a non int value for user or area");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $users;
    }
    /**
     * Associate the maqinato Team to an User
     * @param int User id
     */
    public static function teamFriends($idUser){
        $response=null;
        if (AccessController::checkSession()){
            if(SecurityController::isint($idUser)){
                $dao=new DaoUser();
                $user=AccessController::getSessionUser();
                //Retorna el equipo de maqinato
                $teamUsers=$dao->teamFriends();
                //Create the notification
                $notification=new Notification();
                $notification->setType("friendship");
                $notification->addParameter("type","received");
                $notification->addParameter("sender",$user->getId());
                foreach ($teamUsers as $teamUser){
                    $dao->requestFriend($teamUser->getId(),$user->getId());
                    $dao->acceptFriend($user->getId(),$teamUser->getId());
                    ActivityController::associatePastActivities($user,$teamUser);
                    $notification->addParameter("recipient",$teamUser->getId());
                    $notification->setRecipient($teamUser);
                    self::sendNotification($notification,array($teamUser));
                }
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Guarda la imagen del usuario actual a partir de la ruta del archivo
     * @param string $media Ruta temporal de la imagen
     * @return string Nombre conel que quedó almacenada la imagen
     */
    public static function saveUserImage($mediaName){
        $response=false;
        if (AccessController::checkSession()){
            $mediaName=SecurityController::sanitizeString($mediaName);
            $user=AccessController::getSessionUser();
            //Move the temporal image
            if(trim($mediaName)!="false"){
                Router::copyFile("temp/".$mediaName,'users/images/'.$user->getId().'.jpg');
                //Crea el thumbnail de la imagen
                $thumbnail=MediaController::resizeJpg(Router::img('users/images/'.$user->getId().'.jpg'),36,35,true);
                //Guarda el thumbnail en la carpeta temporal del sistema
                $path=sys_get_temp_dir()."/".uniqid().'.jpg';
                imagejpeg($thumbnail,$path);
                //Copia la imagen a la carpeta de data
                Router::saveFile($path,'users/thumbnails/'.$user->getId().'.jpg');
                //Elimina los archivos temporales
                Router::deleteFile("temp/".$mediaName);
                //Retorna el nombre de los archivos
                $response=$user->getId().'.jpg';
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    //**************** COMMENTS ****************//
    /**
     * Save in database a Comment
     * @param string Comment string
     * @param int Activity identificator (optional)
     * @param int toUser identificator (optional)
     * @return Comment Comment Object
     */
    public static function saveComment($commentText,$activityId=0,$toUserId=0){
        $response=false;
        if (AccessController::checkSession()){
            $commentText=SecurityController::sanitizeString($commentText);
            if(SecurityController::isint($activityId)&&SecurityController::isint($toUserId)){
                $daoAct=new DaoActivity();
                $daoUse=new DaoUser();
                $users=array();
                
                //Load the sender
                $user=AccessController::getSessionUser();
                
                //Load the activity
                $activity=$daoAct->read($activityId);
                
                //Associate the sender with the activity
                $daoAct->setActivityUsers($activity,array($user));
                
                //Load related users that commented the activity
                $usersList=$daoAct->usersThatCommentInActivity($activityId);
                foreach ($usersList as $userList){
                    if($userList!=$user->getId()){
                        array_push($users,$daoUse->read("",$userList));
                    }
                }
                //Create an save the comment
                $daoCom=new DaoComment();
                $comment=new Comment(0,$commentText,$user->getId());
                $daoCom->create($comment,$activityId);
                                
                //Create the notification for all the related users
                $notification=new Notification();
                $notification->setType("comment");
                $notification->addParameter("activityId",$activityId);
                $notification->addParameter("commentId",$comment->getId());
                $notification->addParameter("sender",$user->getId());
                self::sendNotification($notification,$users);
                
                $response=$comment;
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Try to pass not integer value");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Delete from database a Comment
     * @param int Comment id
     * @return True if delete the comment, false otherwise
     */
    public static function deleteComment($commentId){
        $response=false;
        if (AccessController::checkSession()){
            if(SecurityController::isint($commentId)){
                $daoCom=new DaoComment();
                $response=$daoCom->deleteById($commentId);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Try to pass not integer value");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Return ALL comments for an Activity or to an User
     * @param int Activity identificator
     * @param int User identificator
     * @return Comment[] Array of Comment Object
     */
    public static function allComments($activityId=0,$toUser=0){
        $response=false;
        $daoCom=new DaoComment();
        if(SecurityController::isint($activityId)&&SecurityController::isint($toUser)){
            $response=$daoCom->listing($activityId,$toUser);
        }
        return $response;
    }
    /**
     * Return the html of a Comment
     * @param Comment Comment of an Activity
     * @return string String with the Comment in HTML Format
     */
    public static function htmlComment($comment){
        $html="";
        $close="";
        $user=AccessController::getSessionUser();
        $commentUser=SocialController::getUser("",$comment->getUser());
        //Only shows close button if is the same user
        if($user){
            if($user->getId()==$commentUser->getId()){
                $close='<a class="btn-delete-comment"></a>';
            }
        }
        $html.=
            '<div class="comment" id="comment'.$comment->getId().'">'.
                $close.
                '<a id="user'.$commentUser->getId().'" class="user" href=""><img src="'.Router::img("users/thumbnails/".$commentUser->getId().".jpg").'"></a>'.
                '<div class="info">'.
                    '<p class="name"><a href="" class="user" id="user'.$commentUser->getId().'">'.$commentUser->name().'</a></p>'.
                    '<p>'.$comment->getComment().'</p>'.
                '</div>'.
            '</div>';
        return $html;
    }
    //**************** LIKES ****************//
    /**
     * Intercambia entre like y unlike de una actividad para el usuario actual
     * @param Activity $activity Objeto de tipo Activity sobre la que se hace like/unlike
     * @return bool True si se pudo hacer el cambio, false en otro caso
     */
    public static function toggleLikeActivity($activity){
        $response=false;
        if (AccessController::checkSession()){
            if(SecurityController::isclass($activity,"Activity")){
                $daoLike=new DaoLike();
                $user=AccessController::getSessionUser();
                $response=$daoLike->toggle("activity",$activity->getId(), $user->getId());
                //Agrega los puntos correspondientes al like
                $dao=new DaoSet();
                //
                $isLike=$daoLike->isLike("activity",$activity->getId(),$user->getId());
                if($isLike){
                    $points=$dao->saveLikePoints($user->getId(),$activity->getId());
                    //Agrega los puntos al badge de Groundswell
                    $daoBad=new DaoBadge();
                    $badge=$daoBad->addPointsToGroundswellBadge($user,$points);
                    //Si retorna un badge obtenido, crea la actividad y la notificación
                    if($badge){
                        self::showNewBadges($user,array($badge));
                    }
                }
                if($activity->getCreator()!=$user->getId()&&$response=="like"){
                    //Crea una notificación para el creador de la actividad si no es propia
                    $notification=new Notification();
                    $notification->setType("like");
                    $notification->addParameter("activityId",$activity->getId());
                    $notification->addParameter("sender",$user->getId());
                    $creator=self::getUser("",$activity->getCreator());
                    self::sendNotification($notification,$creator);
                }
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Try to pass not integer value");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    //**************** SHARES ****************//
    /**
     * Intercambia entre like y unlike de una actividad para el usuario actual
     * @param Activity $activity Objeto de tipo Activity sobre la que se hace like/unlike
     * @return bool True si se pudo hacer el cambio, false en otro caso
     */
    public static function shareActivity($activity){
        $response=false;
        if (AccessController::checkSession()){
            if(SecurityController::isclass($activity,"Activity")){
                $user=AccessController::getSessionUser();
                $dao=new DaoSet();
                $points=$dao->saveSharePoints($user->getId(),$activity->getId());
                //Agrega los puntos al badge de Groundswell
                $daoBad=new DaoBadge();
                $badge=$daoBad->addPointsToGroundswellBadge($user,$points);
                //Si retorna un badge obtenido, crea la actividad y la notificación
                if($badge){
                    self::showNewBadges($user,array($badge));
                }
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Try to pass not integer value");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Recibe un array de badges y crea una actividad y una notificación para cada uno
     * @param User $user Usuario de los badges
     * @param Badge[] $badges Lista de badges a mostar
     */
    public static function showNewBadges($user,$badges){
        if (AccessController::checkSession()){
            if(is_array($badges)&&count($badges)>0){
                foreach ($badges as $badge) {
                    if(SecurityController::isclass($badge,"Badge")){
                        //Crear la actividad
                        $activity=ActivityController::activityFromBadge($user,$badge);
                        //Crear la notificación
                        $notification=new Notification();
                        $notification->setType("badge");
                        $notification->addParameter("user",$user->getId());
                        $notification->addParameter("activityId",$activity->getId());
                        $notification->addParameter("badgeId",$badge->getId());
                        $notification->setRecipient($user->getId());
                        SocialController::sendNotification($notification,array($user));
                    }
                }
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
    }
    
    /**
     * Return the time ago
     * Taked from: http://stackoverflow.com/questions/12082987/show-x-time-ago-if-time-is-less-than-24-hour-ago
     * @param date Date from
     * @param date Date to
     * @return string Time Ago
     * @todo improve this function
     */
    public static function timeAgo($time=false,$just_now=false){
        $output="";
        $adjective="";
        if ($time instanceOf DateTime)
            $time = $time->getTimestamp();
        elseif (is_numeric($time))
            $time = date('m/d/y h:i A', $time);
        if (strtotime($time) === false)
            $time = date('m/d/y h:i A', time());
        $interval = date_create($time)->diff(date_create('now'));
                
        if(strtotime($time)>time()){
            $adjective='from now';
        }else{
            $adjective='ago';
        }
                
        if($interval->m>12){
            $output=$time;
        }elseif($interval->m>1&&$interval->m<=12){
            $output=$interval->days.' months ago';
        }elseif($interval->m==1){
            $output=$interval->m.' month ago';
        }elseif($interval->days>1&&$interval->days<=30){
            $output=$interval->days.' days ago';
        }elseif($interval->days==1){
            $output=$interval->days.' day ago';
        }else{
            if($interval->h < 1 && $interval->i < 1 && $just_now){
                $output='just now';
            }else{
                if($interval->h > 1){
                    $output=$interval->h.' hour';
                    if($interval->h>1){
                        $output.='s';
                    }else{
                        $output.='';
                    }
                    $output.=' ago';
                }else{
                    $output=$interval->i.' minutes'.' '.$adjective;
                }
            }
        }
        return $output;
    }
    
    //**************** NOTIFICATIONS ****************//
    
    /**
     * Return the list of non-readed Notifications for the User
     * @param int User id to get notifications
     * @return Notification[] Notification list for the user
     */
    public static function getNotifications($userId){
        $response=false;
        if (AccessController::checkSession()){
            $response=true;
            $dao=new DaoNotification();
            if(SecurityController::isint($userId)){
                $response=$dao->listing($userId);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."User id is not an integer [".gettype($userId)."]: ".$userId);
                $response=false;
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Return the number of non-readed Notifications for the User
     * @param int User id to get notifications
     * @return Notification[] Notification list for the user
     */
    public static function nonReadedNotifications($userId){
        $response=false;
        if (AccessController::checkSession()){
            $response=true;
            $dao=new DaoNotification();
            if(SecurityController::isint($userId)){
                $response=$dao->countNotifications($userId);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."User id is not an integer [".gettype($userId)."]: ".$userId);
                $response=false;
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Send a Notification to a list of Users
     * @param Notification Notification Object with type ["frienship"|"comment"|"reprnt"|"response"]
     * @param User[] User array, could be only one user: array($user)
     * @return mixed Value with the request result
     */
    public static function sendNotification($notification,$userList){
        $response=false;
        if (AccessController::checkSession()){
            $response=true;
            $dao=new DaoNotification();
            if(!is_array($userList)){
                $userList=array($userList);
            }
            foreach ($userList as $user) {
                $notification->setRecipient($user->getId());
                $response=$response&&$dao->create($notification);
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Evita que una notificación se vuelva a mostrar
     * @param int Notification id
     * @return mixed Value with the request result
     */
    public static function deleteNotification($idNotification){
        $response=false;
        if (AccessController::checkSession()){
            $response=true;
            $dao=new DaoNotification();
            if(SecurityController::isint($idNotification)){
                $response=$response&&$dao->markDelete($idNotification);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Notification id is not an integer");
                $response=false;
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Mark a Notification as read
     * @param int Notification id
     * @return mixed Value with the request result
     */
    public static function readNotification($idNotification){
        $response=false;
        if (AccessController::checkSession()){
            $response=true;
            $dao=new DaoNotification();
            if(SecurityController::isint($idNotification)){
                $response=$response&&$dao->markRead($idNotification);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Notification id is not an integer");
                $response=false;
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Mark all Notifications as read for the current user
     * @return mixed Value with the request result
     */
    public static function readAllNotifications(){
        $response=false;
        if (AccessController::checkSession()){
            $response=true;
            $dao=new DaoNotification();
            $user=AccessController::getSessionUser();
            $response=$response&&$dao->markAllRead($user);
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Mark a Notification as unread
     * @param int Notification id
     * @return mixed Value with the request result
     */
    public static function unreadNotification($idNotification){
        $response=false;
        if (AccessController::checkSession()){
            $response=true;
            $dao=new DaoNotification();
            if(SecurityController::isint($idNotification)){
                $response=$response&&$dao->markUnred($idNotification);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Notification id is not an integer");
                $response=false;
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    
    /**
     * Return the html for a list of Notification
     * @param Notification[] Notification list
     * @return string Notification in HTML
     */
    public static function htmlNotifications($notifications){
        $html='';
        foreach ($notifications as $notification) {
            switch ($notification->getType()) {
                case "friendship":
                    $html.=self::htmlNotificationFrienship($notification);
                    break;
                case "comment":
                    $html.=self::htmlNotificationComment($notification);
                    break;
                case "like":
                    $html.=self::htmlNotificationLike($notification);
                    break;
                case "reprnt":
                    $html.=self::htmlNotificationReprnt($notification);
                    break;
                case "badge":
                    $html.=self::htmlNotificationBadge($notification);
                    break;
                case "followFolio":
                    $html.=self::htmlNotificationFollowFolio($notification);
                    break;
                default:
                    break;
            }
        }
        return $html;
    }    
    /**
     * Return a notification of frienship in html format
     * @param Notification Notification object
     * @return string Notification in hrml format
     */
    private static function htmlNotificationFrienship($notification){
        $user=self::getUser("",$notification->getParameter("sender"));
        $html='<li>';
            $html.='<div class="notification" id="notification'.$notification->getId().'" notifType="'.$notification->getType().'">';
                if($notification->getParameter("type")=="request"||$notification->getParameter("type")=="resend"){
                    $html.='<p class="paragrahp">'.
                            '<a href="" class="user" id="user'.$user->getId().'">'.$user->name().'</a>'.
                            ' wants to be your friend'.
                        '</p>';
                    $html.='<div class="deleteNotification" title="hide notification"></div>';
                    $html.='<div href="" class="notification-button" id="accept">Accept</div>';
                    $html.='<div href="" class="notification-button" id="ignore">Ignore</div>';
                }elseif($notification->getParameter("type")=="received"){
                    $html.='<p class="paragrahp">'.
                            '<a href="" class="user" id="user'.$user->getId().'">'.$user->name().'</a>'.
                            ' has accepted your friend request'.
                        '</p>';
                    $html.='<div class="deleteNotification" title="hide notification"></div>';
                }
            $html.='</div>';
        $html.='</li>';
        return $html;
    }
    /**
     * Return a notification of comment in html format
     * @param Notification Notification object
     * @return string Notification in hrml format
     */
    private static function htmlNotificationComment($notification){
        $daoAct=new DaoActivity();
        $user=self::getUser("",$notification->getParameter("sender"));
        $activity=$daoAct->read($notification->getParameter("activityId"));
        $html='<li>';
            $html.='<div class="notification" id="notification'.$notification->getId().'" notifType="'.$notification->getType().'">';
                $html.='<p class="paragrahp">';
                    if($activity->getCreator()==$notification->getRecipient()){
                        $html.='<a href="" class="user" id="user'.$user->getId().'">'.$user->name().'</a> commented on your <a class="commentActivity" id="commentActivity'.$activity->getId().'" href="#activitiesViewer">activity</a>.';
                    }else{
                        $html.='<a href="" class="user" id="user'.$user->getId().'">'.$user->name().'</a> commented in an <a class="commentActivity" id="commentActivity'.$activity->getId().'" href="#activitiesViewer">activity</a> in which you commented.';
                    }
                $html.='</p>';
                $html.='<div class="deleteNotification" title="hide notification"></div>';
            $html.='</div>';
        $html.='</li>';
        return $html;
    }
    /**
     * Return a notification of like in html format
     * @param Notification Notification object
     * @return string Notification in hrml format
     */
    private static function htmlNotificationLike($notification){
        $daoAct=new DaoActivity();
        $user=self::getUser("",$notification->getParameter("sender"));
        $activity=$daoAct->read($notification->getParameter("activityId"));
        $html='<li>';
            $html.='<div class="notification" id="notification'.$notification->getId().'" notifType="'.$notification->getType().'">';
                $html.='<p class="paragrahp">';
                    $html.='<a href="" class="user" id="user'.$user->getId().'">'.$user->name().'</a> likes your <a class="commentActivity" id="commentActivity'.$activity->getId().'" href="#activitiesViewer">activity</a>.';
                $html.='</p>';
                $html.='<div class="deleteNotification" title="hide notification"></div>';
            $html.='</div>';
        $html.='</li>';
        return $html;
    }
    /**
     * Return a notification of reprnt in html format
     * @param Notification Notification object
     * @return string Notification in hrml format
     */
    private static function htmlNotificationReprnt($notification){
        $daoAct=new DaoActivity();
        $user=self::getUser("",intval($notification->getParameter("user")));
        $footpoints=intval($notification->getParameter("points"));
        $activity=$daoAct->read($notification->getParameter("activityId"));
        $html='<li>';
            $html.='<div class="notification" id="notification'.$notification->getId().'" notifType="'.$notification->getType().'">';
                $html.='<p class="paragrahp">';
                    $html.='<a href="" class="user" id="user'.$user->getId().'">'.$user->name().'</a> gave to your <a class="commentActivity" id="commentActivity'.$activity->getId().'" href="#activitiesViewer">combination</a>. You won '.$footpoints.' points.';
                $html.='</p>';
                $html.='<div class="deleteNotification" title="hide notification"></div>';
            $html.='</div>';
        $html.='</li>';
        return $html;
    }
    /**
     * Return a notification of badge in html format
     * @param Notification Notification object
     * @return string Notification in hrml format
     */
    private static function htmlNotificationBadge($notification){
        $daoAct=new DaoActivity();
        $daoBad=new DaoBadge();
        $badge=$daoBad->read($notification->getParameter("badgeId"));
        $activity=$daoAct->read($notification->getParameter("activityId"));
        $html='<li>';
            $html.='<div class="notification" id="notification'.$notification->getId().'" notifType="'.$notification->getType().'">';
                $html.='<p class="paragrahp">';
                    $html.='You unlocked the <a class="commentActivity" id="commentActivity'.$activity->getId().'" href="#activitiesViewer">'.$badge->getName().' badge</a>.';
                $html.='</p>';
                $html.='<div class="deleteNotification" title="hide notification"></div>';
            $html.='</div>';
        $html.='</li>';
        return $html;
    }
    /**
     * Return a notification of following a folio in html format
     * @param Notification Notification object
     * @return string Notification in hrml format
     */
    private static function htmlNotificationFollowFolio($notification){
        $daoFol=new DaoFolio();
        $user=self::getUser("",$notification->getParameter("sender"));
        $folio=$daoFol->read($notification->getParameter("folioId"));
        $html='<li>';
            $html.='<div class="notification" id="notification'.$notification->getId().'" notifType="'.$notification->getType().'">';
                $html.='<p class="paragrahp">';
                    $html.='<a href="" class="user" id="user'.$user->getId().'">'.$user->name().'</a> is now following your folio: '.$folio->getName();
                $html.='</p>';
                $html.='<div class="deleteNotification" title="hide notification"></div>';
            $html.='</div>';
        $html.='</li>';
        return $html;
    }
    /**
     * Retorna una lista de usuarios en HTML para el search
     * @param User[] User lista de usuarios
     * @return string lista en HTML
     */
    public static function htmlUsersSearch($users){
        $response=false;
        if (AccessController::checkSession()){
            $response="";
            foreach ($users as $user){
                $response.=self::htmlUserSearch($user);
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    
    /**
     * Retorna un usuario en formato HTML para el cuadro search
     * @param User User object
     * @return string User del seacrh en html
     */
    private static function htmlUserSearch($user){
        $friend='Add friend';
        $showName=$user->name();
        //Set the name or email
        if($showName==""){
            $showName=$user->getEmail();
        }
        if($user->getId()==0){ //Cuando el usuario no existe en el sistema, invitarlo con email
            $friend='Invite';
        }else if($user->getIsFriend()==1){
            $friend='Friends';
        }else if($user->getIsFriend()=="sent"){
            $friend='Accept';
        }else if($user->getIsFriend()=="received"){
            $friend='Resend';
        }
        $html='<div id="user'.$user->getId().'" class="user">'.
                '<img src="'.Router::img("users/thumbnails/".$user->getId().".jpg").'">'.
                '<div class="name">'.$showName.'</div>'.
                '<div class="friend" state="'.$friend.'">'.$friend.'</div>'.
            '</div>';
        return $html;
    }
    
    //**************** BONDEEDS ****************//
    
    /**
     * Crea un bondeed a partir de los datos proporcionados
     */
    public static function makeBondeed($content,$user,$tags,$mediaName,$folioId){
        $response=false;
        if(AccessController::checkSession()){
            $content=SecurityController::sanitizeString($content);
            $mediaName=SecurityController::sanitizeString($mediaName);
            if(SecurityController::isclass($user,"User")){
                $daoDeed=new DaoDeed();
                $daoSet=new DaoSet();
                //Save the Good deed in database and return it with the id
                $deed=new Deed();
                $deed->setDate(date('Y-m-d H:i:s'));
                $deed->setContent($content);
                $deed->setUser($user->getId());
                $deed->setTags($tags);
                $daoDeed->create($deed);

                //Move the temporal image
                if(trim($mediaName)!="false"){
                    Router::copyFile("temp/".$mediaName,'deeds/'.$deed->getId().'.jpg');
                    Router::deleteFile("temp/".$mediaName);
                }
                //Create the Activity for the Good Deed
                $activity=ActivityController::activityFromDeed($deed);
                //Suma los puntos por bondeed
                $daoSet->saveDeedPoints($user->getId(),$deed->getId());
                if($folioId){
                    $folio=FolioController::readFolio($folioId);
                    FolioController::addActivityToFolio($folio,array($activity->getId()));
                    //Se hace seguidor del folio automáticamente si no es seguidor
                    FolioController::follow($folio);
                    //Se crean los puntos para el folio
                    FolioController::setFolioPoints($user->getId(),$folio,"bondeed",0,$deed->getId(),true);
                }
                $response=$activity;
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Invalid data parameters");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    
    //**************** STORIES ****************//
    /**
     * Return the last story for the user
     * @param Story Story user
     */
    public static function lastStory($userId){
        $response=false;
        if (AccessController::checkSession()){
            $daoSto=new DaoStory();
            $response=$daoSto->lastStory($userId);
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Save an story in database
     * @param string Content of the story
     */
    public static function makeStory($title,$url,$description,$content,$image=null,$folioId=0){
        $response=false;
        if (AccessController::checkSession()){
            $daoSto=new DaoStory();
            $daoSet=new DaoSet();
            $user=AccessController::getSessionUser();
            $story=new Story();
            $story->setDate(date('Y-m-d H:i:s'));
            $story->setTitle($title);
            $story->setUrl($url);
            $story->setDescription($description);
            $story->setContent($content);
            $story->setUser($user->getId());
            if($image){
                $story->setWithImage(true);
            }
            if($daoSto->create($story)){
                $response=$story;
                //Crea la actividad de la historia
                $activity=ActivityController::activityFromStory($story);
                //Suma los puntos por stories
                $daoSet->saveStoryPoints($user->getId(),$story->getId());
                //Si se crea desde un folio
                if($folioId){
                    $folio=FolioController::readFolio($folioId);
                    FolioController::addActivityToFolio($folio,array($activity->getId()));
                    //Se hace seguidor del folio automáticamente si no es seguidor
                    FolioController::follow($folio);
                    //Se crean los puntos para el folio
                    FolioController::setFolioPoints($user->getId(),$folio,"story",0,$story->getId(),true);
                }
                if($image){
                    //Se copia la imagen al directorio temporal y se retorna el nombre
                    $imageTemp=MediaController::downloadImage($image,"");
                    if($imageTemp){
                        Router::saveFile($imageTemp,"stories/".$story->getId().".jpg");
                    }else{
                        error_log("[".__FILE__.":".__LINE__."]"."File type not allowed");
                    }
                }
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Can't create the story");
            }
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."The user session does not exist");
        }
        return $response;
    }
    /**
     * Return URL from the first image in a web page
     * @param string Link to search
     * @return string URL of the image
     * @todo Implement!!!
     */
    public static function firstImage($link){
//        $html=file_get_contents($link);        
//        $array = array();
//        preg_match( '~<img[^>]*src\s?=\s?[\'"]([^\'"]*)~i',$html,$array);
    }
    
    
    /**
     * Return URL from the first image in a web page
     * @param string Link to search
     * @return string URL of the image
     * @todo Implement!!!
     */
    public static function contactUs($message,$email=""){
        //If the session is active send from the registered user, else, read the email
        if (AccessController::checkSession()){
            $user=AccessController::getSessionUser();
            $email=$user->getEmail();
        }
        if(SecurityController::isemail($email)){
            $daoCont=new DaoContactUs();
            $message=SecurityController::sanitizeString($message);
            $daoCont->create($email,$message);
        }else{
            error_log("[".__FILE__.":".__LINE__."]"."Invalid email");
        }
    }
    
    //**************** STORIES ****************//
    /**
     * Almacena una invitación en la base de datos
     * @param User Objeto de tipo usuario, la persona que invita
     * @param string email de la persona a la que se quiere enviar
     * @return Invitation invitación con los datos, incluída la $key
     */
    public static function storeInvitation($inviter,$email){
        $response=false;
        //If the session is active send from the registered user, else, read the email
        if (AccessController::checkSession()){
            if(SecurityController::isemail($email)){
                $daoInv=new DaoInvitation();
                $crypt=new Crypt();
                $invitation=new Invitation(0,$email);
                $invitation->setKey($crypt->encryptString(mt_rand(10000,99999).time().$email));
                $invitation->setInviter($inviter->getId());
                $response=$daoInv->create($invitation);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Invalid email");
            }
        }
        return $response;
    }
    /**
     * Verifica y actualiza los datos de una invitación a un amigo
     *  - Comprueba si la clave de la invitación es correcta
     *  - Marca las otras invitaciones al mismo correo como inactivas
     *  - Asigna los puntos por invitaciones al $inviter
     *  - Marca como amigos a quien invitó y al invitado
     *  - Envia las notificaciones correspondientes
     * @param string $email Email del usuario que recibió la invitación
     * @param string $key Clave enviada al email
     * @return bool True si la clave corresponde con el correo
     */
    public static function verifyInvitation($email,$key){
        $response=false;
        $daoInv=new DaoInvitation();
        $daoSet=new DaoSet();
        $email=SecurityController::sanitizeString($email);
        $key=SecurityController::sanitizeString($key);
        $invitation=$daoInv->verifyValidationKey($email,$key);
        if($invitation){
            //Si la invitación fue almacenada
            if($daoInv->acceptInvitation($invitation)){
                //Guarda los puntos de invitación
                $daoSet->saveInvitePoints($invitation);
                //Crea la amistad y las notificaciones
                $inviter=self::getUser("",$invitation->getInviter());
                $guest=self::getUser($email);
                self::makeFriendsByInvitation($inviter,$guest);
                $response=true;
            }
        }
        return $response;
    }
    
    //**************** SUGGESTIONS ****************//
    public static function loadSuggestions($page){
        $response=false;
        //If the session is active send from the registered user, else, read the email
        if (AccessController::checkSession()){
            $daoSug=new DaoSuggestion();
            $page=SecurityController::sanitizeString($page);
            $user=AccessController::getSessionUser();
            $response=$daoSug->loadSuggestions($page,$user);
        }
        return $response;
    }
    public static function readSuggestion($suggestionId){
        $response=false;
        //If the session is active send from the registered user, else, read the email
        if(AccessController::checkSession()){
            if(SecurityController::isint($suggestionId)){
                $user=AccessController::getSessionUser();
                $daoSug=new DaoSuggestion();
                $response=$daoSug->markRead($suggestionId,$user);
            }else{
                error_log("[".__FILE__.":".__LINE__."]"."Invalid suggestion id");
            }
        }
        return $response;
    }
    
    //**************** PIONEERS ****************//
    private static function startPioneers(){
        $response=false;
        if(AccessController::checkSession()){
            $dao=new DaoUser();
            $pioneers=$dao->listOfPioneers();
            foreach ($pioneers as $pioneer) {
//                CommunicationController::sendPreregisteredThanks($pioneer);
//                CommunicationController::sendPreregisteredLaunch($pioneer);
//                
//                //Agrega los puntos al badge de Pioneer
//                $daoBad=new DaoBadge();
//                $badge=$daoBad->addPointsToPioneerBadge($pioneer,300);
//                //Si retorna un badge obtenido, crea la actividad y la notificación
//                if($badge){
//                    self::showNewBadges($pioneer,array($badge));
//                }
            }
        }
        return $response;
    }
}
?>
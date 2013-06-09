<?php
/**
 * jxDaemons ajax File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
session_start();
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'ActivityController.php';
include_once Router::rel('controllers').'SocialController.php';
$user=AccessController::getSessionUser();
if($user){
    $daemons=json_decode($_POST['daemons']);
    $response=null;
    foreach ($daemons as $daemon){
        $data='';
        switch ($daemon->name) {
            case 'notifications':
                $data.='"data":'.SocialController::nonReadedNotifications($user->getId());
                break;
            case 'activities':
                $activitiesData='"data":{';
                if($daemon->params){
                    //Load the last Activities
                    $byRefMin=0;$byRefMax=0;
                    $list=ActivityController::userLoadActivities(
                            $user,
                            intval($daemon->params->refActivity),
                            Config::$activitiesLoadScroll,
                            1,
                            SecurityController::sanitizeString($daemon->params->filter),
                            $daemon->params->tagsFilter,
                            $byRefMin,
                            $byRefMax,
                            intval($daemon->params->folio),
                            intval($daemon->params->profileId)
                        );
                    if(count($list)>0){
                        $htmlActivities=str_replace("'","´",ActivityController::htmlActivities($list));
                        $htmlActivities=filter_var($htmlActivities,FILTER_SANITIZE_MAGIC_QUOTES);
                        $htmlActivities=str_replace("\n","<br/>",$htmlActivities);
                        $newActivities='"activities":"'.$htmlActivities.'","min":'.$byRefMin.',"max":'.$byRefMax.',';
                    }else{
                        $newActivities='"activities":"",';
                    }
                    $activitiesData.=$newActivities;
                    //Add the Activities with new comments
                    $withComments='"withComments":[';
                    $sendingComments=false;
                    foreach ($daemon->params->list as $listActivity){
                        $total=ActivityController::totalComments($listActivity->activity);
                        if(!$listActivity->comments){
                            $listActivity->comments=0;
                        }
                        if($listActivity->comments!=$total){
                            $withComments.=$listActivity->activity.",";
                            $sendingComments=true;
                        }
                    }
                    if($sendingComments){
                        //Remove the last comma
                        $withComments=substr($withComments,0,-1);
                    }
                    $withComments.="]";
                    $activitiesData.=$withComments;
                }
                $activitiesData.='}';
                $data.=$activitiesData;
                break;
            default:
                $data.='"data":0';
                break;
        }
        $response.='{"name":"'.$daemon->name.'",'.$data.'},';
    }
    //Remove the last comma
    $response=substr($response,0,-1);
}else{
    $response="";
}
echo '{"daemons":['.$response.']}';
?>
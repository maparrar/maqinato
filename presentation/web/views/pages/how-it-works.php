<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato :: How it Works</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            /**
            * Privacy page file
             * @author https://github.com/maparrar/maqinato
             * @package views
             * @subpackage pages
            */
            session_start();
            if(!class_exists('Router')) require_once '../../config/Router.php';
            include_once Router::rel('controllers').'AccessController.php';
                        
            //If session wasn't started go to landing page and destroy the session
            $user=false;
            if (!AccessController::checkSession()){
                AccessController::destroy();
            }else{
                //Load the logged User
                $user=AccessController::getSessionUser();
                //Redirige a validar, si la cuenta no se ha validado
                if(!AccessController::validatedAccount($user)){
                    header("Location: ".Router::rel('transactions')."validateAccount.php");
                }
            }
            
            //INCLUDE CSS SCRIPTS
            Router::css("reset", "jqueryui", "skeleton","layout","structure","pages");
            //INCLUDE JS SCRIPTS
            //Basic
            Router::js("jquery","jqueryui","modernizr","system","tools");
            //Components
            Router::js("security","ajaxCore","ajaxSocial","access","daemons","notifications");
            
            //Write the main configuration variables in HTML to be readed from JS
            Router::configInHtml();
        ?>        
        <script type="text/javascript">
            $(document).ready(function(){
                window.system=new System();
                system.init({
                    access:true,
                    notifications:true,
                    session:true
                });
            });
        </script>
    </head>
    <body id="howPage">
        <?php
            //Load the user id
            if($user){
                echo '<input type="hidden" id="userId" value="user'.$user->getId().'" />';
            }
        ?>
        <!-- INCLUDE HEADER   -->
        <?php include Router::rel('web').'templates/header.php'; ?>
        <section id="page">
            <div class="wrapper">
                <aside>
                    <h1>How it Works</h1>
                </aside>			
                <div class="page-content">
                    <p class="textHighlighted">Give to the causes you care about. Share your impact.</p>
                    <p><b>maqinato is an interactive social giving platform that empowers individuals to make a difference through everyday actions and donations, and allows them to share their contributions with a community of like-minded individuals. Ours is platform based on causes… tapping into what individuals are passionate about, giving them the ability to share and inspire others.</b></p>
                    <p>To start giving, explore the Impact Areas: <span class="area_text_1"><b>Protect the Planet</b></span>, <span class="area_text_2"><b>Power to the People</b></span>, <span class="area_text_3"><b>Learn, Grow, Create</b></span>, <span class="area_text_4"><b>Stay Happy, Be Healthy</b></span>, and <span class="area_text_5"><b>Speak Your Mind</b></span>. <b>These align with causes supported by nonprofits.</b> Select the causes that are important to you by dragging and dropping the tags into the circle to create your personal cause mix. The mix can be adjusted to give priority to certain causes. You can also search for any word that interests you and find related tags or specific nonprofits. </p>
                    <p>When the desired cause mix is achieved, click on the Give Now button to see your impact and the nonprofits that maqinato has selected to receive your donations. Click on refresh to see other nonprofit combinations. Click on Submit to make your donations. maqinato will make the donations on your behalf and you will receive the documentation verifying that the selected nonprofits have received your gift. The maqinato community will see your action and you can share your giving with your social network as well.</p>
                    <p>maqinato selects the nonprofits based on a large data base of registered 501c3 nonprofits. <b>Our algorithms search these nonprofits and select the organizations that best fit with your cause mix.  We track variables like the ONU millennium and RIO +20 goals, as well as a media search.</b> maqinato analyzes the cause mix you choose and connects you to corresponding nonprofits.</p>
                    <p>Points are achieved depending on several variables. If you select tags that are related to and complement each other, you can achieve extra points. If the causes you pick are trending at the time and more help is needed in those areas, you will also achieve more points. You can increase your points by increasing the amount you give. You will also be achieving points for almost any activity inside maqinato. For example, if you share something, invite friends to join, or are a frequent giver you will achieve more points. With all these points you can achieve Badges related to the causes you like and share them within maqinato or other social networks.</p>
                    <p><b>Impact areas</b></p>
                    <p><span class="area_text_1"><b>Protect the Planet</b></span>: Preserving biodiversity, reducing pollution, and protecting our natural resources are all vital things that our planet needs to continue to provide us with clean air, water and the perfect amount of sunshine. Unfortunately humans have been slowly destroying our planet. Luckily there are many things you can to do help.</p>
                    <p><span class="area_text_2"><b>Power to the People</b></span>: Societies are stronger when we support each other and aspire to live together in peace. Unfortunately the world is not fair and bad things happen to good people. We need to look after each other and offer the social services and charity to those in need.</p>
                    <p><span class="area_text_3"><b>Learn, Grow, Create</b></span>: Education is the source of all human development in our society. The more we know, the more creative we are, the better we can innovate, the smarter we can grow and the richer all our lives can become.</p>
                    <p><span class="area_text_4"><b>Stay Healthy, Be Happy</b></span>: We all want to live long, healthy, happy lives. Health and spirituality are closely linked to our ability to be happy. But, sometimes our health issues are out of our control. That doesn’t have to mean we still can’t be happy. All you have to do is find the joy in life and embrace it!</p>
                    <p><span class="area_text_5"><b>Speak Your Mind</b></span>: Some issues receive less attention and need a bit of support. Advocacy is also an important part of making a positive impact. Encourage users to speak out and let us know what ‘pet’ issues they care about most.</p>
                    <p><span class="giving_text"><b>Start Giving</b></span>: Explore the Impact Areas and select the causes you care about. Adjust the Cause Mix and select your donation amount. maqinato selects the nonprofits to best match your mix based on a large data base of registered 501c3 nonprofits. We use algorithms to track variables including the UN Needs Assessment by Country, News Media
                    International Goals (G20, Rio +20) and Ranking Metrics.</p>
                    <p><b>Folios</b>: By creating a Folio you can save and share a particular Cause Mix. Folios enable you to donate to the same tags again and share it with others to gain more support. You can upload stories, links, photos, videos, and track the progress of the Folio over time. When you create or follow a folio you will recieve points for every donation and activity, distributed among the followers.</p>
                    <p><b>Badges</b>: Achieve badges for being active on the site! Giving to a particular Impact Areas or nonprofits will unlock badges, as will making frequent donations and having lots of friends.
                    <p><b>Search Feature</b>: Search for your interests or favorite nonprofits and drag & drop the results to create your Cause Mix.
                    <p><b>BonDeed</b>: Share the good deeds you do every day and inspire others! You can upload a photo, describe what you did, and choose the tags that are associated with your BonDeed.                    
                </div>
            </div>
        </section>
        
        <!-- INCLUDE FOOTER   -->
        <?php include Router::rel('web').'templates/footer.php'; ?>
    </body>
</html>
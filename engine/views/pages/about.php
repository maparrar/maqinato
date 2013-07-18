<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>maqinato :: About</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            /**
            * About page file
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
    <body id="aboutPage">
        <!-- INCLUDE HEADER   -->
        <?php include Router::rel('web').'templates/header.php'; ?>
        <section id="page">
            <div class="wrapper">
                <aside>
                    <h1>About</h1>
                </aside>			
                <div class="page-content">
                    <p><span class="textHighlighted">maqinato is a social online platform for you to support & discover causes you care about.</span> We make it easy for you to get your friends involved in the process, saving time and amplifying impact. We make giving fun!</p> 

                    <p>maqinato aims to create additional donation channels for nonprofits by increasing knowledge and engagement and adding a social experience for giving.  Select impact areas that are important to you and create a cause mix based on what you are passionate about. maqinato generates a portfolio of nonprofits that best align with the chosen causes. Actions and impacts are shared within the maqinato network as well as through other social networks, building and growing the positive effects of each action.</p>
                    <p>Give to the causes you care about. Share your impact.</p>
                    <br>
                    <p class="textTitle textBold">Who we are</p>
                    <p>The company was founded by two Colombian entrepreneurs who believe that it is possible to gain profit while having a positive impact and on the world by generating resources for nonprofits. They created the idea for this platform over an inspirational cup of Colombian coffee in 2011 and have joined with friends, family, and their professional network to create maqinato, based in San Francisco.</p>
                    <br>
<!--                    <p class="textTitle textBold">The Team</p>
                    <div id="team">
                        <p class="textHighlighted textBold">Salomón Stroh (co-founder)</p>
                        Founder and board member of several companies with experience in management, corporate strategy, human resources, marketing and sales. MBA in finance and a bachelor’s degree in electronic engineering. 
                        <br><br>
                        <p class="textHighlighted textBold">Juan Carlos Rebolledo (co-founder)</p>
                        Experience in with technology-based companies and as manager and founder. Bachelor’s degree in electronic engineering, with a minor in business administration. 
                        <br><br>
                        <p class="textHighlighted textBold">Luis Miguel Chaves (partner)</p>
                        Bachelor in Industrial Engineering, specialist in Marketing with 5-year experience in management and entrepreneurship in Energy and Food industries.
                        <br><br>
                        <p class="textHighlighted textBold">Krystal Kavney (Partnership & Content Manager)</p>
                        Background in business development, strategy, and design. MBA in sustainable management, with an emphasis on business and nonprofit partnerships and bachelors degree in design.
                        <br><br>
                        <p class="textHighlighted textBold">Miguel Alejandro Parra (Back-End Developer)</p>
                        Expertise in Web based Software Development, user experience and Geographic Information Systems. Master’s degree in Computer Science.
                        <br><br>
                        <p class="textHighlighted textBold">Juan Pablo Guarín (Front-End Developer)</p>
                        Expertise in graphic design with experience in front end development.
                        <br><br>
                        <p class="textHighlighted textBold">Laura Moreno (Designer)</p>
                        Experience in design, branding, and image. Master’s degree in exhibition management and design. 
                        <br><br>
                        <p class="textHighlighted textBold">Daniella Alverez (Social Media Expert)</p>
                        Currently pursuing her Masters in Management and Public Relations. Bachelor’s degree in Spanish.
                    </div>-->
                </div>
            </div>
        </section>
        <!-- INCLUDE FOOTER   -->
        <?php include Router::rel('web').'templates/footer.php'; ?>
    </body>
</html>
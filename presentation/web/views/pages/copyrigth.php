<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>maqinato :: Copyrigth</title>
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
    <body id="copyrigthPage">
        <!-- INCLUDE HEADER   -->
        <?php include Router::rel('web').'templates/header.php'; ?>
        <section id="page">
            <div class="wrapper">
                <aside>
                    <h1>Copyrigth</h1>
                </aside>
                </aside>			
                <div class="page-content">
                <p align="center"><strong>maqinato, inc.</strong><br />
                  <strong>Copyright and DMCA Policy</strong><strong> </strong><br />
                  Date: April 18, 2012<br />
                  The U.S. Digital Millennium Copyright Act (&ldquo;<u>DMCA</u>&rdquo;) provides recourse to copyright owners who believe that their rights under the United States Copyright Act have been infringed by acts of third parties over the Internet.  <br />
                  If you believe that any content uploaded, posted or otherwise transmitted through the online services (collectively, the &ldquo;<u>Service</u>&rdquo;) offered by maqinato, inc. (&ldquo;<u>maqinato</u>&rdquo;) infringes upon any copyright which you own or control, you may send a written notification to maqinato&rsquo;s Designated DMCA Agent in accordance with the process set forth below. <br /></p>
                  <strong>1.  Designated DMCA Agent.</strong><br />
                  maqinato&rsquo;s designated DMCA agent is SALOMON STROH  (the &ldquo;<u>Designated DMCA Agent</u>&rdquo;), and our Designated DMCA Agent&rsquo;s contact information is as follows:  <br />
                  maqinato, inc.<br />
                  Attn: <br />
                  SALOMON STROH<br />
                  Designated DMCA Agent
                <p>Phone: 1-415-800-4376<br />
                  Email: <a href="mailto:privacy@https://github.com/maparrar/maqinato">privacy@https://github.com/maparrar/maqinato</a> <br />
                  <strong>2.  Notification of Alleged Copyright Infringement.</strong><br />
                  If you believe that your own copyrighted work is accessible through the Service in violation of your copyright, you may provide our Designated DMCA Agent with a written communication as set forth in Section 512(c)(3) of the DMCA that contains substantially the following information:</p>
                <ol>
                  <li>Identify in sufficient detail the copyrighted work or intellectual property that you claim has been infringed so that we can locate the material.  If multiple copyrighted works at a single online site are covered by your notification, you may provide a representative list of such works at that site. </li>
                  <li>Identify the URL or other specific location on the Service that contains the material that you claim infringes your copyright described in Item (a) above. You must provide us with reasonably sufficient information to enable us to locate the alleged infringing material. </li>
                  <li>Provide the electronic or physical signature of the owner of the copyright or a person authorized to act on the owner&rsquo;s behalf. </li>
                  <li>Include a statement by you that you have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law. </li>
                  <li>Include a statement by you that the information contained in your notice is accurate and that you attest under penalty of perjury that you are the copyright owner or that you are authorized to act on the copyright owner&rsquo;s behalf. </li>
                  <li>Include your name, mailing address, telephone number and email address. </li>
                </ol>
                <p>You may submit any notification of alleged copyright infringement to our Designated DMCA Agent by fax, mail, or email at the contact information noted above.<br />
                  Please note that you may be liable for damages, including court costs and attorneys fees, if you materially misrepresent that content on the Service is copyright infringing. <br />
                  Upon receiving a proper notification of alleged copyright infringement as described in this Section 2, we</p>
                 <strong>3.  Counter-Notification.</strong> <br />
                  If you believe your own copyrighted material has been removed from the Service as a result of mistake or misidentification, you may submit a written counter-notification letter to our Designated DMCA Agent pursuant to Sections 512(g)(2) and (3) of the DMCA.  To be an effective counter notification under the DMCA, your letter must include substantially the following:</p>
                <ol>
                  <li>Identification of the material that has been removed or disabled and the location at which the material appeared before it was removed or disabled. </li>
                  <li>A statement that you consent to the jurisdiction of the Federal District Court in which your address is located, or if your address is outside the United States, for any judicial district in which the service provider may be found. </li>
                  <li>A statement that you will accept service of process from the party that filed the notification of alleged copyright infringement or the party&rsquo;s agent. </li>
                  <li>Your name, address and telephone number. </li>
                  <li>A statement under penalty of perjury that you have a good faith belief that the material in question was removed or disabled as a result of mistake or misidentification of the material to be removed or disabled. </li>
                  <li>Your physical or electronic signature. </li>
                </ol>
                <p>You may submit your counter-notification letter to our Designated DMCA Agent by fax, mail, or email at the contact information noted above.<br />
                  If you send us a valid, written counter-notification letter meeting the requirements described above, we will restore your removed or disabled material after 10 business days but no later than 14 business days from the date we receive your counter notification, unless our Designated DMCA Agent first receives notice from the party filing the original notification of alleged copyright infringement informing us that such party has filed a court action to restrain you from engaging in infringing activity related to the material in question.<br />
                  Please note that if you materially misrepresent that the disabled or removed content was removed by mistake or misidentification, you may be liable for damages, including costs and attorney&rsquo;s fees. <br />
                  <strong>4.  Repeat Infringer Policy.</strong><br />
                  In accordance with the DMCA and other applicable law, maqinato has adopted a policy of terminating, in appropriate circumstances and at our sole discretion, users of the Service who are deemed to be repeat infringers.  We also may, in our sole discretion, limit access to the Service and/or terminate the memberships of any users of the Service who infringe any intellectual property rights of others, whether or not there is any repeat infringement.</p>
                <p> will remove or disable access to the allegedly infringing material and promptly notify the alleged infringer of your claim.  We also will advise the alleged infringer of the DMCA statutory counter-notification procedure described below in Section 3 by which the alleged infringer may respond to your claim and request that we restore this material.<br />
                  <strong>3.  Counter-Notification.</strong> <br />
                  If you believe your own copyrighted material has been removed from the Service as a result of mistake or misidentification, you may submit a written counter-notification letter to our Designated DMCA Agent pursuant to Sections 512(g)(2) and (3) of the DMCA.  To be an effective counter notification under the DMCA, your letter must include substantially the following:</p>
                <ol>
                  <li>Identification of the material that has been removed or disabled and the location at which the material appeared before it was removed or disabled. </li>
                  <li>A statement that you consent to the jurisdiction of the Federal District Court in which your address is located, or if your address is outside the United States, for any judicial district in which the service provider may be found. </li>
                  <li>A statement that you will accept service of process from the party that filed the notification of alleged copyright infringement or the party&rsquo;s agent. </li>
                  <li>Your name, address and telephone number. </li>
                  <li>A statement under penalty of perjury that you have a good faith belief that the material in question was removed or disabled as a result of mistake or misidentification of the material to be removed or disabled. </li>
                  <li>Your physical or electronic signature. </li>
                </ol>
                <p>You may submit your counter-notification letter to our Designated DMCA Agent by fax, mail, or email at the contact information noted above.<br />
                  If you send us a valid, written counter-notification letter meeting the requirements described above, we will restore your removed or disabled material after 10 business days but no later than 14 business days from the date we receive your counter notification, unless our Designated DMCA Agent first receives notice from the party filing the original notification of alleged copyright infringement informing us that such party has filed a court action to restrain you from engaging in infringing activity related to the material in question.<br />
                  Please note that if you materially misrepresent that the disabled or removed content was removed by mistake or misidentification, you may be liable for damages, including costs and attorney&rsquo;s fees. <br />
                  <strong>4.  Repeat Infringer Policy.</strong><br />
                  In accordance with the DMCA and other applicable law, maqinato has adopted a policy of terminating, in appropriate circumstances and at our sole discretion, users of the Service who are deemed to be repeat infringers.  We also may, in our sole discretion, limit access to the Service and/or terminate the memberships of any users of the Service who infringe any intellectual property rights of others, whether or not there is any repeat infringement.</p>
                    <i>Last Updated:</i> March 15, 2013
                </div>
            </div>
        </section>
        <!-- INCLUDE FOOTER   -->
        <?php include Router::rel('web').'templates/footer.php'; ?>
    </body>
</html>
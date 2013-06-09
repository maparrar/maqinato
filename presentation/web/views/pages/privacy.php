<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato :: Privacy</title>
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
    <body id="privacyPage">
        <!-- INCLUDE HEADER   -->
        <?php include Router::rel('web').'templates/header.php'; ?>
        <section id="page">
            <div class="wrapper">
                <aside>
                    <h1>Privacy Policy</h1>
                </aside>			
                <div class="page-content">
                    <p>maqinato inc.</p>
                    <p>This website, available at www.https://github.com/maparrar/maqinato (the “Site”), and the charitable contributions social networking service accessible through the Site (the “Service”), are provided by maqinato inc. (“maqinato,” “we” and “us”). We understand that you care how information about you is collected, used and shared. Accordingly, this Privacy Policy sets forth the information about you that will be collected when you access the Site or use the Service, as well as how that information is used, maintained, and, in some cases, shared. </p>
                    <p>Please note that by accessing the Site or using the Service, you are accepting the practices described in this Privacy Policy. If you do not agree to this Privacy Policy, please do not use the Site or Service.</p>
                    <p class="subtitle">What is the purpose of this Privacy Policy?</p>
                    <p>This Privacy Policy discloses our personal information gathering and dissemination practices with respect to the Site and the Service.  Please read this Privacy Policy carefully.</p>
                    <p class="subtitle">What information do we collect from users and how is it used?</p>
                    <p>The following describes personal and other information we may collect about you through your use of the Site or Service, and how we use and maintain that information:</p>
                    <p><i>Registration.</i> Before you can utilize the Service, we will ask that you register and provide us with your first name, last name and email address, and generate a password.  Although optional, you may also submit other information about yourself, such as your contact information and personal details. This registration information will be used for identification purposes, to communicate with you regarding your account with us, and to facilitate the functioning of the Service. We may keep all of this information indefinitely.</p>
                    <p><i>Donations and Other Transactions.</i> When you make a donation or engage in any other financial transaction through the Service, you will be asked to provide certain of your financial information, such as your credit card or PayPal account and billing address. We will only keep this information as long as necessary to complete the transaction or series of recurring transactions authorized by you.</p>
                    <p><i>Social Networking Activities.</i> The Service includes the ability for you and other users to engage in social networking.  As a result, we will also receive information about you: (i) when you choose to post or otherwise share it on the Service, or (ii) when other users post or otherwise share information about you on the Service. We may use such information about you in a variety of ways, including to administer the Service and enhance your experience with the Service, and to communicate with you about the Service and new offerings associated with the Service. We may keep all of this information indefinitely. And any such information that you or others post to the Service will be readily accessible by any other users of the Service. </p>
                    <p><i>Correspondence.</i> If you correspond with us through the Site or Service, or via email, we may gather in a file specific to you the information that you submit. We may keep this information indefinitely.</p>
                    <p><i>Promotions.</i> We will collect certain information about you if you participate in a maqinato promotion, such as a sweepstakes or contest.  For example, you may be asked to provide your name, email address, and mailing address.  We will store this information in our database and will use it to communicate with you about the promotion.  We may also use this information to promote the Service to you in the future, unless you inform us that you opt out from receiving such communications.</p>
                    <p><i>URLs and IP Addresses.</i> Like many other online services, we collect information about users’ utilization and navigation of the Site and Service. This information helps us to design the Site and Service to better suit our users’ needs. For example, the Site will track the URL that you visited before you came to the Site, the URL to which you next go and your Internet Protocol (IP) address. We use your IP address to help diagnose problems with our server and to administer the Site. Your IP address also is used to help identify you and to gather broad demographic information.</p>
                    <p><i>Information Collected With Technologies.</i> Like most websites, we employ technologies such as cookies on certain pages of the Site and in connection with our Service. Cookies make using the Site and the Service easier by, among other things, saving your preferences for you. We may also use cookies to deliver content tailored to your interests. Our cookies may enable us to relate your use of the Site or Service to personally identifying information that you previously submitted, such as calling you by name when you return to the Site. If your browser is set to reject cookies, or if your browser notifies you that you are about to receive a cookie and you reject it, then your use of the Site or Service may not be as efficient or as enjoyable as it would be if the cookie were enabled. The information that we collect with cookies allows us to improve our marketing and promotional efforts, to statistically analyze usage of the Site and Service, and to improve and customize our content and other offerings. However, we only use information collected with cookies on an aggregated basis without the use of any information that personally identifies you. </p>
                    <p><i>User Behavior Data.</i> We also collect certain behavioral information regarding users of the Service, such as the categories of nonprofits to which your donations are allocated, the types of good deeds you upload, and the stories you share through the Service. Understanding this information enables us to enhance the Service, but we only user behavior data on an aggregated basis without the use of any information that personally identifies you. </p>
                    <p class="subtitle">To whom do we disclose information about you that we collect?</p>
                    <p>We will not share, rent, sell or otherwise disclose any of the personally identifiable information that we collect about you through the Site or the Service, except in any of the following situations:</p>
                    <ul>
                        <li>We may disclose certain personal information about you to the nonprofits that will receive your donations (or portions thereof) through the Service. These nonprofits are not bound by the terms of this Privacy Policy, and may have privacy practices that vary from those stated herein. </li>
                        <li>We may also disclose information that we collect about you to our third-party contractors and payment processors who perform services for maqinato in connection with the Site or Service, or to complete or confirm a transaction or series of transactions that you conduct with us. </li>
                        <li>We may disclose the results of aggregated data about you for marketing or promotional purposes. In these situations, we do not disclose to these entities any information that could be used to personally identify you. Certain information, such as your password, is not disclosed to marketing advertisers at all, even in aggregate form. </li>
                        <li>We may disclose information about you as part of a merger, acquisition or other sale or transfer of maqinato’s assets or business. We do not guarantee that any entity receiving such information in connection with one of these transactions will comply with all terms of this Privacy Policy.</li>
                        <li>We may be legally obligated to disclose information about you to the government or to third parties under certain circumstances, such as in connection with illegal activity on the Site or in connection with the Service, or to respond to a subpoena, court order or other legal process. We reserve the right to release information that we collect to law enforcement or other government officials, as we, in our sole and absolute discretion, deem necessary or appropriate. </li>
                    </ul>
                    <p>If you access the Site or use the Service from outside of the United States, information that we collect about you will be transferred to servers inside the United States and maintained indefinitely, which may involve the transfer of information out of countries located in the European Economic Area. By allowing maqinato to collect information about you, you consent to such transfer and processing of your data.</p>
                    <p class="subtitle">What security measures do we employ in connection with the Site and Service?</p>
                    <p>To help protect the privacy of data you transmit through the Site or Service, where personally identifiable information is requested, we use technology designed to encrypt the information that you input before it is sent to us. In addition, we take steps to protect the user data we collect against unauthorized access. However, you should keep in mind that the Site and the Service are run on software, hardware and networks, any component of which may, from time to time, require maintenance or experience problems or breaches of security beyond our control. </p>
                    <p>Please also be aware that despite our best intentions and the guidelines outlined in this Privacy Policy, no data transmission over the Internet or encryption method can be guaranteed to be 100% secure.</p>
                    <p class="subtitle">How can you correct or update information that we collect about you?</p>
                    <p>You may correct or update information collected about you by managing your account profile or by contacting maqinato at the email or mailing address noted below. We will use reasonable efforts to update our records. For our records, we may retain original and updated information for reasons such as technical constraints, dispute resolution, troubleshooting and agreement enforcement.</p>
                    <p class="subtitle">What are the policies of linked websites and other third parties?</p>
                    <p>This Privacy Policy only addresses the use and disclosure of information that we collect from you. You should be aware that when you are on the Site, you could be directed to other websites that are beyond our control, and maqinato is not responsible for the privacy practices of third parties or the content of any other websites. We encourage you to read the posted privacy policy whenever interacting with any website.</p>
                    <p class="subtitle">How will I know about changes in the Privacy Policy?</p>
                    <p>We reserve the right to update this Privacy Policy from time to time. Please visit this web page periodically so that you will be apprised of any changes. </p>
                    <p class="subtitle">What is our policy on children users?</p>
                    <p>We do not knowingly collect or maintain personally identifiable information from persons under 13 years old. IF YOU ARE UNDER 13 YEARS OF AGE, PLEASE DO NOT USE THIS SITE OR THE SERVICE AT ANY TIME OR IN ANY MANNER. If maqinato learns that personally identifiable information of persons less than 13 years old has been collected without verifiable parental consent, then maqinato will take the appropriate steps to delete this information. If you are a parent or guardian and discover that your child under the age of 13 has obtained an account with us, you may alert maqinato at the address below and request that we delete that child’s personal information from our systems.</p>
                    <p class="subtitle">What are my California Privacy Rights?</p>
                    <p>Under California law, a California resident with whom we have an established relationship has the right to request certain information with respect to the types of personal information maqinato has shared with third parties for their direct marketing purposes, and the identities of those third parties, within the immediately preceding calendar year, subject to certain exceptions. All requests for such information must be in writing and sent to maqinato at the mailing address set forth below.</p>
                    <p class="subtitle">What law governs my use of the Site and Service?</p>
                    <p>By choosing to visit this Site, use the Service, or otherwise provide information to maqinato, you agree that any dispute over privacy or the terms contained in this Privacy Policy will be governed by the law of the State of New York, U.S.A., without reference to its conflicts of laws principles. You also agree to be subject to all limitations on damages contained in our Terms of Service.</p>
                    <p class="subtitle">How can you contact us?</p>
                    <p>If you have any questions about this Privacy Policy, or need to reach us for any other reason, you may contact us by e-mail at privacy@https://github.com/maparrar/maqinato, or by mail at: </p>
                    <p>
                        maqinato inc.<br>
                        901 Mission Street<br>
                        Suite 105<br>
                        San Francisco, CA 94103<br>
                        United Stated<br>
                    </p>
                    <i>Last Updated:</i> March 15, 2013
                </div>
            </div>
        </section>
        <!-- INCLUDE FOOTER   -->
        <?php include Router::rel('web').'templates/footer.php'; ?>
    </body>
</html>
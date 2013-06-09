<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>maqinato :: Terms</title>
        <meta name="author" content="maqinato">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <?php
            /**
            * Terms page file
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
    <body id="termsPage">
        <!-- INCLUDE HEADER   -->
        <?php include Router::rel('web').'templates/header.php'; ?>
        <section id="page">
            <div class="wrapper">
                <aside>
                    <h1>Terms</h1>
                </aside>			
                <div class="page-content">
                    <p>maqinato Inc.<p>
                    <p>Last Updated: March 15, 2013.</p>
                    <p>These Terms of Service (the “Terms”) govern your use of the social networking platform made available by maqinato inc. (“maqinato,” “we” and “us”) at www.https://github.com/maparrar/maqinato, which provides you with an online mechanism for making donations to nonprofits from our database of nonprofit organizations, uploading good deeds and sharing stories, while interacting with other users of the maqinato platform (collectively, the “Service”).  The details of the Service are set forth more fully in these Terms below and elsewhere on our website.  To agree to these Terms, click “I agree” where indicated in our user registration process.</p>
                    <p>We reserve the right to modify these Terms prospectively at any time.  We will post any changes to these Terms on our website, and will indicate the date the Terms were last revised.  Your continued use of the Service after any such change constitutes your acceptance of the updated Terms.</p>
                    <p class="item">1. <span>Eligibility.</span></p>
                    <p>Before accessing or using the Service, you must agree to these Terms.  You are also required to register and set up a profile for your presence on the maqinato platform (your "Profile").</p>
                    <p>The Service is intended solely for individuals who are at least 18 years of age or older.  Any registration by, use of or access to the Service by anyone under 18 is unauthorized.  By using the Service, you represent and warrant that you are 18 or older.</p>
                    <p class="item">2. <span>Registration.</span></p>
                    <p>When you register with maqinato, you will be asked to disclose certain personal information about you, including your name and email address, all of which will be subject to our Privacy Policy (available here).  Please keep in mind that information posted to your Profile will be viewable by other users of the Service.</p>
                    <p>You agree to provide us with true, accurate and complete information about you as requested in our registration process.  You also agree to update such information promptly as necessary to keep it current and accurate.</p>
                    <p class="item">3. <span>Donations.</span></p>
                    <p>The Service is intended to provide you with a unique system for facilitating your donations to nonprofit organizations within the context of a social networking platform.  We maintain a database of 501(c)(3) organizations, which can be found on our website.  We are routinely updating this database, so you may want to check it from time to time.  We sort the nonprofits in our database into a variety of Impact Areas (defined below) based on the stated objectives of each nonprofit.</p>
                    <p class="subtitle">The Service provides you with two ways to make donations to these nonprofits:</p>
                    <p class="subitem">A. Donations to an Auto Generated Set of Nonprofits.  On the donations page, you have the option to select from a variety of nonprofit categories (which we refer to as “Impact Areas”) and a variety of causes in each Impact Area (which we refer to as “Tags”).  Once you have submitted these selections and created the combination that you want (which we refer to as the “Cause Mix”), the Service will auto generate a set of nonprofits based on the combination of Impact Areas and Tags—i.e., the Cause Mix—that you have selected and other variables built into our algorithm.  If you approve of the list, you may proceed to the checkout process and your associated donation (or donations) will then be allocated to those nonprofits.  If you do not like a particular auto generated list of nonprofits, you may request (as many times as you would like) that a new list be auto generated, or before completing the checkout process you may cancel the request and identify an alternative combination of Impact Areas and Tags.  (Alternatively, you may utilize the second option for making donations through the Services described in Section 3.B. below.)  For any auto generated set of nonprofits, you may designate either a one-time donation or recurring donations for a designated period of time, such as once per month for a year.  At the end of each month, we will send you a report identifying the nonprofits receiving your allocated donations.</p>
                    <p class="subitem">B. Donations to Specific Nonprofits Identified by You.  Alternatively, you may simply select one or more of the 501(c)(3) organizations from our catalog to receive your donation(s).  To help with this, you may run word searches on our catalog or the “Giving” page on the Service, which will return Tags that can be further searched and/or names of 501(c)(3) organizations from which you may select to receive your donation(s).  For this option, you may also designate either a one-time donation or recurring donations for a designated period of time, such as once per month for a year.</p>
                    <p>Your donation payments under either Section 3.A or 3.B above will be delivered to maqinato or our authorized payment processing agent.  Within up to thirty (30) days of maqinato (or our agent) receiving a donation payment from you, we will pass it along on your behalf to the nonprofit(s) identified as set forth above, minus our ten percent (10%) fee.</p>
                    <p>This 10% fee is the only fee that we assess on your donation payments; it covers any third-party fees that may be incurred by maqinato in connection with your donation payments (which, for your reference, can amount to as much as 7% of the payment).  No additional fees apply.</p>
                    <p>You acknowledge that this 10% fee is being paid by you to maqinato in exchange for your use of the Service.  This fee is the only consideration that maqinato receives for facilitation of your donations through the Service—that is, maqinato receives no compensation from any of the nonprofit organizations in our catalog in connection with the Service.</p>  
                    <p>Although we may later expand the options for processing your donations, currently all payments from you to maqinato, and then from maqinato to the nonprofits, will be processed via PayPal.</p>
                    <p class="item">4. <span>Disclaimers Regarding Donations.</span></p>
                    <p>maqinato makes no representations regarding your donations, including whether or not they qualify for treatment as charitable contributions or are otherwise tax deductible under applicable law.  We suggest that you hire an attorney, accountant, and/or financial advisor to advise you regarding any such questions.</p>
                    <p>maqinato shall not be responsible for any dissatisfaction you may experience with respect to any nonprofit’s use of any of your donations through the Service.  You acknowledge that maqinato cannot control the use of your donations by such nonprofits.</p>
                    <p>Finally, if you make any donations to an auto generated set of nonprofits (under the option described in Section 3.A. above), maqinato makes no representations regarding the set of nonprofits so generated.  As noted above, each such set of nonprofits is generated by an algorithm based on the combination of Impact Areas and Tags that you select and other variables built into our algorithm.  As noted, if you do not approve of any such auto generated list, your option is to cancel the transaction before checking out.</p>
                    <p class="item">5. <span>Dealings with Third Parties.</span></p>
                    <p>To the extent you are introduced to any nonprofit organization, or other organizations or individuals, through the Service, your interactions with such third parties are solely your responsibility.  YOU AGREE THAT maqinato SHALL NOT BE RESPONSIBLE OR LIABLE FOR ANY LOSS OR DAMAGE OF ANY KIND ARISING FROM ANY DEALINGS BETWEEN YOU AND SUCH THIRD PARTIES.</p>
                    <p class="item">6. <span>User Content; License to maqinato.</span></p>
                    <p>You understand and agree that you are solely responsible for all content, including all text, photos, videos and information, which you upload to or otherwise make available through the Service (collectively, your “User Content”).  You hereby represent and warrant that you own or have the necessary licenses, rights, consents, and permissions to use and authorize maqinato to use all copyright, trademark, trade secret and other intellectual property rights in and to any and all of your User Content, and to enable inclusion and use of your User Content in the manners contemplated by the Service and these Terms.  You may not upload to or otherwise make available any content through the Service that you did not create or that you do not have permission to so upload or make available.</p>
                    <p>By uploading to or otherwise making available your User Content through the Service, you automatically grant to maqinato an irrevocable, perpetual, non-exclusive, transferable, fully paid, worldwide license (with the right to sublicense) to use, copy, publicly perform, publicly display, reformat, translate, excerpt (in whole or in part) and distribute such User Content for any purpose (including commercial, advertising and other purposes), on or in connection with the Service or the promotion thereof, to prepare derivative works of, or incorporate into other works, such User Content, and to grant and authorize sublicenses of any of the foregoing.  When you upload to or otherwise make available your User Content through the Service, you also authorize and direct maqinato to make such copies thereof as we deem necessary in order to facilitate the transmission and storage of such User Content.</p>
                    <p>You should exercise caution, good sense and sound judgment in uploading content to the Service.  You should not upload any content to the Service that you consider to be confidential or proprietary.</p>
                    <p>You may remove any of your User Content from the Service at any time.  If you remove any of your User Content, the license granted by you to maqinato above will immediately terminate with respect to such User Content, except that we may retain archived copies of such User Content for a reasonable period of time for audits and similar purposes.  maqinato does not assert any ownership over your User Content; rather, as between maqinato and you, subject to the rights granted to us in these Terms, you retain full ownership of all of your User Content and any intellectual property rights or other proprietary rights associated with your User Content.</p>
                    <p class="item">7. <span>No Endorsement.</span></p>
                    <p>You acknowledge that maqinato does not endorse, support, represent or guarantee the truthfulness, accuracy, or reliability of any content uploaded to or otherwise made available through the Service by maqinato users, or endorse any opinions expressed in such content.  You acknowledge that any reliance on material available on or through the Service will be at your own risk.</p>
                    <p class="item">8. <span>User Conduct.</span></p>
                    <p>You represent, warrant and agree that your User Content will not violate or infringe upon the rights of any third party, including any copyright, trademark, privacy, publicity or other intellectual property, personal or proprietary right.</p>
                    <p>Without limiting the generality of the foregoing, you agree not to upload to or otherwise make available through the Service any of the following:</p>
                    <ul>
                        <li>any content that is not your original work, unless you have permission from the rightful owner to post such content and to grant maqinato all of the licensed rights granted herein;</li>
                        <li>any private information of any third party;</li>
                        <li>any content that is harmful, threatening, unlawful, defamatory, infringing, abusive, inflammatory, harassing, vulgar, obscene, fraudulent, invasive of privacy or publicity rights, hateful, or racially, ethnically or otherwise objectionable; </li>
                        <li>any unsolicited or unauthorized advertising, solicitations, promotional materials, spam, or any other form of solicitation; or </li>
                        <li>any software viruses or other computer code, viruses, malware, bots, files or programs designed to interrupt, destroy or limit the functionality of any computer software or hardware or telecommunications equipment.</li>
                    </ul>
                    <p>In using the Service, you also agree not to do any of the following:</p>
                    <ul>
                        <li>impersonate any other person or entity in your Profile, or falsely state or otherwise misrepresent yourself or your affiliation with any other person or entity in your Profile;</li>
                        <li>use or attempt to use another user’s maqinato profile;</li>
                        <li>intimidate or harass another individual on the Service;</li>
                        <li>conduct any activities that could damage, disable, overburden or impair the Service; or</li>
                        <li>use the Service in a way that is not in compliance with any applicable law or regulation.</li>
                    </ul>
                    <p>maqinato does not review User Content uploaded to or otherwise made available through the Service.  However, we reserve the right to review User Content at any time, and may remove any or all of your User Content from the Service for violating any of the above restrictions, as determined in our sole discretion.</p>
                    <p class="item">9. <span>Intellectual Property in the Service.</span></p>
                    <p>Subject to the terms and conditions of these Terms, the Service, including all text, graphics, photos, videos, information, applications, and any other content available on or through the Service, and their selection and arrangement in the Service, are the intellectual property of maqinato or our licensors, with all rights reserved.</p>
                    <p>Subject to the terms and conditions of these Terms, maqinato grants you a non-exclusive, revocable, limited license to access and use the Service solely for the purposes set forth in these Terms.  Any use of the Service other than as specifically authorized in these Terms, without the prior written permission of maqinato, is strictly prohibited and will automatically terminate the foregoing license granted to you.  Also, this license will terminate upon any termination of the Terms or upon any suspension, termination or cancellation of your access to the Service.</p>
                    <p>You agree not to do any of the following:</p>
                    <ul>
                        <li>Reverse engineer, decompile, disassemble, translate, modify, alter or otherwise change the Service, or any part thereof.</li>
                        <li>Attempt to derive the source code or structure of the Service, or any part thereof.</li>
                        <li>Remove from the Service, or alter, any of maqinato’s or any of our licensors’ trademarks, trade names, logos, patent or copyright notices, or other notices or markings.</li>
                        <li>Distribute, sublicense or otherwise transfer access to the Service to others.</li>
                    </ul>
                    <p class="item">10. <span>Copyright Protection.</span></p>
                    <p>The U.S. Digital Millennium Copyright Act (“DMCA”) provides recourse to copyright owners who believe that their rights under the United States Copyright Act have been infringed by acts of third parties over the Internet.  If you believe that any content uploaded, posted or otherwise transmitted through the Service infringes upon any copyright which you own or control, you may so notify us in accordance with our DMCA process accessible here.</p>
                    <p>In accordance with the DMCA and other applicable law, we have adopted a policy of terminating, in appropriate circumstances and at our sole discretion, users of the Service who are deemed to be repeat infringers.  maqinato may also at its sole discretion limit access to the Service or terminate the Profile of any user who infringes any intellectual property rights of others, whether or not there is any repeat infringement.</p>
                    <p class="item">11. <span>Trademarks.</span></p>
                    <p>All trademarks, service marks, logos and trade names associated with maqinato and/or the Service, whether registered or unregistered, are proprietary to maqinato or to other companies where so indicated.  Such marks may not be used, including as part of others’ trademarks or domain names, in connection with any product or service in any manner that is likely to cause confusion, and may not be copied, imitated, or used, in whole or in part, without the prior written permission of maqinato.</p>
                    <p class="item">12. <span>Third-Party Websites and Content.</span></p>
                    <p>The Service may contain or deliver links to other websites (each, a “Third-Party Site”) as well as applications, software, text, graphics, pictures, designs, music, sound, video, articles, photographs, information, and other content belonging to or originating from third parties (“Third-Party Content”).  Such Third-Party Sites and Third-Party Content are not investigated, monitored or checked for accuracy, appropriateness, or completeness by maqinato, and we are not responsible for any Third-Party Sites accessed through the Service or any Third-Party Content uploaded to, or otherwise available through, the Service.</p>
                    <p>If you decide to leave the Service and access a Third-Party Site or to use or install any Third-Party Content, you do so at your own risk and you should be aware that these Terms and our other policies no longer govern.  You should review the terms and policies, including privacy and data gathering practices, applicable to any Third-Party Site or Third-Party Content to which you navigate or relating to any applications you use or install from the Service.</p>
                    <p class="item">13. <span>User Disputes.</span></p>
                    <p>You are solely responsible for your interactions with other users of the Service.  We reserve the right, but have no obligation, to monitor interactions or disputes between you and any other such user.</p>
                    <p class="item">14. <span>Privacy.</span></p>
                    <p>All personally identifiable information that you provide to us through the Service will be held and used in accordance with our Privacy Policy, which may be accessed here.</p>
                    <p class="item">15. <span>Disclaimers.</span></p>
                    <p>You acknowledge that maqinato is not responsible or liable in any manner for any content (including your User Content), or any Third-Party Content, posted or transmitted through the Service, whether posted or transmitted by users of the Service, by maqinato, by third parties or by any of the equipment or software underlying the Service.  Although we have rules for user conduct and postings, we do not control and are not responsible for what users upload or otherwise make available through the Service.  maqinato is not responsible for the conduct, whether online or offline, of any user of the Service.</p>
                    <p>The Service may be temporarily unavailable from time to time for maintenance or other reasons.  maqinato assumes no responsibility for any error, omission, interruption, deletion, defect, delay in operation or transmission, communications line failure, theft or destruction or unauthorized access to, or alteration of, user communications. maqinato is not responsible for any technical malfunction or other problems of any telephone network or service, computer systems, servers or providers, computer or mobile phone equipment, software, failure of email on account of technical problems or traffic congestion on the Internet or on the Service or combination thereof, including injury or damage to your or to any other person’s computer, mobile device, or other hardware or software, related to or resulting from using or downloading materials in connection with the Service.  Under no circumstance will maqinato be responsible for any loss or damage resulting from your use of the Service, or from any content (including your User Content) or Third-Party Content uploaded or otherwise made available through the Service, or from any interactions between users of the Service (whether online or offline).</p>
                    <p>maqinato reserves the right to modify the Service at any time without notice.</p>
                    <p>YOU EXPRESSLY AGREE THAT USE OF THE SERVICE IS AT YOUR SOLE RISK AND IS PROVIDED ON AN “AS IS” BASIS WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF TITLE OR IMPLIED WARRANTIES OF NON-INFRINGEMENT, MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE (EXCEPT ONLY TO THE EXTENT PROHIBITED UNDER APPLICABLE LAW).</p>
                    <p>maqinato DOES NOT GUARANTEE ANY SPECIFIC RESULT FROM USE OF THE SERVICE.  maqinato DOES NOT REPRESENT OR WARRANT THAT THE SERVICE IS ACCURATE, COMPLETE, RELIABLE, CURRENT OR ERROR-FREE OR THAT THE SERVICE IS FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.</p>
                    <p class="item">16. <span>Limitations on Liability.</span></p>
                    <p>IN NO EVENT WILL maqinato OR ITS DIRECTORS, OFFICERS, EMPLOYEES, CONTRACTORS, PARTNERS OR AGENTS BE LIABLE TO YOU FOR ANY CONSEQUENTIAL, INCIDENTAL OR OTHER SPECIAL OR INDIRECT DAMAGES (INCLUDING FOR ANY LOST BUSINESS PROFITS) OR FOR PUNITIVE OR EXEMPLARY DAMAGES, HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, ARISING FROM YOUR USE OF THE SERVICE, EVEN IF maqinato IS AWARE OR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</p>
                    <p class="item">17. <span>Termination.</span></p>
                    <p>maqinato may terminate your access to the Service, delete your Profile and/or prohibit you from using or accessing the Service for any reason, or no reason, at any time in its sole discretion, with or without notice.</p>
                    <p class="item">18. <span>Disputes and Governing Law.</span></p>
                    <p>You agree that any dispute, claim or controversy arising out of or relating to the Service or this Agreement shall be settled by independent arbitration involving a neutral arbitrator and administered by the American Arbitration Association.  The arbitration shall be conducted in San Francisco, California.  The arbitrator shall apply the Commercial Arbitration Rules of the American Arbitration Association, and the judgment upon the award rendered by the arbitrator may be entered by any court having jurisdiction.  Note that there is no judge or jury in an arbitration proceeding and the decision of the arbitrator shall be binding upon both parties.</p>
                    <p>This Agreement and performance hereunder shall be governed by and construed in accordance with the laws of the State of California, without giving effect to its conflict of laws provisions.</p>
                    <p class="item">19. <span>Indemnity.</span></p>
                    <p>You agree to indemnify and hold harmless maqinato, and its directors, officers, agents, contractors, partners and employees, from and against any loss, liability, claim, demand, damages, costs and expenses, including reasonable attorney’s fees, arising out of or in connection with any of your User Content, your conduct in connection with the Service, or any violation of these Terms or of any law or rights of any third party.</p>
                    <p class="item">20. <span>Miscellaneous.</span></p>
                    <p>These Terms constitute the entire agreement between you and maqinato regarding the use of the Service, superseding any prior agreements between you and maqinato relating to your use of the Service.  The failure of maqinato to exercise or enforce any right or provision of these Terms shall not constitute a waiver of such right or provision in that or any other instance.  If any provision of these Terms is held invalid, the remainder of these Terms   shall continue in full force and effect.  If any provision of these Terms shall be deemed unlawful, void or for any reason unenforceable, then that provision shall be deemed severable from these Terms and shall not affect the validity and enforceability of any remaining provisions.</p>
                    <p class="item">21. <span>Questions.</span></p>
                    <p>If you have any questions regarding these Terms, please contact us by sending an email to privacy@https://github.com/maparrar/maqinato.</p>
                </div>
            </div>
        </section>
        <!-- INCLUDE FOOTER   -->
        <?php include Router::rel('web').'templates/footer.php'; ?>
    </body>
</html>
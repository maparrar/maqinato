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
<div id="modalRecoveryPassword">
<!-- MODAL GOOD DEED GENERATOR -->
    <div id="recoveryPassword-generator">
        <h4>Recovery Password</h4>
        <div id="subtitle">Please type your email so we can send instructions on recovering your password</div>
        <div id="content">
            <div class="recovery-form">
               <input id="email-recovery" type="text" placeholder="email">
            </div>
        </div>
        <span class="pass-error"></span>
        <input id="send" class="btn-recovery" type="submit" value="Send">
    </div>
</div>
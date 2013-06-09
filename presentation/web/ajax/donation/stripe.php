<?php
/**
 * paypalPay File
 *
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package web
 * @subpackage ajax
 */
if(!class_exists('Router')) require_once '../../../config/Router.php';
include_once Router::rel('controllers').'AccessController.php';
include_once Router::rel('controllers').'PaymentController.php';




include_once Router::rel('vendors').'stripe/lib/Stripe.php';
error_log("en stripe <-------------------");





// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://manage.stripe.com/account
Stripe::setApiKey("sk_test_6PZIrFq0PoKv7CsFnsLWgE8F");

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// Create the charge on Stripe's servers - this will charge the user's card
try {
    $charge = Stripe_Charge::create(array(
      "amount" => 1000, // amount in cents, again
      "currency" => "usd",
      "card" => $token,
      "description" => "payinguser@example.com")
    );
} catch(Stripe_CardError $e) {
    // The card has been declined
}


?>
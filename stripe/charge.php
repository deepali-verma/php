<?php 

//let's say each article costs 15.00 bucks

try {

	require_once('Stripe/lib/Stripe.php');
        require_once 'config.php';
        require_once 'customer.class.php';
	      Stripe::setApiKey(SECRET_KEY);
        $customer_ob = new Customer();
        $token = $_POST['stripeToken'];
        // Create a Customer
        $customer = Stripe_Customer::create(array(
          "source" => $token,
          "description" => "Example customer")
        );
        //save customer details in DB for later use
        if(isset($customer->id) && isset($_POST['stripeEmail'])){
            if($customer_ob->addCustomer($customer->id,trim($_POST['stripeEmail']))){
                echo "<h1>Your payment has been completed. We will send you the test item in a minute.</h1>";
            }
            else{
                echo "Some error occured while saving customer details";
            }
        }
        else{
            echo 'Customer id or email address missing.Can\'t update Database';
        }
	      $charge = Stripe_Charge::create(array(
          "amount" => 1500,
          "currency" => "usd",
          "customer" => $customer->id
        ));
        //Make an associative array from response
        $charge = $charge->__toArray(true);
        //Save the transaction details in db
        if(!$customer_ob->addTransaction($charge)){
            echo "Some error occured while saving transaction details";
        }
}

catch(Stripe_CardError $e) {
	
}

//catch the errors in any way you like

 catch (Stripe_InvalidRequestError $e) {
  // Invalid parameters were supplied to Stripe's API

} catch (Stripe_AuthenticationError $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)

} catch (Stripe_ApiConnectionError $e) {
  // Network communication with Stripe failed
} catch (Stripe_Error $e) {

  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {

  // Something else happened, completely unrelated to Stripe
}
?>
<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Payments using Stripe</title>
</head>
<body>
	

<h1>Buy test item</h1>
<p>Price: 15.00$</p>
<p>Name: Test item</p>


<form action="charge.php" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key=<?php echo PUBLISH_KEY;?>
    data-image="img/test.jpeg"
    data-name="Test payment"
    data-description="Get test item ($15.00)"
    data-amount="1500">   
  </script>
</form>



</body>
</html>



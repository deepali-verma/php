<?php
require_once 'config.php';
global $mysqli;
$row = array();
$table = "products";
//Retrieve all the products info from products table
$stmt = $mysqli->prepare("SELECT pid,product,product_img,price,currency FROM $table");
if($stmt && $stmt->execute()){
    $stmt->bind_result($pid,$product,$product_img,$price,$currency);
    while($stmt->fetch()){
        $row[] = array('pid'=>$pid,'product'=>$product,'product_img'=>$product_img,'price'=>$price,'currency'=>$currency);
    }
    $stmt->close();
}
if(empty($row)){
    echo "No items in Cart";
    exit;
}
?>
<h4>Welcome, Guest</h4>
 
<div class="product">
    <?php 
    for($i = 0;$i < count($row); $i++){
        $img_name = "img/".$row[$i]['product_img'];
        $product = $row[$i]['product'];
        $price = $row[$i]['price'];
        $item_number = $row[$i]['pid'];
        $currency = $row[$i]['currency'];
?>
    <div class="image">
        <img width="50px" height="50px" src="<?php echo $img_name;?>" />
    </div>
    <div class="name">
        <?php echo $product;?>
    </div>
    <div class="price">
       <?php echo $price."$";?>
    </div>
    <div class="btn">
    <form action="<?php echo PAYPAL_URL_TEST; ?>" method="post" name="frmPayPal1">
        <input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="item_name" value="<?php echo $product;?>">
        <input type="hidden" name="item_number" value="<?php echo $item_number;?>">
        <input type="hidden" name="credits" value="510">
        <input type="hidden" name="userid" value="1">
        <input type="hidden" name="amount" value="<?php echo $price;?>">
        <input type="hidden" name="cpp_header_image" value="img/test.jpeg">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="currency_code" value="<?php echo $currency;?>">
        <input type="hidden" name="handling" value="0">
        <input type="hidden" name="cancel_return" value="http://127.0.0.1:80/modules/paypal-payment/cancel.php"><!-- Set its value according to your configuration settings -->
        <input type="hidden" name="return" value="http://127.0.0.1:80/modules/paypal-payment/success.php"><!-- Set its value according to your configuration settings -->
        <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form> 
</div>
    <?php } ?>
</div>
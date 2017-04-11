<?php
require_once 'config.php';
$item_no            = $_REQUEST['item_number'];
$item_transaction   = $_REQUEST['tx']; // Paypal transaction ID
$item_price         = $_REQUEST['amt']; // Paypal received amount
$item_currency      = $_REQUEST['cc']; // Paypal received currency type
global $mysqli;
$row = array();
//Getting product details
$stmt = $mysqli->prepare("SELECT pid,product,product_img,price,currency FROM products WHERE pid = '$item_no'");
if($stmt && $stmt->execute()){
    $stmt->bind_result($pid,$product,$product_img,$price,$currency);
    while($stmt->fetch()){
        $row[] = array('pid'=>$pid,'product'=>$product,'product_img'=>$product_img,'price'=>$price,'currency'=>$currency);
    }
    $stmt->close();
}
if(empty($row)){
    echo "<h1>Payment Failed</h1>";
    exit;
}
//These value may come from Database
$price = $row[0]['price'];
$currency = $row[0]['currency'];
//Rechecking the product price and currency details
if($item_price==$price && $item_currency==$currency){
    $table = "sales";
    $stmt = $mysqli->prepare("INSERT INTO ".$table." (pid,saledate,transactionid) VALUES (?,CURRENT_TIMESTAMP,?)");
    $stmt->bind_param("is", $item_no,$item_transaction);
    $result = $stmt->execute();
    if($result){
        echo "<h1>Welcome, Guest</h1>";
        echo "<h1>Payment Successful</h1>";
    }
    else{
       echo "<h1>Payment Failed</h1>"; 
    }
    $stmt->close();
}
else{
    echo "<h1>Payment Failed</h1>";
}
?>
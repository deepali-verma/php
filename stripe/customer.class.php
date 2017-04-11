<?php
class Customer{
    public $customer_table = "customers";
    public $transaction_table = "transaction_details";
    /*Get customer details from DB*/
    function getCustomer($customer_id){
        global $mysqli;
        $row = array();
        $stmt = $mysqli->prepare("SELECT id,customer_id,customer_email FROM $this->customer_table WHERE customer_id='$customer_id'");
        if($stmt && $stmt->execute()){
            $stmt->bind_result($id, $customer_id,$customer_email);
            while ($stmt->fetch()){
                    $row[] = array('id' => $id, 'customer_id' => $customer_id, 'customer_email' => $customer_email);
            }
            $stmt->close();
        }
        return $row;
    }

    /*Insert customer details in DB*/
    function addCustomer($customer_id,$customer_email){
        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO ".$this->customer_table."(
                                        customer_id,
                                        customer_email
                                        )
                                        VALUES(
                                        ?,?)"
                                );
        $stmt->bind_param('ss',$customer_id,$customer_email);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    /*Insert transaction details in DB*/
    function addTransaction($transaction_details){
        global $mysqli;
        $transaction_id = $transaction_details['id'];
        $customer_id = $transaction_details['customer'];
        $amount = $transaction_details['amount'];
        $currency = $transaction_details['currency'];
        $brand = $transaction_details['source']['brand'];
        $country = $transaction_details['source']['country'];
        $name = $transaction_details['source']['name'];
        $status = $transaction_details['status'];
        $created_date = $transaction_details['created'];
        $stmt = $mysqli->prepare("INSERT INTO ".$this->transaction_table."(
                                        transaction_id,
                                        customer_id,
                                        amount,
                                        currency,
                                        brand,
                                        country,
                                        name,
                                        status,
                                        created_date
                                        )
                                        VALUES(
                                        ?,?,?,?,?,?,?,?,?)"
                                );
        $stmt->bind_param('ssisssssi',$transaction_id,$customer_id,$amount,$currency,$brand,$country,$name,$status,$created_date);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
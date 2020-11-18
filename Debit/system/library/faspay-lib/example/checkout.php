<?php

require "../Faspay.php";

Faspay_Config::$isProduction = FALSE;

Faspay_Config::$bussinessId = 99999;
Faspay_Config::$bussinessUser = 'bot99999';
Faspay_Config::$bussinessPassword = 'p@ssw0rd';

Faspay_Config::$bcaReturnUrl = 'http://localhost/faspay/example/returnUrl.php';

$params = array(
        'payment_channel' => $_POST["channel"],
        'bill_no' => rand(),
        'bill_total' => 10000,
        'bill_gross' => 10000,
        'bill_miscfee' => 10000,
        'bill_date' => $bill_date = date('Y-m-d H:i:s'),
        'bill_expired' => '2019-06-02 15:30:34',
        'bill_desc' => 'pembelian barang',
        'pay_type'  => '1'
);
try{
    header('Location: ' . FaspayBussiness::redirectToFaspay($params));
}catch (exception $e){
    print($e->getMessage());
}
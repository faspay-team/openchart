<?php
/**
 * Created by PhpStorm.
 * User: gamaliens
 * Date: 1/28/16
 * Time: 1:36 PM
 */


require "../Faspay.php";

Faspay_Config::$isProduction = FALSE;

Faspay_Config::$bussinessId = 99996;
Faspay_Config::$bussinessUser = 'bot99996';
Faspay_Config::$bussinessPassword = 'p@ssw0rd';

$notif = new FaspayNotification();

if($notif->payment_status_code == 2)
{
    // TODO Set payment status in merchant's database to 'success'
}else
{
    // TODO Set payment status in merchant's database to failed'
}
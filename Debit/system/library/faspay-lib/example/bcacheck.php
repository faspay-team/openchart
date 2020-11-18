<?php
/**
 * Created by PhpStorm.
 * User: gamaliens
 * Date: 1/29/16
 * Time: 11:35 AM
 */
require "../Faspay.php";

Faspay_Config::$isProduction = FALSE;

Faspay_Config::$bussinessId = 99999;
Faspay_Config::$bussinessUser = 'bot99999';
Faspay_Config::$bussinessPassword = 'p@ssw0rd';

Faspay_Config::$klikPayCode = 'Dev2Op';
Faspay_Config::$clearKey = 'ClearKeyDevPulau';

$amount = 10000;
$bcaDate= '01/01/2016 22:00:00';

$utils = new Utils();
$keyid = $utils->genKeyId(Faspay_Config::$clearKey);

if(isset($_GET['trx_id']) && (isset($_GET['signature']) || isset($_GET['authkey'])))
{
    $reqSignature = isset($_GET['signature']) ? $_GET['signature'] : '';
    $reqAuthkey   = isset($_GET['authkey']) ? $_GET['authkey'] : '';

    $sig        = $utils->genSignature(Faspay_Config::$klikPayCode,$bcaDate, $_GET['trx_id'], $amount, 'IDR', $keyid);
    $authkey    = $utils->genAuthKey(Faspay_Config::$klikPayCode, $_GET['trx_id'], 'IDR', $bcaDate, $keyid);

    if($sig == $reqSignature || $authkey == $reqAuthkey)
    {
        echo 1;
    }
    else
    {
        echo 0;
    }
}

else{
    echo 'THIS PAGE IS FOR CHECK AUTHKEY/SIGNATURE ONLY';
}
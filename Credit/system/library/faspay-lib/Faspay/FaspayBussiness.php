<?php

/**
 * Created by User: gamaliens
 * Date: 12/28/15
 * Time: 11:06 AM
 */
class FaspayBussiness
{
    public static $result;

    /**
     * @return mixed
     */
    public static function getResult()
    {
        return self::$result;
    }
    public static function postXmlToFaspay($params){

        self::validateParams($params);
        $loads = $params;

        $signatureRequest = self::generateSignature($params['bill_no']);

        $user = array(
            'merchant_id' => Faspay_Config::$bussinessId,
            'signature'   => $signatureRequest
        );

        $ext = array(
            'request'   => 'Post Data Transaksi',
            'terminal'  => 10
        );
        $loads = array_merge($loads, $user, $ext);
        $result = Transaction::processDebitTransaction($loads);

        return $result;
    }

    public static function redirectToFaspay($result){

        $signatureRequest = self::generateSignature($result->bill_no);
        $baseUrl = Faspay_Config::getRedirectUrl();

        $url = $baseUrl.$signatureRequest."?trx_id=".$result->trx_id."&merchant_id=".$result->merchant_id."&bill_no=".$result->bill_no;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);                   

        echo $result;
        exit;


    }

    public static function redirect2Faspay($result){

        $signatureRequest = self::generateSignature($result->bill_no);
        $baseUrl = Faspay_Config::getRedirectUrl();

        $url = $baseUrl.$signatureRequest."?trx_id=".$result->trx_id."&merchant_id=".$result->merchant_id."&bill_no=".$result->bill_no; 
        header("Location:$url");
    }
    
    public static function redirect2PermataNet($va_number,$amount){

        $post = array(                           
                    "va_number"         =>$va_number,
                    "amount"            =>$amount                                                     
                );

        $string = '<form method="post" name="form" id = "BCAForm" action="'.Faspay_Config::getPermatanetUrl().'" >';
        if ($post != null) {
            foreach ($post as $name => $value) {
                $string .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
            }
        }
        $string .= '</form>';
        $string .= '<script>document.getElementById("BCAForm").submit();</script>';
        echo $string;
    }

    public static function redirect_to_bca($bcaDate,$trx_id,$bill_gross,$pay_type,$bill_desc,$return,$miscFee){
        
        $utils = new Utils();
        $keyId = $utils->genKeyId(Faspay_Config::$clearKey);
       
        $post = array(
            "klikPayCode"		=> Faspay_Config::$klikPayCode,
            "transactionDate"	=> $bcaDate,
            "transactionNo" 	=> $trx_id,
            "currency"			=> 'IDR',
            "totalAmount" 		=> $bill_gross,
            "payType"			=> $pay_type,
            "signature"			=> $utils->genSignature(Faspay_Config::$klikPayCode, $bcaDate, $trx_id, $bill_gross, 'IDR', $keyId),
            "descp"				=> $bill_desc,
            "callback"			=> $return,
            "miscFee"			=> $miscFee
        );

        $string = '<form method="post" name="form" id = "BCAForm" action="'.Faspay_Config::getBCAUrl().'" >';
        if ($post != null) {
            foreach ($post as $name => $value) {
                $string .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
            }
        }
        $string .= '</form>';
        $string .= '<script>document.getElementById("BCAForm").submit();</script>';
        echo $string;
    }

    public static function generateSignature($bill_no){
        $signature = sha1(md5(Faspay_Config::$bussinessUser.Faspay_Config::$bussinessPassword.$bill_no));

        return $signature;
    }

    /**
     * @param $params
     * @throws Exception
     */
    public static function validateParams($params){
        if(empty($params["bill_no"])){
            throw new Exception("bill_no cannot be empty", 1);
        }
        if(empty($params["bill_date"])){
            throw new Exception("bill_date cannot be empty", 1);
        }
        if(empty($params["bill_expired"])){
            throw new Exception("bill_expired cannot be empty", 1);
        }
        if(empty($params["bill_desc"])){
            throw new Exception("bill_desc cannot be empty", 1);
        }
        if(empty($params["bill_gross"])){
            throw new Exception("bill_gross cannot be empty", 1);
        }
        if(empty($params["bill_total"])){
            throw new Exception("bill_total cannot be empty", 1);
        }
        if(empty($params["payment_channel"])){
            throw new Exception("payment_channel cannot be empty", 1);
        }
        if(empty($params["pay_type"])){
            throw new Exception("pay_type cannot be empty", 1);
        }
        if(array_key_exists("item", $params)){
            if(!array_key_exists("product", $params["item"][0])
                || !array_key_exists("qty", $params["item"][0])
                || !array_key_exists("amount", $params["item"][0])){
                    throw new Exception("Please provide product details", 1);
            }
        } else if(array_key_exists("item", $params) && $params['payment_channel'] == '405'){
            if(!array_key_exists("product", $params["item"][0])
                || !array_key_exists("qty", $params["item"][0])
                || !array_key_exists("amount", $params["item"][0])
                || !array_key_exists("payment_plan", $params["item"][0])
                || !array_key_exists("merchant_id", $params["item"][0])
                || !array_key_exists("tenor", $params["item"][0])){
                    throw new Exception("Please provide product details", 1);
            }

        }
    }
}
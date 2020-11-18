<?php

/**
 * Created by PhpStorm.
 * User: gamaliens
 * Date: 1/27/16
 * Time: 10:37 AM
 */
class FaspayNotification
{
    private $response = array();

    public function __construct($reinquiry = FALSE, $input_source = "php://input"){
        set_error_handler(array($this, "response"));

        $notification = simplexml_load_string(file_get_contents($input_source));


        if($reinquiry){
            $status_response = Transaction::inquiry($notification->trx_id, $notification->bill_no);
            $this->response = $status_response;
        }else{
            $this->response = $notification;
        }

        if(!$this->validateSignature($notification)){
            $this->response(99, "Invalid Signature");
        }
		else if($this->validateSignature($notification)){
            $this->response("00", "Success");
        }
    }

    public function __get($name){
        if ($this->response && array_key_exists($name, $this->response)) {
            return $this->response->$name;
        }else{
            return "";
        }
    }

    /**
     * @param $notification
     * @return bool
     */
    private function validateSignature($notification){
        $correctSignature = sha1(md5(Faspay_Config::$bussinessUser.Faspay_Config::$bussinessPassword.$notification->bill_no.$notification->payment_status_code));
        if($correctSignature == $notification->signature){
            return TRUE;
        }

        return FALSE;
    }

    public function response($errno, $errmsg){
        $xml ="<faspay>";
        $xml.="<response>Payment Notification</response>";
        $xml.="<trx_id>".$this->__get('trx_id')."</trx_id>";
        $xml.="<merchant_id>".Faspay_Config::$bussinessId."</merchant_id>";
        $xml.="<bill_no>".$this->__get('bill_no')."</bill_no>";
        $xml.="<response_code>$errno</response_code>";
        $xml.="<response_desc>$errmsg</response_desc>";
        $xml.="<response_date>".Date("Y-m-d H:i:s")."</response_date>";
        $xml.="<reserve2></reserve2>";
        $xml.="</faspay>";

        echo $xml;
        exit;
    }
}
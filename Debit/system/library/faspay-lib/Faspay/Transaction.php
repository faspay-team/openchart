<?php

/**
 * Created by PhpStorm.
 * User: gamaliens
 * Date: 1/28/16
 * Time: 10:51 AM
 */
class Transaction
{
    public static function processDebitTransaction($loads){
        return Api::post(Faspay_Config::getUrl(), $loads);
    }

    public function inquiry($id, $bill_no){
        $loads = array(
            'request'       => 'Inquiry Status Payment',
            'trx_id'        => $id,
            'merchant_id'   => Faspay_Config::$bussinessId,
            'bill_no'       => $bill_no,
            'signature'     => sha1(md5(Faspay_Config::$bussinessUser.Faspay_Config::$bussinessPassword.$bill_no))
        );

        return Api::post(Faspay_Config::getInquiryUrl(),$loads);
    }

    public static function cancel($id, $bill_no, $reason){
        $loads = array(
            'request'       => 'Canceling Payment',
            'trx_id'        => $id,
            'merchant_id'   => Faspay_Config::$bussinessId,
            'merchant'      => Faspay_Config::$bussinessId,
            'bill_no'       => $bill_no,
            'payment cancel'=> $reason,
            'signature'     => sha1(md5(Faspay_Config::$bussinessUser.Faspay_Config::$bussinessPassword.$bill_no))
        );

        return Api::post($loads);
    }

    public static function void(){

    }

    public static function refund(){

    }
}
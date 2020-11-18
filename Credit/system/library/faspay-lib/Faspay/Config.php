<?php

Class Faspay_Config{

    public static $isProduction = false;

    public static $bussinessId;
    public static $bussinessUser;
    public static $bussinessPassword;

    public static $merchantId;
    public static $merchantPassword;

    public static $klikPayCode;
    public static $clearKey;
    public static $bcaReturnUrl;

    const SANDBOX_BASE_URL = 'https://dev.faspay.co.id/pws/300011/183xx00010100000';
    const PRODUCTION_BASE_URL = 'https://web.faspay.co.id/pws/300011/383xx00010100000';

    const SANDBOX_REDIRECT_URL = 'https://dev.faspay.co.id/pws/100003/0830000010100000/';
    const PRODUCTION_REDIRECT_URL = 'https://web.faspay.co.id/pws/100003/2830000010100000/';

    const SANDBOX_BCA_URL       = 'https://dev.faspay.co.id/bcaklikpay/purchasing';
    //const SANDBOX_BCA_URL       = 'https://202.6.215.230:8081/purchasing/purchase.do?action=loginRequest';
    const PRODUCTION_BCA_URL    = 'https://klikpay.klikbca.com/purchasing/purchase.do?action=loginRequest';

    const SANDBOX_INQUIRY_URL   = 'https://dev.faspay.co.id/pws/100004/183xx00010100000';
    const PRODUCTION_INQUIRY_URL= 'https://web.faspay.co.id/pws/100004/183xx00010100000';

    const SANDBOX_PERMATANET_URL = 'https://dev.faspay.co.id/permatanet/payment';
    const PRODUCTION_PERMATANET_URL = 'https://web.faspay.co.id/permatanet/payment';


    public static function getUrl(){
        return self::$isProduction ? Faspay_Config::PRODUCTION_BASE_URL : Faspay_Config::SANDBOX_BASE_URL;
    }

    public static function getRedirectUrl(){
        return self::$isProduction ? Faspay_Config::PRODUCTION_REDIRECT_URL : Faspay_Config::SANDBOX_REDIRECT_URL;
    }

    public static function getBCAUrl(){
        return self::$isProduction ? Faspay_Config::PRODUCTION_BCA_URL : Faspay_Config::SANDBOX_BCA_URL;
    }

    public static function getInquiryUrl(){
        return self::$isProduction ? Faspay_Config::PRODUCTION_INQUIRY_URL : Faspay_Config::SANDBOX_INQUIRY_URL;
    } 

    public static function getPermatanetUrl(){
        return self::$isProduction ? Faspay_Config::PRODUCTION_PERMATANET_URL : Faspay_Config::SANDBOX_PERMATANET_URL;    
    }
}

?>
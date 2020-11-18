<?php

/**
 * Created by PhpStorm.
 * User: gamaliens
 * Date: 1/28/16
 * Time: 4:09 PM
 */
class Utils
{
    public function genSignature($klikPayCode, $transactionDate, $transactionNo, $amount, $currency, $keyId) {

        $tempKey1 = $klikPayCode . $transactionNo . $currency . $keyId;
        $hashKey1 = $this->getHash($tempKey1);
        $expDate = explode("/",substr($transactionDate,0,10));

        $strDate = $this->intval32bits($expDate[0] . $expDate[1] . $expDate[2]);
        $amt = $this->intval32bits($amount);
        $tempKey2 = $strDate + $amt;
        $hashKey2 = $this->getHash((string)$tempKey2);

        $signature = abs($hashKey1 + $hashKey2);

        return $signature;
    }

    public function genKeyId($clearKey) {
        return strtoupper(bin2hex($this->str2bin($clearKey)));
    }

    public function genAuthKey($klikPayCode, $transactionNo, $currency, $transactionDate, $keyId) {

        $klikPayCode = str_pad($klikPayCode, 10, "0");
        $transactionNo = str_pad($transactionNo, 18, "A");
        $currency = str_pad($currency, 5, "1");

        $value_1 = $klikPayCode . $transactionNo . $currency . $transactionDate . $keyId;

        $hash_value_1 = strtoupper(md5($value_1));

        if (strlen($keyId) == 32)
            $key = $keyId . substr($keyId,0,16);
        else if (strlen($keyId) == 48)
            $key = $keyId;
        
        $keys=mcrypt_encrypt(MCRYPT_3DES,$this->hex2bin($key), $this->hex2bin($hash_value_1), MCRYPT_MODE_ECB);

        return strtoupper(bin2hex(mcrypt_encrypt(MCRYPT_3DES, hex2bin($key), hex2bin($hash_value_1), MCRYPT_MODE_ECB)));
    }

    private function hex2bin($data)
    {
        $len = strlen($data);
        return pack("H" . $len, $data);
    }
    public function str2bin($data) {
        $len = strlen($data);
        return pack("a" . $len, $data);
    }
    public function intval32bits($value) {
        if ($value > 2147483647)
            $value = ($value - 4294967296);
        else if ($value < -2147483648)
            $value = ($value + 4294967296);
        return $value;
    }

    public function getHash($value) {
        $h = 0;
        for ($i = 0;$i < strlen($value);$i++) {
            $h = $this->intval32bits($this->add31T($h) + ord($value{$i}));
        }
        return $h;
    }
    public function add31T($value) {
        $result = 0;
        for($i=1;$i <= 31;$i++) {
            $result = $this->intval32bits($result + $value);
        }
        return $result;
    }

    

    public function convertBcaDate($date){
        $newDate = DateTime::createFromFormat('Y-m-d H:i:s', $date);

        return $newDate->format('d/m/Y H:i:s');
    }

    
}
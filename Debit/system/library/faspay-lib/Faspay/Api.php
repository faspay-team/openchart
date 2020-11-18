<?php

Class Api{

    public static function post($url, $loads){
        $request = self::prepDataDebit($loads);
        return self::callServer($url, $request);
    }

    /**
     * @param $url
     * @param $user
     * @param $data
     * @param string $type
     */
    public static function callServer($url, $data, $type = "debit"){
 
        
        if($type = "xml") {
            $c = curl_init();
            $curl_options = array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array('Content-Type: application/xml'),
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_POST => TRUE,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => 1
            );
           
            if($data){
                 curl_setopt ($c, CURLOPT_POSTFIELDS, $data);
            }else {
                curl_setopt ($c, CURLOPT_POSTFIELDS, '');
            }

            curl_setopt_array($c, $curl_options);

            $result = curl_exec($c);
            
            
            if($result === FALSE){
                throw new Exception('CURL Error: ' . curl_error($c), curl_errno($c));
            }else{
                $result_object = simplexml_load_string($result);
                if (isset($result_object->response_error)) {
                    $error = $result_object->response_error;
                    $message = 'Faspay Error (' . $error->response_code . '): ' . $error->response_desc;

                    echo $message;
                    exit;

                    $ercode = (array) $error->response_code;

                    throw new Exception($message, $ercode[0]);
                } else {
                    return $result_object;
                }
            }
        }
    }

    /**
     * @param $params
     */
    public static function prepDataDebit($params){

        $body   = '<faspay>';
        
        foreach ($params as $param => $value) {
            if ($param == 'item') {
                
                foreach ($value as $key => $val) {
                    $body   .= '<item>';
                    foreach ($val as $kunci => $nilai) {
                        $body .= "<$kunci>".$nilai."</$kunci>";     
                    } 
                    $body   .= '</item>';
                }
                
            }else{
                $body .= "<$param>".$value."</$param>";
            }
            
        }
        $body  .= '</faspay>';

        return $body;
    }

}

?>
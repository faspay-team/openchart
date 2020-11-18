<?php 
class ModelExtensionPaymentFaspaycc extends Model {
	public function getMethod($address, $total) {
		$this->language->load('extension/payment/faspaycc');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('faspaycc_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('faspaycc_total') > 0 && $this->config->get('faspaycc_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('faspaycc_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	

		$currencies = array(
			'AUD',
			'CAD',
			'EUR',
			'GBP',
			'JPY',
			'USD',
			'NZD',
			'CHF',
			'HKD',
			'SGD',
			'SEK',
			'DKK',
			'PLN',
			'NOK',
			'HUF',
			'CZK',
			'ILS',
			'MXN',
			'MYR',
			'BRL',
			'PHP',
			'TWD',
			'THB',
			'TRY',
			'IDR'
		);
		
		if (!in_array(strtoupper($this->session->data['currency']), $currencies)) {
			$status = false;
		}	
					
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'faspaycc',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('faspaycc_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
	
	public function getUrl(){
		$this->load->model('setting/setting');
		
		$server		= "";
		$setting	= $this->model_setting_setting->getSetting('faspaycc', 0);
		
		$server		= $setting["faspaycc_server"];
		
		$rd = $server == 0 ?
			"https://fpgdev.faspay.co.id/payment" :
			"https://fpg.faspay.co.id/payment";
		
		return $rd;
	}
	
	private function getBaseUrl() {
		if(isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$base = $this->config->get('config_ssl');
		}else{
			$base = $this->config->get('config_url');
		}
		return $base;
	}
	
	private function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."order_product WHERE order_id = '" . (int)$order_id ."'");
		return $query->rows;
	}
	
	private function getShippingAmount($order_id){
		$shipping	= $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' and code='shipping'");
		$shipping	= $shipping->rows;
		$ship		= isset($shipping[0]['value']) ? $shipping[0]['value'] : "0";
		return $ship;
	}

	public function getPasswordMID($mid){
		
		$sql = "SELECT `key` FROM ".DB_PREFIX."setting WHERE `value` = '".$mid."' " ;
		$rsp = $this->db->query($sql);
		$rsp = $rsp->rows;

		$newquery = str_replace("id", "pwd", $rsp[0]['key']);

		$password 	= "SELECT `value` FROM ".DB_PREFIX."setting WHERE `key` = '".$newquery."' ";
		
		$passfix	= $this->db->query($password);
		$passfix	= $passfix->rows;
		$pass	= $passfix[0]['value'];

		// print_r($passfix);exit;
		return $pass;
	}

	private function string_sanitize($s) {
		$result	= str_replace(array('\'', '"'), '', $s);
		return $result;
	}

	public function getRedirectAction(){
		$lastOrderId	= $this->session->data['order_id'];
		//$message = 'Thank You For Using Faspay CC';
		//$this->model_checkout_order->addOrderHistory($lastOrderId,$this->config->get('faspaycc_order_status'),$message,true);
		$this->model_checkout_order->addOrderHistory($lastOrderId,$this->config->get('faspaycc_order_status'));

		$order_product	= $this->getOrderProducts($lastOrderId);
		$order_info		= $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$mid 					= $this->request->post['mid'];
		$pas 					= $this->request->post['pas'];
		$merchant  				= $this->request->post['merchant'];
		$amount					= $order_info["total"];
		$shipping				= $this->getShippingAmount($lastOrderId);
		$base_url				= $this->getBaseUrl();

		$signaturecc	= sha1('##'.strtoupper($mid).'##'.strtoupper($pas).'##'.$lastOrderId.'##'.number_format($amount,'2','.','').'##'.'0'.'##');
		$post = array(
			"LANG" 								=> '',
			"RESPONSE_TYPE"						=> '2' , //1
			"TRANSACTIONTYPE"					=> '1', //2

			"MERCHANTID" 						=> $mid,
			"MERCHANT_TRANID"					=> $lastOrderId,
			"PAYMENT_METHOD"					=> '1',
			"TXN_PASSWORD"						=> $pas,
			"CURRENCYCODE"						=> 'IDR',
			"AMOUNT"							=> number_format($amount,'2','.',''),
			"CUSTNAME"							=> $order_info["payment_firstname"]." ".$order_info['payment_lastname'],
			"CUSTEMAIL"							=> $order_info["email"],
			"DESCRIPTION"						=> 'Pembelian Barang di ('.$merchant.')',
			"RETURN_URL" 						=> $base_url.'index.php?route=extension/payment/faspaycc/thanks',
			"SIGNATURE" 						=> $signaturecc,
			"BILLING_ADDRESS"					=> $order_info["payment_address_1"],
			"BILLING_ADDRESS_CITY"				=> $order_info["payment_city"],
			"BILLING_ADDRESS_REGION"			=> $order_info["payment_zone"],
			"BILLING_ADDRESS_STATE"				=> $order_info["payment_country"],
			"BILLING_ADDRESS_POSCODE"			=> $order_info["payment_postcode"],
			"BILLING_ADDRESS_COUNTRY_CODE"		=> $order_info["payment_country_id"],
			"RECEIVER_NAME_FOR_SHIPPING"		=> '',
			"SHIPPING_ADDRESS" 					=> $order_info["shipping_address_1"],
			"SHIPPING_ADDRESS_CITY" 			=> $order_info["shipping_city"],
			"SHIPPING_ADDRESS_REGION"			=> $order_info["shipping_zone"],
			"SHIPPING_ADDRESS_STATE"			=> $order_info["shipping_country"],
			"SHIPPING_ADDRESS_POSCODE"			=> $order_info["shipping_postcode"],
			"SHIPPING_ADDRESS_COUNTRY_CODE"		=> $order_info["shipping_country_id"],
			"SHIPPINGCOST"						=> number_format($shipping,'2','.',''),
			"PHONE_NO" 							=> $order_info["telephone"],
			"MREF1"								=> '',
			"MREF2" 							=> '',
			"MREF3" 							=> '',
			"MREF4"								=> '',
			"MREF5"								=> '',
			"MREF6"								=> '',
			"MREF7"								=> '',
			"MREF8"								=> '',
			"MREF9"								=> '',
			"MREF10"							=> '',
			"MPARAM1" 							=> '',
			"MPARAM2" 							=> '',
			"CUSTOMER_REF"	 					=> '',
			"PYMT_IND"							=> '',
			"PYMT_CRITERIA"						=> '',
			"FRISK1"							=> '',
			"FRISK2"							=> '',
			"DOMICILE_ADDRESS"					=> '',
			"DOMICILE_ADDRESS_CITY"				=> '',
			"DOMICILE_ADDRESS_REGION"			=> '',
			"DOMICILE_ADDRESS_STATE"			=> '',
			"DOMICILE_ADDRESS_POSCODE" 			=> '',
			"DOMICILE_ADDRESS_COUNTRY_CODE"		=> '',
			"DOMICILE_PHONE_NO"	 				=> '',
			"handshake_url"						=> '',
			"handshake_param"					=> ''
		);
		 //print_r($post);exit;
		return $post;
	}
	
	public function resp_faspay($act, $data, $specific="") {
		$this->createLogFaspaytbl();
		
		switch($act) {
			case 'faspay_log':
				$sql = "INSERT INTO ".DB_PREFIX."order_payment_faspay_log(merchant_id, merchant_trx_id, trx_id, trx_status, trx_error_code, trx_error)
						values('".$data['MERCHANTID']."', ".$data['MERCHANT_TRANID'].", '".$data['TRANSACTIONID']."', '".$data['TXN_STATUS']."', '".$data['ERR_CODE']."','".$this->string_sanitize($data['ERR_DESC'])."')";
		
				if($specific!==""){
					$sql	= "INSERT INTO ".DB_PREFIX."order_payment_faspay_log(merchant_id, merchant_trx_id, trx_id, trx_status, trx_error_code, trx_error)
								values('".$data['MERCHANTID']."', ".$data['MERCHANT_TRANID'].", '".$data['TRANSACTIONID']."', '".$data['TXN_STATUS']."', '".$data['ERR_CODE']."','".$specific."')";
				}
				//echo $sql;exit;
				$this->db->query($sql);
				
				break;
		}
	}

	public function requery($data){
		$pass		= $this->getPasswordMID($data["MERCHANTID"]);
		$base_url	= $this->getBaseUrl();

		
		$sigcc	= sha1('##'.strtoupper($data["MERCHANTID"]).'##'.strtoupper($pass).'##'.$data["MERCHANT_TRANID"].'##'.$data["AMOUNT"].'##0##');

		$post = array(
			"TRANSACTIONTYPE"		=> '4',
			//"TRANSACTIONTYPE"		=> $data["TRANSACTIONTYPE"],
			"MERCHANTID" 			=> $data["MERCHANTID"],
			"MERCHANT_TRANID"		=> $data["MERCHANT_TRANID"],
			"AMOUNT"				=> $data["AMOUNT"],
			"RESPONSE_TYPE"			=> '3',
			"SIGNATURE"				=> $sigcc,
			"RETURN_URL"			=> $base_url.'index.php?route=extension/payment/faspaycc/thanks'
		);

		$a	= $this->inquiryThanks($post);
		//print_r($a);exit;
		return $a;
	}
	
	public function requeryVoid($data){
		$pass		= $this->getPasswordMID($data["MERCHANTID"]);
		$base_url	= $this->getBaseUrl();
		
		$sigvoid	= sha1('##'.strtoupper($data["MERCHANTID"]).'##'.strtoupper($pass).'##'.$data["MERCHANT_TRANID"].'##'.$data["AMOUNT"].'##'.$data["TRANSACTIONID"].'##');
		$post = array(
			"PAYMENT_METHOD"		=> '1',
			//"TRANSACTIONTYPE"		=> '10',
			"TRANSACTIONTYPE"		=> $data["TRANSACTIONTYPE"],
			"MERCHANTID"			=> $data["MERCHANTID"],
			"MERCHANT_TRANID"		=> $data["MERCHANT_TRANID"],
			"TRANSACTIONID"			=> $data["TRANSACTIONID"],
			"AMOUNT"				=> $data["AMOUNT"],
			"RETURN_URL"			=> $base_url.'index.php?route=extension/payment/faspaycc/thanks',
			"RESPONSE_TYPE"			=> '3',
			"SIGNATURE"				=> $sigvoid
		);
		$a	= $this->inquiryVoid($post);
		
		return $a;
	}
	
	private function inquiryThanks($post){
		$this->load->model('setting/setting');
		
		$setting	= $this->model_setting_setting->getSetting('faspaycc', 0);
		$server		= $setting['faspaycc_server'];

		
		$url 	= $server == 0 ? "https://cc1dev.mediaindonusa.com/payment/PaymentInterface.jsp" : 
									"https://cc1.mediaindonusa.com/payment/PaymentInterface.jsp";

		foreach($post as $key => $value){
			$post_items[] = $key . '=' . $value;
		}
		$postData = implode ('&', $post_items);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$result	= curl_exec($ch);
		curl_close($ch);
		
		$lines	= explode(';',$result);
		$result = array();
		foreach($lines as $line){
			list($key,$value) = array_pad(explode('=', $line, 2), 2, null);
			$result[trim($key)] = trim($value);			
		}

		//print_r($result);exit;
		
		return $result;
	}
	
	private function inquiryVoid($post){
		$this->load->model('setting/setting');
		
		$setting	= $this->model_setting_setting->getSetting('faspaycc', 0);
		$server		= $setting["faspaycc_server"];
		
		$url 	= $server == 0 ? "https://cc1dev.mediaindonusa.com/payment/PaymentInterface.jsp" : 
									"https://cc1.mediaindonusa.com/payment/PaymentInterface.jsp";
		
		foreach($post as $key => $value){
			$post_items[] = $key . '=' . $value;
		}
		$postData = implode ('&', $post_items);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$result	= curl_exec($ch);
		curl_close($ch);
		
		$lines	= explode(';',$result);
		$result = array();
		foreach($lines as $line){
			list($key,$value) = array_pad(explode('=', $line, 2), 2, null);
			$result[trim($key)] = trim($value);			
		}
		
		return $result;
	}
	
	public function createLogFaspaytbl() {
		$query = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."order_payment_faspay_log`  (
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`merchant_id` varchar(100) DEFAULT NULL,
					`merchant_trx_id` int(50) DEFAULT NULL,
					`trx_id` int(50) DEFAULT NULL,
					`trx_status` varchar(32) DEFAULT NULL,
					`trx_error_code` varchar(32) DEFAULT NULL,
					`trx_error` varchar(100) DEFAULT NULL,
					`trx_timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
				
		$this->db->query($query);
		return true;
	}
	
	public function dump($arg, $die=true) {
		if (is_string($arg) && preg_match("/xml/i", $arg)) {
			echo header("Content-type: application/xml");
			echo $arg;
		}
		else {
			echo "<br /><pre>";
			if(is_string($arg)) echo $arg;
			else print_r($arg);
			echo "</pre><br />";
		}
		if($die) die();
	}
}
?>
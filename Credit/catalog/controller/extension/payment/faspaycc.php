<?php

class ControllerExtensionPaymentFaspaycc extends Controller {
	public function index() {
		$this->load->language('extension/payment/faspaycc');
		$this->load->model('setting/setting');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/faspaycc');

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['action'] 		= $this->url->link('extension/payment/faspaycc/process_order');
		$data['setting']		= $this->model_setting_setting->getSetting('faspaycc', 0);
		
		$order_info				= $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {
			$data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');				
			
			$data['products'] = array();
			
				foreach ($this->cart->getProducts() as $product) {
					$option_data = array();
	
					foreach ($product['option'] as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];	
						} else {
							$filename = $this->encryption->decrypt($option['value']);
						
							$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
						}
										
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}

					$data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
						'quantity' => $product['quantity'],
						'option'   => $option_data,
						'weight'   => $product['weight']
					);
				}

		# COUPON
		if ( isset($this->session->data['coupon']) )
		{
		    $coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
		    
		    switch ( $coupon['type'] )
		    {
			case "F":
			    $coupon_amount = $coupon['discount'];
			break;
		    
			case "P":
			    $coupon_amount = ( $coupon['discount'] * $total ) / 100;
			break;
		    }
		    
		    $data['coupon'] =$coupon_amount;
		}else
		{
			$data['coupon']=0;
		}
		
		# VOUCHER
		if ( isset($this->session->data['voucher']) )
		{
		    $voucher = $this->model_checkout_voucher->getVoucher($this->session->data['voucher']);		    
		    $voucher_amount = $voucher['amount'];
		    
		    if ( !empty($voucher_amount) )
		    {
			$data['voucher']=$voucher_amount;
		    }
		}else
		{
			$data['voucher']=0;
		}
			
		}	
		//print_r($data);exit;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspaycc_default.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/faspaycc_default.tpl', $data);
		} else {
			return $this->load->view('default/template/extension/payment/faspaycc_default.tpl', $data);
		}
		
		$this->render();
	}	 

	public function process_order() {
		$this->load->language('extension/payment/faspaycc');
		$this->load->model('extension/payment/faspaycc');
		$this->load->model('checkout/order');
		$this->load->model('setting/setting');

		$this->document->setTitle("Faspay Redirect");
		$data['post']		= $this->model_extension_payment_faspaycc->getRedirectAction();
		$data['action']		= $this->model_extension_payment_faspaycc->getUrl();
		$data['message']	= $this->language->get('text_redirect');
		$data['button']		= $this->language->get('text_button');

				
		if(version_compare(VERSION, '2.2.0.0') < 0) {
	      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspaycc_send')) {
	        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspaycc_send', $data));
	      	} else {
	        $this->response->setOutput($this->load->view('default/template/extension/payment/faspaycc_send', $data));
	      	}
	    } else {
	      $this->response->setOutput($this->load->view('extension/payment/faspaycc_send', $data));
	    		}

		$this->template 		= 'extension/payment/faspaycc_send.tpl';
	    $data['header'] 		= $this->load->controller('common/header');
	    $data['column_left'] 	= $this->load->controller('common/column_left');
	    $data['footer'] 		= $this->load->controller('common/footer');
	    $data['text_edit'] 		= $this->language->get('text_edit');

	    $this->response->setOutput($this->load->view('extension/payment/faspaycc_send.tpl', $data));
		
		$this->cart->clear();		
	}

	public function autocallback(){
		$this->load->language('extension/payment/faspaycc');
		$this->load->model('setting/setting');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/faspaycc');

		$data = $_POST;
		if($data['TXN_STATUS']=="A" && $data['TXN_STATUS']=="C"){
			$this->model_checkout_order->addOrderHistory($data['MERCHANT_TRANID'],5,"Payment on Process",true);
		}elseif ($data['TXN_STATUS']=="F") {
			$this->model_checkout_order->addOrderHistory($data['MERCHANT_TRANID'],10,"Payment Failed",true);
		}else{
			$this->model_checkout_order->addOrderHistory($data['MERCHANT_TRANID'],1,"Payment on Process",true);
		}

	}
	
	public function thanks() {
		$this->load->language('extension/payment/faspaycc');
		$this->load->model('setting/setting');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/faspaycc');


		if (!isset($_POST)) {
			$this->response->redirect($this->url->link('error/not_found.php'));
			return;
		}
		
		$data = $_POST;
		// $this->model_extension_payment_faspaycc->dump($data); exit;

		if (!isset($data['MERCHANTID']) || !isset($data['MERCHANT_TRANID']) || !isset($data['AMOUNT']) || !isset($data['TXN_STATUS']) ) {
			$this->response->redirect($this->url->link('error/not_found.php'));
			return;
		}
		
		$lastOrderId	= $this->session->data['order_id'];
		if(!$lastOrderId){
			$msg	= "Opencart Transaction ID Empty";
			
			$order_status = $msg;
			$this->model_extension_payment_faspaycc->resp_faspay("faspay_log",$data,$msg);
			$this->model_checkout_order->addOrderHistory($lastOrderId, 1, $msg);
			$this->response->redirect($this->url->link('error/not_found.php'));
			return;
		}
		
		if ($lastOrderId != $data['MERCHANT_TRANID']) {
			$msg	= "Opencart Transaction ID Not Match";
			
			$order_status = $msg;
			$this->model_extension_payment_faspaycc->resp_faspay("faspay_log",$data,$msg);
			$this->model_checkout_order->addOrderHistory($lastOrderId, 1, $msg);
			$this->response->redirect($this->url->link('error/not_found.php'));
			return;
		}
		
		if ( empty($data['SIGNATURE']) ) {
			$msg	= 'No Signature Detected. Error Description: '.$data['ERR_DESC'];
			
			$this->model_extension_payment_faspaycc->resp_faspay("faspay_log",$data,$msg);
			$this->response->redirect($this->url->link('error/not_found.php'));
			return;			
		}
		
		$this->document->setTitle('Faspay Thanks');
		$data['order_id'] 			= $lastOrderId;
		$data['continue']			= $this->url->link('common/home');
		$data['button_continue']	= $this->language->get('button_continue');
		$this->model_extension_payment_faspaycc->resp_faspay("faspay_log",$data);
		$setting	= $this->model_setting_setting->getSetting('faspaycc', 0);
		$signature	= $data["SIGNATURE"];
		$void		= $this->config->get('faspaycc_autovoid');
		$pass		= $this->model_extension_payment_faspaycc->getPasswordMID($data['MERCHANTID']);

		// $print_r($pass);exit;

		$sigres		= strtoupper(sha1(strtoupper('##'.$data["MERCHANTID"].'##'.$pass.'##'.$data["MERCHANT_TRANID"].'##'.$data["AMOUNT"].'##'.$data["TXN_STATUS"].'##')));

		$success	= false;

		if( $data["TXN_STATUS"]=="F" ){
		 	// F = gagal
			
		 	$data['faspaycc_status'] = "FAILED";
			
		 	$msg	= "Payment Failed For Transaction ID : ".$data["MERCHANT_TRANID"];
		 	$this->model_checkout_order->addOrderHistory($data["MERCHANT_TRANID"], 10, $msg);
		 	
		 	goto fail;
		 }elseif(strtoupper($data["TXN_STATUS"])=="PENDING"){
		 	goto pending;
		 }

		
	if($signature==$sigres){
		if( $void == "on" && ( $data["TXN_STATUS"]=="C" || $data["TXN_STATUS"]=="S" ) && strtoupper($data["EXCEED_HIGH_RISK"])=="YES" ){
				// C = Capture , S = Sales
				
				$a 	= $this->model_extension_payment_faspaycc->requeryVoid($data);
				$this->model_extension_payment_faspaycc->resp_faspay("faspay_log",$a);
				//$this->model_extension_payment_faspaycc->dump($a);exit;
				
				if($a["TXN_STATUS"] == "V"){
				
					$data['faspaycc_status'] = "FAILED";
					
					$this->model_checkout_order->addOrderHistory($data["MERCHANT_TRANID"], $setting["faspaycc_void_status"],"Payment is Auto Void");
					$msg	= "Payment Refused For Transaction ID : ".$data["MERCHANT_TRANID"];
					
					goto fail;
				}else $success=true;
		
		}elseif( $data["TXN_STATUS"]=="A" && strtoupper($data["EXCEED_HIGH_RISK"])=="NO" ){
				// A = pending
				
				$a 	= $this->model_extension_payment_faspaycc->requery($data);
				//$this->model_extension_payment_faspaycc->dump($a);exit;
				$this->model_extension_payment_faspaycc->resp_faspay("faspay_log",$a);
				
				if($a["TXN_STATUS"] == "A" || $a["TXN_STATUS"] == "CR"){
					goto pending;
				}else $success=true;
				
		}elseif( $data["TXN_STATUS"]=="A" && strtoupper($data["EXCEED_HIGH_RISK"])=="YES" ){
				pending:
				$data['faspaycc_status'] = "PENDING";
				$msg	= "Payment Pending For Transaction ID : ".$data["MERCHANT_TRANID"];
				$this->model_checkout_order->addOrderHistory($data["MERCHANT_TRANID"], 1, $msg);
				
				goto fail;

		}elseif($data["TXN_STATUS"]=="S" || $data["TXN_STATUS"]=="C") $success=true;
		
		//condition	
		if($success==true){
			$data['faspaycc_status'] = "SUCCESS";
			$this->model_checkout_order->addOrderHistory($data["MERCHANT_TRANID"], $setting["faspaycc_after_payment_status"], "Faspay has processed the payment.");
		}else{
			$data['faspaycc_status'] = "FAILED";
			$msg	= "Faspay failed to process the payment";
			$this->model_checkout_order->addOrderHistory($data["MERCHANT_TRANID"], 1, $msg);
			goto fail;
		}
	}else{
			$data['faspaycc_status'] = "PENDING";
			$msg	= "Signature Not Match. Error Description: ".$data["ERR_DESC"];
			$this->model_extension_payment_faspaycc->resp_faspay("faspay_log",$data,$msg);
			$this->model_checkout_order->addOrderHistory($data["MERCHANT_TRANID"], 1, $msg);
			$msg	= "Payment Failed For Transaction ID : ".$data["MERCHANT_TRANID"];
			fail:
			
			$data["errorMessage"]	= $msg;
	}
		// //fail
		// $msg	= "Payment Failed For Transaction ID : ".$data["MERCHANT_TRANID"];
		// 	fail:
		// $data["errorMessage"]	= $msg;

		$_POST = array();
		unset($_POST);
		
		$route = empty($this->request->get['route']) ? 'common/home' : $this->request->get['route'];
		$css_file = str_replace('/', '_', $route) . '.css';

		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/' . $css_file)) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template'). '/stylesheet/' . $css_file);
		}
	
		if(version_compare(VERSION, '2.2.0.0') < 0) {
	      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspaycc_thanks')) {
	        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspaycc_thanks', $data));
	      	} else {
	        $this->response->setOutput($this->load->view('default/template/extension/payment/faspaycc_thanks', $data));
	      	}
	    } else {
	      $this->response->setOutput($this->load->view('extension/payment/faspaycc_thanks', $data));
	    		}

		$this->template = 'extension/payment/faspaycc_thanks.tpl';
	    $data['header'] 		= $this->load->controller('common/header');
	    $data['column_left'] 	= $this->load->controller('common/column_left');
	    $data['column_right'] 	= $this->load->controller('common/column_right');
	    $data['footer'] 		= $this->load->controller('common/footer');
	    $data['content_top'] 	= $this->load->controller('content_top');
	    $data['content_bottom'] = $this->load->controller('content_bottom');
	    $data['text_edit'] 		= $this->language->get('text_edit');

	    $this->response->setOutput($this->load->view('extension/payment/faspaycc_thanks.tpl', $data));
	}
	
	private function _encrypt($decrypted) {
		$key = hash('SHA256', '!kQm*fF3pXe1Kbm%9' . '*!nD0n3s5!4#', true);
		srand();
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
		if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
		return $iv_base64 . $encrypted;
	}
	
	private function _decrypt($encrypted) {
		$key = hash('SHA256', '!kQm*fF3pXe1Kbm%9' . '*!nD0n3s5!4#', true);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
		$encrypted = substr($encrypted, 22);
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
		$hash = substr($decrypted, -32);
		$decrypted = substr($decrypted, 0, -32);
		if (md5($decrypted) != $hash) return false;
		return $decrypted;
	}
}
?>
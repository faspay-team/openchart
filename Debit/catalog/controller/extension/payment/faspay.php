<?php

require_once(DIR_SYSTEM . 'library/faspay-lib/Faspay.php');
require_once(DIR_SYSTEM . 'library/mail.php');

class ControllerExtensionPaymentFaspay extends Controller
{
	public function index(){
		$this->load->language('extension/payment/faspay');
		$this->load->model('setting/setting');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['action'] = $this->url->link('extension/payment/faspay/process_order');
		$data['setting'] = $this->model_setting_setting->getSetting('faspay', 0);


		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

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
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_default.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_default.tpl', $data);
		} else {
			return $this->load->view('default/template/extension/payment/faspay_default.tpl', $data);
		}
		
		$this->render();

	}
	public function process_order () {
		$this->load->model('extension/payment/faspay');
		$this->load->language('extension/payment/faspay');
		$this->load->model('setting/setting');
		$this->load->model('checkout/order');

		$currency='IDR';

		// $shipping_total = 0;

		// if ($this->cart->hasShipping()) {
			$shipping_total = isset($this->session->data['shipping_method']['cost']) ?$this->session->data['shipping_method']['cost']: 0;
		// }

		$order_info = $this->model_checkout_order->getOrder($this->request->post['order_id']);

		$order_product = $this->model_extension_payment_faspay->getOrderProducts($this->request->post['order_id']);

		$shipping = $this->currency->format($shipping_total, $currency, false, false);

		// $message = 'Payment On Process';
		// $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('faspay_order_status_id'),$message,true);

		// $this->cart->clear();
		$setting = $this->model_setting_setting->getSetting('faspay', 0);
		
		$order_exp	= date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($order_info['date_modified'])) . " +".$this->request->post['order_expired']." hour"));
		
		

		// //email backup

		// $mail = new Mail();
		// $mail->protocol = $this->config->get('config_mail_protocol');
		// $mail->parameter = $this->config->get('config_mail_parameter');
		// $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		// $mail->smtp_username = $this->config->get('config_mail_smtp_username');
		// $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		// $mail->smtp_port = $this->config->get('config_mail_smtp_port');
		// $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		// $mail->setTo($order_info['email']);
		// $mail->setFrom($this->config->get('config_email'));
		// $mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
		// $mail->setSubject("Thank You For Shopping!");
		// $mail->setHtml($this->load->view('extension/payment/templatemail'));
		// $mail->setText("Jancuk");
		// //print_r($mail);exit;

		// $mail->send();



		if ($this->request->post['server'] == 0) {
			Faspay_Config::$isProduction = FALSE;
		}else {
			Faspay_Config::$isProduction = TRUE;	
		}
		
		Faspay_Config::$bussinessId 		= $this->config->get('faspay_merchant_id');
		Faspay_Config::$bussinessUser	 	= $this->config->get('faspay_user_id');
		Faspay_Config::$bussinessPassword 	= $this->config->get('faspay_user_pwd');
		

		$srv = $this->config->get('config_url');
		$bill_gross = $order_info['total'] - $shipping;

		if ($this->request->post['gateway'] == 'permatanet'){
			$payment_channel = '402';
		}else{
			$payment_channel = $this->request->post['gateway'];
		}

		if ($this->request->post['gateway'] == 702) {
			$reserve1 = isset($setting['faspay_free_text1']) ? $setting['faspay_free_text1'] : '';
			$reserve2 = isset($setting['faspay_free_text2']) ? $setting['faspay_free_text2'] : '';
		}elseif($this->request->post['gateway'] == 709){
			$reserve1 = '';
			$reserve2 = '30_days';
		}else{
			$reserve1 = '';
			$reserve2 = '';
		}

		if($order_info['payment_firstname'])
			{	
				$address1  = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');	
				$address2  = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');	
				$billing_address = $address1;
				$billing_address_city = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
				$billing_address_region=$order_info['payment_zone'];
				$this->load->model('localisation/zone');
				$zone = $this->model_localisation_zone->getZone($order_info['payment_zone_id']);
				
				if (isset($zone['code'])) {
				$billing_state = html_entity_decode($zone['code'], ENT_QUOTES, 'UTF-8');
				}
				$billing_tel = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');		
				$billing_zip = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
				
				$billing_country_iso_code_2 = $order_info['payment_iso_code_2'];
				$billing_country_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "country where iso_code_2='".$billing_country_iso_code_2."'");
				$billing_country_name = $billing_country_query->row['name'];
				$billing_country = $billing_country_name;
				
			}
		if($order_info['shipping_firstname'])
			{
				$customer_firstname =  html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');	
				$customer_lastname  =  html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');	
				$delivery_name 		= $customer_firstname." ".$customer_lastname;
				$address1  = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');	
				$address2  = html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');	
				$shipping_address = $address1." ".$address2;
				$shipping_address_city=$order_info['shipping_city'];
				$shipping_address_postcode=$order_info['shipping_postcode'];
				$shipping_address_region=$order_info['shipping_zone'];
				$this->load->model('localisation/zone');
				$zone = $this->model_localisation_zone->getZone($order_info['shipping_zone_id']);
				if (isset($zone['code'])) {
				$shipping_cust_state = html_entity_decode($zone['code'], ENT_QUOTES, 'UTF-8');
				}
			} else {
				$customer_firstname =  html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');	
				$customer_lastname  =  html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');	
				$delivery_name 		= $customer_firstname." ".$customer_lastname;
				$shipping_address = $billing_address;
				$shipping_address_city = $billing_address_city;
				$shipping_address_region = $billing_address_region;
				$shipping_cust_state = $billing_state;
				$shipping_address_postcode = $billing_zip;


			}
		
		$params = array(
		        'merchant' 		=> $this->config->get('faspay_merchant_name'),
		        'merchant_id' 	=> $this->config->get('faspay_merchant_id'),
		        'bill_no' 		=> $order_info['order_id'],
		        'bill_date' 	=> $order_info['date_modified'],
		        'bill_expired' 	=> $order_exp,
		        'bill_desc' 	=> 'Pembelian online di '.$this->config->get('faspay_merchant_name'),        
		        'bill_gross' 	=> $bill_gross*100,
		        'bill_tax'		=> $order_product[0]['tax']*100,
		        'bill_miscfee' 	=> $shipping*100,
		        'bill_total' 	=> $order_info['total']*100,
		        'cust_no' 		=> $order_info['customer_id'],
		        'cust_name' 	=> $order_info['payment_firstname'].' '.$order_info['payment_lastname'],
		        'payment_channel' 	=> $payment_channel,
		        'msisdn' 			=> $order_info['telephone'],
		        'email' 			=> $order_info['email'],
		        'billing_address' 			=> $billing_address,
		        'billing_address_city' 		=> $billing_address_city,
		        'billing_address_region' 	=> $billing_address_region,
		        'billing_address_state' 	=> $billing_state,
		        'billing_address_poscode' 	=> $billing_zip,
		        'billing_address_country_code' 	=> $billing_country_iso_code_2,
		        'receiver_name_for_shipping' 	=> $delivery_name,
		        'shipping_address' 			=> $shipping_address,
		        'shipping_address_city'	 	=> $shipping_address_city,
		        'shipping_address_region' 	=> $shipping_address_region,
		        'shipping_address_state' 	=> $shipping_cust_state,
		        'shipping_address_poscode' 	=> $shipping_address_postcode,
				'billing_lastname'		=> $customer_lastname,
		        'pay_type'  => '1',
		        'reserve1' 	=> $reserve1,
		        'reserve2' 	=> $reserve2
				);

		if(in_array($payment_channel, ['807', '709']))
			{
				$params['shipping_msisdn'] = $order_info['telephone'];
			}


		$_SESSION['bill_date'] 		= $params['bill_date'];
		$_SESSION['bill_desc'] 		= $params['bill_desc'];
		$_SESSION['bill_gross'] 	= $params['bill_gross'];
		$_SESSION['bill_total'] 	= $params['bill_total'];
		$_SESSION['bill_miscfee'] 	= $params['bill_miscfee'];

		$indexProd = 0;
		$statusPayType = 1;
		$indexStatus = 1;
		$index = 0;
		$last = 0;
		$countercicilan = 0;

		$this->request->post['payment_tenor_0'] = '00';
		$this->request->post['payment_tenor_1'] = '00';
		$this->request->post['payment_tenor_2'] = '00';
		$this->request->post['payment_tenor_3'] = '00';
		$this->request->post['payment_tenor_4'] = '00';
		$this->request->post['payment_tenor_5'] = '00';
		$this->request->post['payment_tenor_6'] = '00';


		foreach($order_product as $key => $val) {
			if($index == 0){
				if($this->request->post['payment_tenor_'.$index] == '00'){
					$statusPayType = 1;
					$last = 1;
				}else{
					$statusPayType = 2;
					$last = 2;
					$countercicilan++;
				}
			}else{
				if($this->request->post['payment_tenor_'.$index] == '00'){
					$statusPayType = 1;
				}else{
					$statusPayType = 2;
					$countercicilan++;
				}
			}

		if($last != $statusPayType){
			$last = 3;
		}
		$index++;
		}

		if($last == 1){
			$params['pay_type'] = '01';
			$_SESSION['pay_type'] = '01';
		}elseif($last == 2 ){
			$params['pay_type'] = '02';
			$_SESSION['pay_type'] = '02';			
		}else{			
			  if(isset($setting['faspay_bcaklikpay_statmid_mix']) == 'on'){//1
				  $params['pay_type'] = '03';
				  $_SESSION['pay_type'] = '03';
			 }else{
				  echo "<script>alert('Sorry, this item not allow for combination transaction method!');</script>";
				  echo "<script language='javascript'>window.location ='$srv"."index.php?route=checkout/checkout'</script>";		 
			  }			
		}

		foreach($order_product as $key => $value) {
			$id = $value['product_id'];
			$price=($value['price']+$value['tax'])*100;
			$qty=$value['quantity'];
			$prices=$price * $qty;
			$descitem = str_replace("&","-", $value['name']);

			$params['item'][$key]['id'] = $value['product_id'];
			$params['item'][$key]['product'] = $descitem;
			$params['item'][$key]['qty'] = $value['quantity'];
			$params['item'][$key]['amount'] = $price;
			$params['item'][$key]['tenor'] = $this->request->post['payment_tenor_'.$indexProd];

			if( $this->request->post['payment_tenor_'.$indexProd] == '00'){
				$params['item'][$key]['payment_plan'] = '01';
			}else{
				$params['item'][$key]['payment_plan'] = '02';
			}

			
			if($this->request->post['gateway'] == 405){
				if($this->request->post['payment_tenor_'.$indexProd] == '03'){
					$params['item'][$key]['merchant_id'] = $this->config->get('faspay_bcaklikpay_mid_3');
				}elseif($this->request->post['payment_tenor_'.$indexProd] == '06'){
					$params['item'][$key]['merchant_id'] = $this->config->get('faspay_bcaklikpay_mid_6');
				}elseif($this->request->post['payment_tenor_'.$indexProd] == '12'){
					$params['item'][$key]['merchant_id'] = $this->config->get('faspay_bcaklikpay_mid_12');
				}elseif($this->request->post['payment_tenor_'.$indexProd] == '24'){
					$params['item'][$key]['merchant_id'] = $this->config->get('faspay_bcaklikpay_mid_24');				
				}else{
					$params['item'][$key]['merchant_id'] = $this->config->get('faspay_bcaklikpay_mid_full');					
				}
			}else{
				if(!in_array($payment_channel, array('709'))){

					$params['item'][$key]['merchant_id'] = '';				
				}			
			}
			
			$indexProd++;
		}

		if(!in_array($payment_channel, array('709'))){
			$params['item'][$key]['reserve1'] = $reserve1;
			$params['item'][$key]['reserve2'] = $reserve2;
		}

		if($countercicilan>5) {
			
			echo "<script> alert('Pembelian dengan Cicilan Tidak Bisa Lebih dari 5 Jenis Barang,Silahkan kurangi barang belanja anda');</script>";
			echo "<script language='javascript'>window.location ='$srv"."index.php?route=checkout/checkout'</script>";			
			exit;
			
		}

		// die(var_dump('<pre>',$order_info, $params, $this->request->post['gateway']));
		$response_xml = FaspayBussiness::postXmlToFaspay($params);
		// exit;

		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('faspay_order_status_id'),$response_xml->trx_id,true);

		
	if ($response_xml->response_code == '00') {
		$this->cart->clear();
		$this->model_extension_payment_faspay->insertTrx($order_info['order_id'],$response_xml->trx_id,$this->request->post['gateway'], "On Process");
	if ($this->request->post['flow'] == 1 ){
		if ($this->request->post['gateway'] == '405'){
			if($this->request->post['server'] == 0 ){
				Faspay_Config::$isProduction = FALSE;
			}else{
				Faspay_Config::$isProduction = TRUE;
			}

			Faspay_Config::$klikPayCode = $this->config->get('faspay_bcaklikpay_code');
			Faspay_Config::$clearKey 	= $this->config->get('faspay_bcaklikpay_clearkey');
			$srv = $this->config->get('config_url');

			$currency='IDR';
			$bill_gross = number_format($_SESSION['bill_gross']/100.00, 2, ".", "");
			$bill_miscfee = number_format($_SESSION['bill_miscfee']/100.00, 2, ".", ""); 	
			$bcaDate	= date("d/m/Y H:i:s", strtotime($_SESSION['bill_date'])); 
			$return = $srv."index.php?route=extension/payment/faspay/callback&trx_id=".$response_xml->trx_id;

			FaspayBussiness::redirect_to_bca($bcaDate,$response_xml->trx_id,$bill_gross,$_SESSION['pay_type'],$_SESSION['bill_desc'],$return,$bill_miscfee);

		}elseif ($this->request->post['gateway'] == '402'){
			if ($this->request->post['server'] == 0 ) {
			 		Faspay_Config::$isProduction = FALSE;
			 		}else {
			 		Faspay_Config::$isProduction = TRUE;	
			 		}

			 $bill_total = number_format($_SESSION['bill_total']/100.00, 2, ".", "");
			 FaspayBussiness::redirect2PermataNet($response_xml->trx_id,$bill_total);

		}else{
			if ($this->request->post['server'] == 0 ) {
			 		Faspay_Config::$isProduction = FALSE;
			 		}else {
			 		Faspay_Config::$isProduction = TRUE;	
			 		}

			 $bill_total = number_format($_SESSION['bill_total']/100.00, 2, ".", "");

			FaspayBussiness::redirect2Faspay($response_xml);
			 }

	}else{
		$data['trx_id'] 	= $response_xml->trx_id;
		$data['bill_no'] 	= $response_xml->bill_no;
		$data['product'] 	= $response_xml->bill_items->product;
		$data['qty'] 		= $response_xml->bill_items->qty;

		$data['price'] = number_format($this->model_extension_payment_faspay->getSubtotalAmount($this->session->data['order_id'])/100.00, 2, ".", "");
		$data['shipping'] = number_format($this->model_extension_payment_faspay->getShippingAmount($this->session->data['order_id'])/100.00, 2, ".", "");
		$data['total']	= number_format($this->model_extension_payment_faspay->getTotalAmount($this->session->data['order_id'])/100.00, 2, ".", "");

		$data['faspay_name'] 	= $this->config->get('faspay_merchant_name');
		$data['expired_order']	= $this->request->post['order_expired'];
		$data['merchant_id'] 	= $this->config->get('faspay_merchant_id');

		$data['continue']			= $this->url->link('common/home');
		$this->document->setTitle($this->language->get('heading_title'));

		if($this->request->post['gateway']=='303'){

			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
				$this->template = 'extension/payment/faspay_all.tpl';
		    	$data['header'] = $this->load->controller('common/header');
		    	$data['column_left'] = $this->load->controller('common/column_left');
		    	$data['column_right'] = $this->load->controller('common/column_right');
		    	$data['content_top'] = $this->load->controller('common/column_top');
		    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
		    	$data['footer'] = $this->load->controller('common/footer');
		    	$data['text_edit'] = $this->language->get('text_edit');

		    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
	    			}
		}
		elseif($this->request->post['gateway']=='400'){
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}
		elseif($this->request->post['gateway']=='402') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}

		}
		elseif($this->request->post['gateway']=='408') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}
		elseif($this->request->post['gateway']=='702') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_vabca')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_vabca', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

		    	// die(var_dump('<pre>',$data));
	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}
		elseif($this->request->post['gateway']=='703') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}
		elseif($this->request->post['gateway']=='706') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
	    				}
		}
		elseif($this->request->post['gateway']=='707') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}

		elseif($this->request->post['gateway']=='708') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}
		elseif($this->request->post['gateway']=='800') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}
		elseif($this->request->post['gateway']=='801') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
		}
		elseif($this->request->post['gateway']=='802') {
			if(version_compare(VERSION, '2.2.0.0') < 0) {
		      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_all')) {
		        $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_all', $data));
		      	} else {
		        $this->response->setOutput($this->load->view('default/template/extension/payment/faspay_all', $data));
		      	}
		    } else {
			$this->template = 'extension/payment/faspay_all.tpl';
	    	$data['header'] = $this->load->controller('common/header');
	    	$data['column_left'] = $this->load->controller('common/column_left');
	    	$data['column_right'] = $this->load->controller('common/column_right');
	    	$data['content_top'] = $this->load->controller('common/column_top');
	    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
	    	$data['footer'] = $this->load->controller('common/footer');
	    	$data['text_edit'] = $this->language->get('text_edit');
	    	$data['payment_channel'] = $this->request->post['gateway'];

	    	$this->response->setOutput($this->load->view('extension/payment/faspay_all.tpl', $data));
    				}
			}

		}
	}
}
public function notify() {

		$this->load->model('setting/setting');
		$this->load->model('checkout/order');
		
		$setting = $this->model_setting_setting->getSetting('faspay', 0);

		if ($setting['faspay_server'] == 0 ) {
			Faspay_Config::$isProduction = FALSE;
		}else {
			Faspay_Config::$isProduction = TRUE;	
		}
		
		Faspay_Config::$bussinessId 		= $setting['faspay_merchant_id'];
		Faspay_Config::$bussinessUser 		= $setting['faspay_user_id'];
		Faspay_Config::$bussinessPassword 	= $setting['faspay_user_pwd'];

		$notif 		= new FaspayNotification();
		$order_info = $this->model_checkout_order->getOrder($notif->bill_no);

		if($notif->payment_status_code == 2 )
		{
		    // TODO Set payment status in merchant's database to 'success'
		    
		    if ($order_info['order_status_id'] != $this->config->get('faspay_after_payment_status_id')) {

		    	$this->load->model('checkout/order');
		    	$this->load->model('extension/payment/faspay');
		    	$this->model_checkout_order->addOrderHistory($notif->bill_no, $this->config->get('faspay_after_payment_status_id'), "Faspay has processed the payment.",true);

		    	if ($notif->payment_reff){
		    		$payment_reff = $notif->payment_reff;
		    	}else {
		    		$payment_reff = null;
		    	}
		    	
		    	$this->model_extension_payment_faspay->updateTrx($notif->payment_status_desc,$notif->payment_date,$payment_reff, $notif->trx_id);
		    	$response = $notif->response('00','Success');			


		    	return $response;
		    }else {
		    	$response = $notif->response('07','Transaction Already Paid');
		    	return $response;
		    }
		    
		}else
		{
		    // TODO Set payment status in merchant's database to failed'
		}

	
	}
	

	public function bca_check(){	
		$transactionNo 	= isset($this->request->get['trx_id']) ? $this->request->get['trx_id'] : '';
		$signature		= isset($this->request->get['signature']) ? $this->request->get['signature'] : '';
		$authkey 		= isset($this->request->get['authkey']) ? $this->request->get['authkey'] : '';

		$this->load->model('setting/setting');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/faspay');

		$setting = $this->model_setting_setting->getSetting('faspay', 0);

		if ($setting['faspay_server'] == 0 ) {
			Faspay_Config::$isProduction = FALSE;
		}else {
			Faspay_Config::$isProduction = TRUE;	
		}
		
		Faspay_Config::$klikPayCode = $this->config->get('faspay_bcaklikpay_code');
		Faspay_Config::$clearKey 	= $this->config->get('faspay_bcaklikpay_clearkey');

		$order_info = $this->model_checkout_order->getOrder($this->model_extension_payment_faspay->getOrderId($transactionNo));
		$orders_amount = $this->model_extension_payment_faspay->getOrderValue($order_info['order_id']);

		$currency='IDR';
		$sub_total 	= $this->currency->format($orders_amount[0]['sub_total'], $currency, false, false);
		$total 		= $this->currency->format($orders_amount[2]['total'], $currency, false, false);
		$shipping 	= $this->currency->format($orders_amount[1]['shipping'], $currency, false, false);
		
		$bcaDate	= date("d/m/Y H:i:s", strtotime($order_info['date_modified']));

		$utils = new Utils();
		$keyid = $utils->genKeyId(Faspay_Config::$clearKey);
   	
		if ($transactionNo && $signature) {
			$sig        = $utils->genSignature(Faspay_Config::$klikPayCode,$bcaDate, $transactionNo, $sub_total, 'IDR', $keyid);
			if ($sig == $signature){
				echo '1';
			}else {
				echo '0';
			}
		}elseif ($transactionNo && $authkey) {
			$authkey    = $utils->genAuthKey(Faspay_Config::$klikPayCode, $transactionNo, 'IDR', $bcaDate, $keyid);
			if ($authkey == $authkey){
				echo '1';
			}else{
				echo '0';
			}
		}

	}
	public function callback(){
		$this->cart->clear();
		$this->load->model('setting/setting');
		$this->document->setTitle("Confirmation");
		$setting = $this->model_setting_setting->getSetting('faspay', 0);

		if ($setting['faspay_server'] == 0 ) {
			Faspay_Config::$isProduction = FALSE;
		}else {
			Faspay_Config::$isProduction = TRUE;	
		}
		
		Faspay_Config::$bussinessId 		= $setting['faspay_merchant_id'];
		Faspay_Config::$bussinessUser 		= $setting['faspay_user_id'];
		Faspay_Config::$bussinessPassword 	= $setting['faspay_user_pwd'];

		$this->load->model('extension/payment/faspay');
		$transactionNo 	= isset($this->request->get['trx_uid']) ? $this->request->get['trx_uid'] : $this->request->get['trx_id'];

		$orders = $this->model_extension_payment_faspay->getOrderId($transactionNo);
		$transaction = New Transaction();
		
		$status = $transaction->inquiry($transactionNo,$orders);

		if($status->payment_status_code == '2'){
			$this->load->model('checkout/order');

			$data['heading_title'] = 'Thank You';
			$data['text_message']= '<h2><i><center>"Pembayaran Anda Untuk Nomor Transaksi '.$transactionNo.'<br> Sukses Terima Kasih Telah Berbelanja di '.$setting['faspay_merchant_name'].'" </center></i></h2>';

			// $this->load->model('checkout/order');
		 //    $this->load->model('extension/payment/faspay');
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('faspay_after_payment_status_id'),"Faspay has process the payment", true);

		    // $this->model_checkout_order->addOrderHistory($setting['faspay_after_payment_status_id'], "Faspay has processed the payment.",true);

		}else{	
			$data['heading_title'] = 'Sorry';
			$data['text_message']= '<h2><i><center>" Mohon Maaf Pembayaran Anda untuk Nomor Transaksi '.$transactionNo.' Gagal"</center></i></h2>';
		}

		$data['breadcrumbs'] = array(); 

      	$data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 
    	
		$data['button_continue'] = $this->language->get('button_continue');

    	$data['continue'] = $this->url->link('common/home');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/extension/payment/faspay_success.tpl';
		} else {
			$this->template = 'default/template/extension/payment/faspay_success.tpl';
		}

		$this->template = 'extension/payment/faspay_success.tpl';
    	$data['header'] = $this->load->controller('common/header');
    	$data['column_left'] = $this->load->controller('common/column_left');
    	$data['column_right'] = $this->load->controller('common/column_right');
    	$data['content_top'] = $this->load->controller('common/column_top');
    	$data['content_bottom'] = $this->load->controller('common/column_bottom');
    	$data['footer'] = $this->load->controller('common/footer');
    	$data['text_edit'] = $this->language->get('text_edit');

    	$this->response->setOutput($this->load->view('extension/payment/faspay_success.tpl', $data));

	}
}
?>
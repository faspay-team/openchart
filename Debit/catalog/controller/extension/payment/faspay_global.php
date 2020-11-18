<?php

class ControllerExtensionPaymentFaspay extends Controller
{
	private $pglabel = array(
		'faspay_tcash' => "Telkomsel Tcash",
		'faspay_xltunai' => "XL Tunai",
		'faspay_sakuku' => "BCA Sakuku",
		'faspay_indomaret' => "Indomaret"
	);

	private $gateway = array(
		'faspay_tcash' => 302,
		'faspay_xltunai' => 303,
		'faspay_sakuku'	=> 704,
		'faspay_indomaret' => 706,
	);

	public function index(){
		$this->load->language('extension/payment/faspay');
		$this->load->model('setting/setting');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['action'] = $this->url->link('extension/payment/faspay/process_order');
		$data['setting'] = $this->model_setting_setting->getSetting('faspay', 0);
		$data['gateway'] = $this->language->get('pglist_gateway');

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

	function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."order_product WHERE order_id = '" . (int)$order_id ."'");
		return $query->rows;
	}
	private function simpleXor($string, $password) {
		
	}

	public function process_order(){
		$currency='IDR';

		$shipping_total = 0;

		if ($this->cart->hasShipping()) {
			$shipping_total = $this->session->data['shipping_method']['cost'];
		}

		$shipping = $this->currency->format($shipping_total, $currency, false, false);

		$payment_gateways = $this->request->post['gateway'];
		$payment_channel = $this->gateway[$payment_gateways];
		$payment_channel_label = $this->pglabel[$payment_gateways];
		$this->_update_payment_channel($payment_channel, $payment_channel_label, $this->session->data['order_id']);

		$order_info = $this->model_checkout_order->getOrder($this->request->post['order_id']);
		$order_product = $this->model_extension_payment_faspay->getOrderProducts($this->request->post['order_id']);

		$indexTemp = 0;
		foreach ($order_product as $k => $v){
			$test = 'payment_tenor_'.$indexTemp;
		}

		$ack = $this->_xml2array($this->_post_data($order_info, $order_product, $shipping, $payment_channel, $setting));
		
		if($ack['response_code']== 00){
			$data['status'] = 'ok';
		}else{
			$data['status'] = 'failed';
		}

		$rsp = array_merge(array('order_id'=>$order_info['order_id'], 'payment_channel'=>$payment_channel), $ack);

		$this->_resp_faspay('post_data', $rsp);
		$sg = $this->_bcakp_signature($setting, $order_info);
		$qs = 'trx_id='.$rsp['trx_id'].'&merchant_id='.$setting['faspay_merchant_id'].'&bill_no='.$order_info['order_id'];

		$order_total=number_format($order_info['total'], 2, '.', '')-$shipping;

		if($payment_channel == 405){
				$rd = $setting['faspay_server'] == '0' ?
					//'https://202.6.215.230:8081/purchasing/purchase.do?action=loginRequest' :
					'http://faspaydev.mediaindonusa.com/redirectbca':
					'https://klikpay.klikbca.com/purchasing/purchase.do?action=loginRequest';
				
				$srv = $this->config->get('config_url');
				
				
				$indexProd = 0;
				$statusPayType = 1;
				$indexStatus = 1;
				$index = 0;
				$last = 0;
				$e = $this->cart->getProducts();
				
				foreach($order_product as $key => $val) {
				if($index == 0){
					if($this->request->post['payment_tenor_'.$index]== '00'){
						$statusPayType = 1;
						$last = 1;
					}else{
						$statusPayType = 2;
						$last = 2;
					}
				}else{
					if($this->request->post['payment_tenor_'.$index] == '00'){
						$statusPayType = 1;
					}else{
						$statusPayType = 2;
					}
					
				}
				if($last != $statusPayType){
					$last = 3;
				}
				$index++;
				}
				if($last == 1){
					$payType = '01';
				}else if($last == 2 ){
					$payType = '02';
				}else{
					$payType = '03';
				}

				$dat = array(
					'klikPayCode'=>$setting['faspay_bca_klikpaycode'],
					'transactionNo'=>$rsp['trx_id'],
					'totalAmount'=>$order_total.'.00',
					'currency'=>"IDR",
					'payType'=>$payType,
					'callback'=> "$srv"."index.php?route=payment/faspay/thanks&pg=bca_klikpay&trx_id=$rsp[trx_id]",
					'transactionDate'=>date('d/m/Y H:i:s',strtotime($order_info['date_modified'])),
					'descp'=>'Pembelian Barang '. $setting['faspay_merchant_name'],
					'miscFee'=>$shipping,
					'signature'=>$this->_bcakp_signature_ori($rsp['trx_id'], $setting,$order_total)
				);
				
				$data['dat'] = $dat;

				}elseif($payment_channel=='402' && $payment_channel_label=='PermataNet' ){

				$va_number=$rsp['trx_id'];
	  			$amount=$order_info['total'];
	  			
	 			$rd = $setting['faspay_server'] == '0' ?
					'http://faspaydev.mediaindonusa.com/permatanet/payment':
					'https://web.faspay.co.id/permatanet/payment';
					
	  			$post = array(                           
                  	"va_number"			=>$va_number,
				  	"amount" 			=>$amount                                 	
                                        
    			);

    			$this->model_checkout_order->addOrderHistory($order_info['order_id'],$setting['faspay_order_status_id'], "Customer Was Redirecting To Faspay",true);	
				$this->cart->clear();
			
				foreach($post as $key=>$value) { 
					$post_items[] = $key . '=' . $value;
				}
	
				$post_string = implode ('&', $post_items);

				$curl_connection =
  				curl_init($rd);

  				//set options
				curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
				curl_setopt($curl_connection, CURLOPT_USERAGENT,
  				"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
				curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
	}
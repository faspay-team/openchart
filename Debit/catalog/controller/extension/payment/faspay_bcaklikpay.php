<?php

require_once(DIR_SYSTEM . 'library/faspay-lib/Faspay.php');

class ControllerExtensionPaymentFaspayBcaklikpay extends Controller {
	
	public function index() {	

		$this->load->model('setting/setting');
		$this->load->language('extension/payment/faspay_bcaklikpay');
		$setting = $this->model_setting_setting->getSetting('faspay_bcaklikpay', 0);
		
		$data['button_confirm'] 			= $this->language->get('button_confirm');
		$data['button_back'] 				= $this->language->get('button_back');

		$data['text_title']		  = $this->language->get('text_title');
		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_payment'] 	  = $this->language->get('text_payment');
		
		$data['action']       			= $this->url->link('extension/payment/faspay/process_order','', 'SSL');
		$data['back']       			= $this->url->link('checkout/checkout', '', 'SSL');
		$data['gateway'] 				= '405';
		$data['flow']					= '1';
		$data['order_expired']			= $setting['faspay_bcaklikpay_expired_order'];
		$data['server']					= $setting['faspay_bcaklikpay_server'];
		$data['order_id'] 				= $this->session->data['order_id'];

		$data['min_price_3']			= $setting['faspay_bcaklikpay_minprice_3'];
		$data['stat_mid_3']				= $setting['faspay_bcaklikpay_statmid_3'];

		$data['min_price_6']			= $setting['faspay_bcaklikpay_minprice_6'];
		$data['stat_mid_6']				= $setting['faspay_bcaklikpay_statmid_6'];

		$data['min_price_12']			= $setting['faspay_bcaklikpay_minprice_12'];
		$data['stat_mid_12']			= $setting['faspay_bcaklikpay_statmid_12'];

		$data['min_price_24']			= $setting['faspay_bcaklikpay_minprice_24'];
		$data['stat_mid_24']			= $setting['faspay_bcaklikpay_statmid_24'];

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
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_bca_checkout')) {
            return $this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_bca_checkout', $data);
        } elseif (file_exists(DIR_TEMPLATE . 'default/template/extension/payment/faspay_bca_checkout') && VERSION < '2.2.0.0') {
            return $this->load->view('default/template/extension/payment/faspay_bca_checkout', $data);
        } else {
            return $this->load->view('extension/payment/faspay_bca_checkout', $data);
        }
	}
}

?>
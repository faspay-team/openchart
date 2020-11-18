<?php

class ControllerExtensionPaymentFaspayccO extends Controller {
	public function index() {
		
		$this->load->model('setting/setting');
		$this->load->language('extension/payment/faspaycc_o');
		$setting = $this->model_setting_setting->getSetting('faspaycc_o', 0);

		$data['button_confirm'] 	= $this->language->get('button_confirm');
		$data['button_back'] 		= $this->language->get('button_back');

		$data['text_title']		  	= $this->language->get('text_title');
		$data['text_instruction'] 	= $setting['faspaycc_o_title'];
		$data['text_description'] 	= $this->language->get('text_description');
		$data['text_payment'] 	  	= $this->language->get('text_payment');
		$data['text_minorder']	  	= $this->language->get('text_minorder');
		$data['success']			= $this->language->get('lbl_success');
		$data['fail']				= $this->language->get('lbl_fail');

		$data['action']				= $this->url->link('extension/payment/faspaycc/process_order','','SSL');
		$data['back']       		= $this->url->link('checkout/checkout', '', 'SSL');

		$data['mid']				= $setting['faspaycc_o_merchant_id'];
		$data['pas']				= $setting['faspaycc_o_merchant_pwd'];
		$data['merchant']			= $setting['faspaycc_o_title'];

		$data['order_id'] 			= $this->session->data['order_id'];
		$data['min_order']			= $setting['faspaycc_o_minimum_price'];

		$order_info					= $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$data['order_total']		= $order_info['total'];

	if ($order_info) {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspaycc_default')) {
	            return $this->load->view($this->config->get('config_template') . '/template/extension/payment/faspaycc_default', $data);
	        } elseif (file_exists(DIR_TEMPLATE . 'default/template/extension/payment/faspaycc_default') && VERSION < '2.2.0.0') {
	            return $this->load->view('default/template/extension/payment/faspaycc_default', $data);
	        } else {
	            return $this->load->view('extension/payment/faspaycc_default', $data);
	        }
		}
	}	 

	}
?>
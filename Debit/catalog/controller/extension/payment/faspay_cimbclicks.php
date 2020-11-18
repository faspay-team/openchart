<?php

class ControllerExtensionPaymentFaspayCimbclicks extends Controller {
	
	public function index() {	

		$this->load->model('setting/setting');
		$this->load->language('extension/payment/faspay_cimbclicks');
		$setting = $this->model_setting_setting->getSetting('faspay_cimbclicks', 0);
		
		$data['button_confirm'] 		= $this->language->get('button_confirm');
		$data['button_back'] 			= $this->language->get('button_back');

		$data['text_title']		  = $this->language->get('text_title');
		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_payment'] 	  = $this->language->get('text_payment');
		
		$data['action']       			= $this->url->link('extension/payment/faspay/process_order', '', 'SSL');
		$data['back']       			= $this->url->link('checkout/checkout', '', 'SSL');
		$data['gateway'] 				= '700';
		$data['flow']					= '1';
		$data['order_expired']			= $setting['faspay_cimbclicks_expired_order'];
		$data['server']					= $setting['faspay_cimbclicks_server'];
		$data['order_id'] 				= $this->session->data['order_id'];
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/faspay_default')) {
            return $this->load->view($this->config->get('config_template') . '/template/extension/payment/faspay_default', $data);
        } elseif (file_exists(DIR_TEMPLATE . 'default/template/extension/payment/faspay_default') && VERSION < '2.2.0.0') {
            return $this->load->view('default/template/extension/payment/faspay_default', $data);
        } else {
            return $this->load->view('extension/payment/faspay_default', $data);
        }
	}
}
?>
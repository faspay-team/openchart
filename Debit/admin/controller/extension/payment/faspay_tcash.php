<?php
class ControllerExtensionPaymentFaspayTcash extends Controller {

	private $error = array();

	public function index() {
		
		$this->load->language('extension/payment/faspay');
		$this->load->language('extension/payment/faspay_tcash');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('faspay_tcash', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
			
		}else{
			$this->session->data['failed'] = $this->language->get('text_failed');
		}

		$language_entries = array(
			'heading_title',
			'text_edit',
			'button_save',
			'button_cancel',
			'tab_account',
			'entry_server',
			'entry_expired_order',
			'text_enabled',
			'text_disabled',
			'text_all_zones',
			'entry_status',
			'entry_sort_order',
			'entry_geo_zone'
		);

		foreach ($language_entries as $language_entry) {
        $data[$language_entry] = $this->language->get($language_entry);
      	}
		
		//error
		$data['error_warning']  	 = isset($this->error['warning']) ?  $this->error['warning'] : $data['error_warning'] = ''; 
		$data['error_expired_order'] = isset($this->error['expired_order']) ? $this->error['expired_order'] : $data['error_expired_order'] = '';
		
		
		//breadcrumbs
		$data['breadcrumbs'] = array();
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/faspay_tcash', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('extension/payment/faspay_tcash', 'token=' . $this->session->data['token'], 'SSL');

    	$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

    	$inputs = array(
      		'faspay_tcash_server',
      		'faspay_tcash_geo_zone_id',
      		'faspay_tcash_expired_order',
      		'faspay_tcash_sort_order',
      		'faspay_tcash_status'
    	);
    
    	foreach ($inputs as $input) {
        	if (isset($this->request->post[$input])) {
          	$data[$input] = $this->request->post[$input];
        	} else {
          	$data[$input] = $this->config->get($input);
        	}
      	}

    	 $this->load->model('localisation/order_status');
    	$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    	 $this->load->model('localisation/geo_zone');
    	$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		
		$this->template = 'extension/payment/faspay_tcash.tpl';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['text_edit'] = $this->language->get('text_edit');

		//print_r($data);exit;

		$this->response->setOutput($this->load->view($this->template, $data));
	}
}
?>
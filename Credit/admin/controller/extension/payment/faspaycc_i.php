<?php
class ControllerExtensionPaymentFaspayccI extends Controller{
	private $error = array();

	public function index(){

		$this->load->language('extension/payment/faspaycc');
		$this->load->language('extension/payment/faspaycc_i');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('faspaycc_i', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}else{
			$this->session->data['failed'] = $this->language->get('text_failed');
		}

		$language_entries = array(
			'heading_title',
			'button_save',
			'button_cancel',
			'entry_title',
			'entry_merchant_id',
			'entry_merchant_pwd',
			'entry_minimum_price',
			'text_enabled',
			'text_disabled',
			'entry_status',
			'text_all_zones',
			'entry_geo_zone',
			'tab_account',
			'text_edit',
			'entry_sort_order'
		);

		foreach ($language_entries as $language_entry) {
        $data[$language_entry] = $this->language->get($language_entry);
      	}
      	$data['error_warning']  	 = isset($this->error['warning']) ?  $this->error['warning'] : $data['error_warning'] = '';
      	 $data['error_title'] = isset($this->error['title']) ? $this->error['title'] : $data['error_title'] = '';
      	$data['error_merchant_id']    = isset($this->error['merchant_id']) ? $this->error['merchant_id'] : $data['error_merchant_id'] = '';
      	 $data['error_merchant_pwd']    = isset($this->error['merchant_pwd']) ? $this->error['merchant_pwd'] : $data['error_merchant_pwd'] = '';

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
			'href'      => $this->url->link('extension/payment/faspaycc_i', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['action'] = $this->url->link('extension/payment/faspaycc_i', 'token=' . $this->session->data['token'], 'SSL');

    	$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

    	$inputs = array(
    		'faspaycc_i_geo_zone_id',
    		'faspaycc_i_sort_order',
    		'faspaycc_i_status',
    		'faspaycc_i_title',
    		'faspaycc_i_merchant_id',
    		'faspaycc_i_merchant_pwd',
    		'faspaycc_i_minimum_price'
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

    	$this->template = 'extension/payment/faspaycc_i.tpl';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['text_edit'] = $this->language->get('text_edit');

		$this->response->setOutput($this->load->view($this->template, $data));
	}
}
?>
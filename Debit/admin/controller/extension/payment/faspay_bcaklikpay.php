<?php
class ControllerExtensionPaymentFaspayBcaklikpay extends Controller {
	private $error = array();

	public function index() {
		
		$this->load->language('extension/payment/faspay');
		$this->load->language('extension/payment/faspay_bcaklikpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('faspay_bcaklikpay', $this->request->post);
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
			'tab_bca',
			'entry_server',
			'entry_expired_order',
			'text_enabled',
			'text_disabled',
			'text_all_zones',
			'entry_status',
			'entry_sort_order',
			'entry_geo_zone',

			'entry_bcaklikpay_clearkey',
			'entry_bcaklikpay_code',
			'entry_mid_full',

			'entry_mid_3',
			'entry_min_price_3',
			'entry_mid_3_active',

			'entry_mid_6',
			'entry_min_price_6',
			'entry_mid_6_active',

			'entry_mid_12',
			'entry_min_price_12',
			'entry_mid_12_active',

			'entry_mid_24',
			'entry_min_price_24',
			'entry_mid_24_active',

			'entry_mid_mix_active',

			'lbl_mid_full',
			'lbl_mid_3',
			'lbl_min_price_3',
			'lbl_stat_mid_3',
			'lbl_mid_6',
			'lbl_min_price_6',
			'lbl_stat_mid_6',
			'lbl_mid_12',
			'lbl_min_price_12',
			'lbl_stat_mid_12',
			'lbl_mid_24',
			'lbl_min_price_24',
			'lbl_stat_mid_24',
			'lbl_stat_mix'
		);

		foreach ($language_entries as $language_entry) {
        $data[$language_entry] = $this->language->get($language_entry);
      	}
		
		$data['error_warning']  	 = isset($this->error['warning']) ?  $this->error['warning'] : $data['error_warning'] = ''; 
		$data['error_expired_order'] = isset($this->error['expired_order']) ? $this->error['expired_order'] : $data['error_expired_order'] = '';
		

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
			'href'      => $this->url->link('extension/payment/faspay_bcaklikpay', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
	$data['action'] = $this->url->link('extension/payment/faspay_bcaklikpay', 'token=' . $this->session->data['token'], 'SSL');

    $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

    $inputs = array(
      'faspay_bcaklikpay_server',
      'faspay_bcaklikpay_geo_zone_id',
      'faspay_bcaklikpay_expired_order',
      'faspay_bcaklikpay_sort_order',
      'faspay_bcaklikpay_status',
      'faspay_bcaklikpay_clearkey',
      'faspay_bcaklikpay_code',
      'faspay_bcaklikpay_mid_full',

      'faspay_bcaklikpay_mid_3',
      'faspay_bcaklikpay_minprice_3',
      'faspay_bcaklikpay_statmid_3',

      'faspay_bcaklikpay_mid_6',
      'faspay_bcaklikpay_minprice_6',
      'faspay_bcaklikpay_statmid_6',

      'faspay_bcaklikpay_mid_12',
      'faspay_bcaklikpay_minprice_12',
      'faspay_bcaklikpay_statmid_12',

      'faspay_bcaklikpay_mid_24',
      'faspay_bcaklikpay_minprice_24',
      'faspay_bcaklikpay_statmid_24',

      'faspay_bcaklikpay_statmid_mix',
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

		
		$this->template = 'extension/payment/faspay_bcaklikpay.tpl';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['text_edit'] = $this->language->get('text_edit');

		$this->response->setOutput($this->load->view($this->template, $data));
	}
}
?>
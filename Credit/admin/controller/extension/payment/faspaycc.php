<?php

class ControllerExtensionPaymentFaspaycc extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/faspaycc');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/payment/faspaycc');
		$this->load->model('localisation/order_status');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('faspaycc',$this->request->post);
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
			'entry_server',
			'entry_order_status',
			'text_enabled',
			'text_disabled',
			'entry_status',
			'text_all_zones',
			'entry_geo_zone',
			'tab_account',
			'text_edit',
			'entry_sort_order',
			'entry_autovoid',
			'entry_void_order_status',
			'entry_after_payment_status'
		);

		foreach ($language_entries as $language_entry) {
        	$data[$language_entry] = $this->language->get($language_entry);
      	}

     $data['error_warning']     = isset($this->error['warning']) ?  $this->error['warning'] : $data['error_warning'] = ''; 
     $data['error_title'] = isset($this->error['title']) ? $this->error['title'] : $data['error_title'] = '';

     if (isset($this->error)) {
      $data['error'] = $this->error;
    } else {
      $data['error'] = array();
    }
		
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
      'href'      => $this->url->link('extension/payment/faspaycc', 'token=' . $this->session->data['token'], 'SSL'),
          'separator' => ' :: '
      );

	$data['action'] = $this->url->link('extension/payment/faspaycc', 'token=' . $this->session->data['token'], 'SSL');

    $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');   
		
		$inputs = array(
			'faspaycc_title',
			'faspaycc_server',
			'faspaycc_status',
			'faspaycc_geo_zone_id',
			'faspaycc_order_status',
			'faspaycc_sort_order',
			'faspaycc_autovoid',
			'faspaycc_void_status',
			'faspaycc_after_payment_status'
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

		$this->template = 'extension/payment/faspaycc.tpl';
    	$data['header'] = $this->load->controller('common/header');
    	$data['column_left'] = $this->load->controller('common/column_left');
    	$data['footer'] = $this->load->controller('common/footer');
    	$data['text_edit'] = $this->language->get('text_edit');

    	$this->response->setOutput($this->load->view('extension/payment/faspaycc.tpl', $data));
	}

	public function install() {
    $this->load->model('extension/payment/faspaycc');
    $this->model_extension_payment_faspaycc->install();
  }

  public function uninstall() {
    $this->load->model('extension/payment/faspaycc');
    $this->model_extension_payment_faspaycc->uninstall();
  }

	private function validate() {

	 	if (!$this->user->hasPermission('modify', 'extension/payment/faspaycc')) {
      $this->error['warning'] = $this->language->get('error_permission');
    	}
    	if (empty($this->request->post['faspaycc_title'])) {
      $this->error['faspaycc_title'] = $this->language->get('error_title');
    	}
    	if (!$this->error) {
      return true;
    	} else {
      return false;
    		}
   		 }
	}
?>
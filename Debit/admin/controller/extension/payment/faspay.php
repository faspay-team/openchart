<?php

class ControllerExtensionPaymentFaspay extends Controller {
  private $error = array();

  public function index() {
    $this->load->language('extension/payment/faspay');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');
    $this->load->model('extension/payment/faspay');
    $this->load->model('localisation/order_status');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('faspay', $this->request->post);
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
      'entry_merchant_id',
      'entry_merchant_name',
      'entry_user_id',
      'entry_user_pwd',
      'entry_server',
      'entry_expired_order',
      'entry_order_status',
      'entry_after_payment_status',
      'entry_sort_order',
      'text_all_zones',
      'entry_geo_zone',
      'entry_status',
      'text_enabled',
      'text_disabled',
      'tab_account'
    );
    foreach ($language_entries as $language_entry) {
        $data[$language_entry] = $this->language->get($language_entry);
      }
    
    $data['error_warning']     = isset($this->error['warning']) ?  $this->error['warning'] : $data['error_warning'] = ''; 
    $data['error_merchant_id']    = isset($this->error['merchant_id']) ? $this->error['merchant_id'] : $data['error_merchant_id'] = '';

    $data['error_expired_order'] = isset($this->error['expired_order']) ? $this->error['expired_order'] : $data['error_expired_order'] = '';

    $data['error_merchant_name'] = isset($this->error['merchant_name']) ? $this->error['merchant_name'] : $data['error_merchant_name'] = '';

    $data['error_user_id']     = isset($this->error['user_id']) ? $this->error['user_id'] : $data['error_user_id'] = '';

    $data['error_user_pwd']    = isset($this->error['user_pwd']) ? $this->error['user_pwd'] : $data['error_user_pwd'] = '';

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
      'href'      => $this->url->link('extension/payment/faspay', 'token=' . $this->session->data['token'], 'SSL'),
          'separator' => ' :: '
      );
    

    $data['action'] = $this->url->link('extension/payment/faspay', 'token=' . $this->session->data['token'], 'SSL');

    $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');   
    
    $inputs = array(
      'faspay_merchant_id',
      'faspay_merchant_name',
      'faspay_user_id',
      'faspay_user_pwd',
      'faspay_server',
      'faspay_geo_zone_id',
      'faspay_expired_order',
      'faspay_order_status_id',
      'faspay_after_payment_status_id',
      'faspay_sort_order',
      'faspay_status'
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
 
    $this->template = 'extension/payment/faspay.tpl';
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    $data['text_edit'] = $this->language->get('text_edit');

    $this->response->setOutput($this->load->view('extension/payment/faspay.tpl', $data));
  }

  public function install() {
    $this->load->model('extension/payment/faspay');
    $this->model_extension_payment_faspay->install();
  }

  public function uninstall() {
    $this->load->model('extension/payment/faspay');
    $this->model_extension_payment_faspay->uninstall();
  }

  private function validate() {

    if (!$this->user->hasPermission('modify', 'extension/payment/faspay')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (empty($this->request->post['faspay_merchant_id'])) {
      $this->error['faspay_merchant_id'] = $this->language->get('error_merchant_id');
    }

    if (empty($this->request->post['faspay_user_id'])) {
      $this->error['faspay_user_id'] = $this->language->get('error_user_id');
    }

    if (empty($this->request->post['faspay_merchant_name'])) {
      $this->error['faspay_merchant_name'] = $this->language->get('error_merchant_name');
    }

    if (empty($this->request->post['faspay_user_pwd'])) {
      $this->error['faspay_user_pwd'] = $this->language->get('error_user_pwd');
    }

    if (empty($this->request->post['faspay_expired_order'])) {
      $this->error['faspay_expired_order'] = $this->language->get('error_expired_order');
    }
    if (!$this->error) {
      return true;
    } else {
      return false;
    }
  } 
  
}
?>
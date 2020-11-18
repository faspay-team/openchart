<?php

class ModelExtensionPaymentFaspay extends Model {

	public function getMethod($address, $total) {
		
		$this->load->language('extension/payment/faspay');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('faspay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('faspay_total') > 0 && $this->config->get('faspay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('faspay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
	      $method_data = array(
	        'code'       => 'faspay',
	        'title'      => $this->language->get('text_title_faspay'),
			'sort_order' => $this->config->get('faspay_sort_order')
	      );
	    }

	    return $method_data;
	}

	public function update_payment_method ($payment_channel, $order_id) {

	  	$payment_lable = $this->getLable($payment_channel);

	  	$_payment_method = "Faspay - ".$payment_lable." (".$payment_channel.")"; 
	    $sql_order_update = "update `" . DB_PREFIX . "order` set payment_method = '".$_payment_method."' where order_id=".$order_id;

	    $update = $this->db->query($sql_order_update);                  
	    
	    return $update; 


	}

	public function getLable ($payment_channel) {
	  	 
    	$qry = $this->db->query("SELECT `channel_desc` FROM " . DB_PREFIX . "faspay_payment_channel WHERE channel_uid =$payment_channel")->rows;
    	 
    	foreach ($qry as $key => $value) {
    	 	$channel_desc = $value;
    	}
    	 
    	return $channel_desc['channel_desc'];	
          
	}

	public function getOrderProducts($order_id){

		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."order_product WHERE order_id = '" . (int)$order_id ."'")->rows;
		return $query;
	}

	public function insertTrx($order_id,$trx_id,$payment_channel,$payment_status){
		
		$sql = "INSERT INTO " . DB_PREFIX . "order_payment_faspay(order_id, trx_id, payment_channel, payment_status)
								values(".$order_id.", '".$trx_id."','".$payment_channel."','".$payment_status."')";
		return $this->db->query($sql);
	}

	public function updateTrx($payment_status_desc,$payment_date,$payment_reff,$trx_id){

		$sql = "UPDATE " . DB_PREFIX . "order_payment_faspay set
									payment_status = '$payment_status_desc',
									payment_date = '$payment_date',
									payment_reff = '$payment_reff'
							where 	trx_id = '$trx_id'";
					
		
		$query = $this->db->query($sql);
		return $query;
	}

	public function getOrderId ($trx_id) {

		$sql = "SELECT a.* from " . DB_PREFIX . "order a, " . DB_PREFIX . "order_payment_faspay b where a.order_id = b.order_id and b.trx_id = ".$trx_id;
		$rsp = $this->db->query($sql);
		$rsp = $rsp->rows;
		$order_id = $rsp[0]['order_id'];
		return $order_id;
	}

	public function getOrderValue($order_id) {

		$sql = "SELECT `code`,`value` from " .DB_PREFIX."order_total WHERE order_id = ".$order_id;
		$rsp = $this->db->query($sql)->rows;

		foreach ($rsp as $key => $value) {
			$data[]= array($value['code']=>$value['value']);
			
		}
		
		return $data;	
	}

	public function getShippingAmount($order_id){
		$shipping	= $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . $order_id . "' and code='shipping'");
		$shipping	= $shipping->rows;
		$ship		= isset($shipping[0]['value']) ? $shipping[0]['value'] : "0";
		return $ship;
	}

	public function getTotalAmount($order_id){
		$total = $this->db->query("SELECT * FROM ".DB_PREFIX."order_total WHERE order_id = '".$order_id."' and code='total'");
		$total 	= $total->rows;
		$tot 	= isset($total[0]['value']) ? $total[0]['value']:"0";

		return $tot;
		}

	public function getSubtotalAmount($order_id){
		$subtotal 	= $this->db->query("SELECT * FROM ".DB_PREFIX."order_total WHERE order_id = '".$order_id."' and code='sub_total'");
		$subtotal 	= $subtotal->rows;
		$subtot 	= isset($subtotal[0]['value']) ? $subtotal[0]['value']:"0";

		return $subtot;
		}

	public function getProduct($order_id){
		$product = $this->db->query("SELECT `name`,`quantity`,`price` FROM ".DB_PREFIX."order_product WHERE order_id='".$order_id."'");

		$product = $product->rows;


		return $product;

	}
	public function getIndex($order_id){
		$product = $this->db->query("SELECT `name`,`quantity`,`price` FROM ".DB_PREFIX."order_product WHERE order_id='".$order_id."'");

		$product = $product->rows;
		$size = count($product);

		return $size;
	}

}

?>
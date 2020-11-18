<?php
class ModelExtensionPaymentFaspaycc extends Model {

	public function install() {
		
		$query = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."order_payment_faspay_log`  (
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`merchant_id` varchar(100) DEFAULT NULL,
					`merchant_trx_id` int(50) DEFAULT NULL,
					`trx_id` int(50) DEFAULT NULL,
					`trx_status` varchar(32) DEFAULT NULL,
					`trx_error_code` varchar(32) DEFAULT NULL,
					`trx_error` varchar(100) DEFAULT NULL,
					`trx_timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($query);
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_payment_faspay_log`;");
	}

	public function log($data, $title = null) {
		if ($this->config->get('faspaycc_debug')) {
			$this->log->write('Faspay CC debug (' . $title . '): ' . json_encode($data));
		}
	}
}
?>
<?php
class ModelExtensionPaymentFaspay extends Model {

	public function install() {
		$check_idr = $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "currency` WHERE `code` = 'IDR'
		");

		if($check_idr->num_rows === 0){
			$this->db->query("
				INSERT INTO `" . DB_PREFIX . "currency` 
				(`title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `status`, `date_modified`)
				VALUES ('Rupiah', 'IDR', 'Rp', '', '0', 1, 1, '".date('Y-m-d H:i:s')."')
			");
		}
		
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_payment_faspay` (
			  `order_id` int(11) NOT NULL,
			  `trx_id` varchar(32) NOT NULL PRIMARY KEY,
			  `payment_channel` varchar(32) NOT NULL,
			  `payment_status` varchar(32) NOT NULL,
			  `payment_date` timestamp NULL,
			  `payment_reff` varchar(32) NULL
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_payment_faspay`;");
	}
 }
?>
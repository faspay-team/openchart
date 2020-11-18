<?php
// Text
$_['text_title'] = 'Faspay';

$_['button_confirm']			= 'Confirm Order';


$_['faspay'] = array('merchant_id'=>null, 'merchant_name'=>null,
				'order_expire'=>1,
				'userid'=>null, 'userpswd'=>null,
				'server'=>'development',
				'encsalt'=>'!kQm*fF3pXe1Kbm%9',
				'encpswd'=>'*!nD0n3s5!4#',
				'pg_exist'=>false,
				'bcakp_clearkey'=>'', 'bcakp_code'=>''
			    );

$_['pglist'] = array(
				'tcash' => array('checked' => ''),
				'xltunai' => array('checked' => ''),
				'sakuku' => array('checked' => ''),
				'indomaret' => array('checked' => '')
				);

$_['pglist_id'] = array(
				'tcash' => 302,
				'xltunai' => 303,
				'sakuku' => 704,
				'indomaret' => 706
				);

$_['pglist_radio'] = array(
				'faspay_tcash' => 'tCash',
				'faspay_xltunai' => 'XLTunai',
				'faspay_sakuku' => 'BCA Sakuku',
				'faspay_indomaret' => 'indomaret'
				);
?>
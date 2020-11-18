
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/faspay_paymentpage.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body style="background: url(catalog/view/theme/default/image/background.png);">
<!-- <h1>{$dat.payment_channel_uid}</h1>
<h2>{$dat.trx_uid}</h2>
<h3>{$dat}</h3>

 -->
 <?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

<div class="box">
        <!-- <img src="{$host}__assets/img/rf_{$dat.boi_uid}_logo.png" class="logo"> -->
     
        <div class="header">
            <p>Transaction Order Detail</p>
        </div>

        <div class="payment">Payment Via:</div>
        <img src="catalog/view/theme/default/image/<?=$payment_channel?>.png" class="bank">
           
        <table class="table mgr-t-20" id="table-checkout-orange">
            <tr>
                <td class="custom_color_left">Bill Number</td>
                <td class="custom_color_right text-right text-bold "><?= $bill_no ?></td>
            </tr>
            <tr>
                <td class="custom_color_left">Va Number / Kode Bayar</td>
                <td class="custom_color_right text-right text-bold VA"><?= $trx_id ?></td>
            </tr>
            <tr>
                <td class="custom_color_left">Total Payment</td>
                <td class="custom_color_right text-right text-bold">Rp. <?=$total*100?></td>
            </tr>
        </table>


    
          <div>
              <p>Expired in:</p>
              <center>
                 <h3 style="color: #FC8410"> <?=$expired_order ?> hours</h3>
              </center>
          </div>
      <br>
      <br>

    <?php include "catalog/view/theme/default/template/extension/payment/faspay_".$payment_channel.".tpl"?>
         	<br>
         	<br>
         	
    </div>
</div>

<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
</body>
</html>
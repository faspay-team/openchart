<h2><?php echo $text_instruction; ?></h2>
<p><b><?php echo $text_description; ?></b></p>

  <p><?php echo $text_payment; ?></p>
<form action="<?php echo $action; ?>" method="post" id="payment">

   <table class="col-md-5" width="500">
          <thead class="head">
              <th>Product Name</th>
              <th>Payment Type</th>
          </thead>
          <tbody>
            

                <?php $index = 0; ?>
                <?php foreach($products as $k => $v) { ?>
                <?php if ($v['name']!='Total:') { ?>
                <tr>
                  <center>
                    <td><?php echo $v['name'] ?></td>
                    <td align="left">

                    <select id="payment_tenor_<?php echo $index; ?>" name="payment_tenor_<?php echo $index; ?>">
                    <option value="00">Full Payment</option>
                    <?php $price = $v['price']; $qty = $v['quantity']; ?>

                    <?php if($price*$qty >= $min_price_3 && $stat_mid_3 == 1 ) {?>
                    <option value="03">Pembayaran Periode 3 Bulan</option>

                    <?php } ?>
                    <?php if($price*$qty >= $min_price_6 && $stat_mid_6 == 1) {?>
                    <option value="06">Pembayaran Periode 6 Bulan</option>

                    <?php } ?>
                    <?php if($price*$qty >= $min_price_12 && $stat_mid_12 == 1) {?>
                    <option value="12">Pembayaran Periode 12 Bulan</option>

                    <?php } ?>
                    <?php if($price*$qty >= $min_price_24 && $stat_mid_24 == 1) {?>
                    <option value="24">Pembayaran Periode 24 Bulan</option>
                    <?php } ?>

                    </select>
                    </td>
                  </center>
                  </tr>
                  <?php } ?>
                <?php $index++; ?>
                <?php } ?>
          </tbody>
        </table>

  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="order_expired" value="<?php echo $order_expired; ?>" />
  <input type="hidden" name="server" value="<?php echo $server; ?>" />
  <input type="hidden" name="gateway" value="<?php echo $gateway; ?>" />
  <input type="hidden" name="flow" value="<?php echo $flow; ?>" />


</form>
<div id="forceright" class="pull-right">
  <noscript><a onclick="$('#payment').submit();" class="btn btn-primary"><span><?php echo $button_confirm; ?></span></a></noscript>

  <a onclick="$('#payment').submit();" class="btn btn-primary"><span><?php echo $button_confirm; ?></span></a>
</div>

<style type="text/css">
.head{
  background-color: lightgrey;
}
.std{
  border:1px solid black;
  border-radius: 5px;

}
  </style>

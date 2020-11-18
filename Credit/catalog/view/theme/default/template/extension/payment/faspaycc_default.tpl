<form action="<?php echo $action; ?>" method="post" id="payment">
      <?php if($order_total >= $min_order){ ?>
	  <div class="well well-sm">
  <p><?php echo $text_payment; ?></p>
</div>


    <?php
     }else { 
         echo "<center><h3>".$text_minorder."</h3></center>";
     } ?>

    			<input type="hidden" name="order_total" value="<?php echo $order_total ?>" />
          <input type="hidden" name="order_id" value="<?php echo $order_id ?>" />
          <input type="hidden" name="server" value="<?php echo $server; ?>" />
          <input type="hidden" name="mid" value="<?php echo $mid ?>" />
          <input type="hidden" name="pas" value="<?php echo $pas ?>" />
          <input type="hidden" name="merchant" value="<?php echo $merchant ?>" />
    			<!-- <input type="hidden" name="status" value="<?php echo $status ?>" /> -->
</form>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script>
  $(document).ready(function(){
    $("#button-confirm").click(function(){        
        $("#payment").submit(); 
    });
  });
</script>


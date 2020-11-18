<form action="<?php echo $action; ?>" method="post" id="payment">  
  <div class="well well-sm">
  <p><?php echo $text_payment; ?></p>
</div>
  
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="order_expired" value="<?php echo $order_expired; ?>" />
  <input type="hidden" name="server" value="<?php echo $server; ?>" />
  <input type='hidden' name='gateway' value='<?php echo $gateway ?>' />
  <input type="hidden" name="flow" value='<?php echo $flow ?>' />
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
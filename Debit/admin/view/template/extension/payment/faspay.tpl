<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-faspay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
      <h1><img src="view/image/payment/faspay/faspay.png" alt="" /></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

<div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>

    <div class="panel-body">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-faspay" class="form-horizontal">
			<ul class="nav nav-tabs" id="tabs">
				<li class="active"><a href="#tab-account" data-toggle="tab"><?php echo $tab_account; ?></a></li>
			</ul>

	<div class="tab-content">
        <div class="tab-pane active" id="tab-account">

        	<!--SERVER-->
			<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-faspay-server"><?php echo $entry_server; ?></label>
                <div class="col-sm-10">
                  <select name="faspay_server" id="faspay-server" class="form-control">
                    <?php if ($faspay_server) { ?>
                    <option value="1" selected="selected"><?php echo $faspay_server='Production'; ?></option>
                    <option value="0"><?php echo $faspay_server='Development'; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $faspay_server='Production'; ?></option>
                    <option value="0" selected="selected"><?php echo $faspay_server='Development'; ?></option>
                    <?php } ?>
                  </select>
                </div>
            </div>

            <!--Merchant ID-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-merchant-id"><?php echo $entry_merchant_id; ?></label>
				<div class="col-sm-10">
				<input type="text" name="faspay_merchant_id" value="<?php echo $faspay_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="input-merchant-id" class="form-control" />
				<?php if ($error_merchant_id) { ?>
				<div class="text-danger"><?php echo $error_merchant_id; ?></div>
				<?php } ?>
				</div>
			</div>

			<!--Merchant Name-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-merchant-name"><?php echo $entry_merchant_name; ?></label>
				<div class="col-sm-10">
				<input type="text" name="faspay_merchant_name" value="<?php echo $faspay_merchant_name; ?>" placeholder="<?php echo $entry_merchant_name; ?>" id="input-merchant-name" class="form-control" />
				<?php if ($error_merchant_name) { ?>
				<div class="text-danger"><?php echo $error_merchant_name; ?></div>
				<?php } ?>
				</div>
			</div>

			<!--User ID-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-merchant-userID"><?php echo $entry_user_id; ?></label>
				<div class="col-sm-10">
				<input type="text" name="faspay_user_id" value="<?php echo $faspay_user_id; ?>" placeholder="<?php echo $entry_user_id; ?>" id="input-merchant-userID" class="form-control" />
				<?php if ($error_user_id) { ?>
				<div class="text-danger"><?php echo $error_user_id; ?></div>
				<?php } ?>
				</div>
			</div>

			<!--User Password-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-merchant-password"><?php echo $entry_user_pwd; ?></label>
				<div class="col-sm-10">
				<input type="text" name="faspay_user_pwd" value="<?php echo $faspay_user_pwd; ?>" placeholder="<?php echo $entry_user_pwd; ?>" id="input-merchant-password" class="form-control" />
				<?php if ($error_user_pwd) { ?>
				<div class="text-danger"><?php echo $error_user_pwd; ?></div>
				<?php } ?>
				</div>
			</div>

			<!--Expired Order in Hours-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-transaction-expired"><?php echo $entry_expired_order; ?></label>
				<div class="col-sm-10">
				<select name="faspay_expired_order" id="input-transaction-expired" placeholder="Select your Expired Date Order here" class="form-control">
                    <?php $i = 1;
                        while ($i <= 24):
                    ?>
                    <?php if ($faspay_expired_order == $i){ ?>
                        <option value="<?php echo $i;?>" selected="selected"><?php echo $i; ?></option>
                    <?php } else { ?>
                    	<option value="<?php echo $i;?>"><?php echo $i; ?></option>
                    <?php } ?>
                    <?php
                            $i++;
                        endwhile; ?>
				</select>
				</div>
			</div>

			<!--Order Status Awal-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
				<div class="col-sm-10">
					<select name="faspay_order_status_id" id="input-order-status" class="form-control">
						<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $faspay_order_status_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>

			<!--Order Status After Payment-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-after-payment-status"><?php echo $entry_after_payment_status; ?></label>
				<div class="col-sm-10">
					<select name="faspay_after_payment_status_id" id="input-after-payment-status" class="form-control">
						<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $faspay_after_payment_status_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>

			<!--Geo Zone-->
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
				<div class="col-sm-10">
				<select name="faspay_geo_zone_id" placeholder="Select your geo zone here" id="input-geo-zone" class="form-control">
                        <option value="0"><?php echo $text_all_zones; ?></option>
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                            <?php if ($geo_zone['geo_zone_id'] == $faspay_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
				</div>
			</div>

			<!--Sort Order-->
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order ?></label>
				<div class="col-sm-10">
					<input type="text" name="faspay_sort_order" value="<?php echo $faspay_sort_order; ?>"  id="input-sort-order" class="form-control" />
				</div>
			</div>

			<!--Status Channel-->
			<!-- <div class="form-group required">
				<label class="col-sm-2 control-label" for="faspay-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
				<select name="faspay_status" placeholder="Select your status enable here" id="faspay-status" class="form-control">
                      <?php if ($faspay_status) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                </select>
				</div>
			</div> -->

			</div>
		</div>
	</form>
	
    
     
       
   </div>    
  </div>
</div>
            
<?php echo $footer; ?> 

<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-faspaycc" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
      <h1><img src="view/image/payment/faspay/faspaycc.png" alt="" /></h1>
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
                <label class="col-sm-2 control-label" for="input-faspaycc-server"><?php echo $entry_server; ?></label>
                <div class="col-sm-10">
                  <select name="faspaycc_server" id="faspaycc-server" class="form-control">
                    <?php if ($faspaycc_server) { ?>
                    <option value="1" selected="selected"><?php echo $faspaycc_server='Production'; ?></option>
                    <option value="0"><?php echo $faspaycc_server='Development'; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $faspaycc_server='Production'; ?></option>
                    <option value="0" selected="selected"><?php echo $faspaycc_server='Development'; ?></option>
                    <?php } ?>
                  </select>
                </div>
            </div>

            <!--Auto Void-->
           <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-faspaycc-autovoid"><?php echo $entry_autovoid; ?></label>
                <div class="col-sm-10">
                  
                    <input type="checkbox" name="faspaycc_autovoid" value="on" <?php if($faspaycc_autovoid=="on")echo "checked"; ?>>
             
                </div>
            </div>

			<!--Title Name-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
				<div class="col-sm-10">
				<input type="text" name="faspaycc_title" value="<?php echo $faspaycc_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
				<?php if ($error_title) { ?>
				<div class="text-danger"><?php echo $error_title; ?></div>
				<?php } ?>
				</div>
			</div>

			<!--Order Status Awal-->
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
				<div class="col-sm-10">
					<select name="faspaycc_order_status" id="input-order-status" class="form-control">
						<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $faspaycc_order_status) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>

      <!--Status Void-->
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_void_order_status; ?></label>
        <div class="col-sm-10">
          <select name="faspaycc_void_status" id="input-void-status" class="form-control">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $faspaycc_void_status) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>

      <!--After Payment Status-->
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_after_payment_status; ?></label>
        <div class="col-sm-10">
          <select name="faspaycc_after_payment_status" id="input-after-payment-status" class="form-control">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $faspaycc_after_payment_status) { ?>
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
				<select name="faspaycc_geo_zone_id" placeholder="Select your geo zone here" id="input-geo-zone" class="form-control">
                        <option value="0"><?php echo $text_all_zones; ?></option>
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                            <?php if ($geo_zone['geo_zone_id'] == $faspaycc_geo_zone_id) { ?>
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
					<input type="text" name="faspaycc_sort_order" value="<?php echo $faspaycc_sort_order; ?>"  id="input-sort-order" class="form-control" />
				</div>
			</div>

			<!--Status Channel-->
		<!-- 	<div class="form-group required">
				<label class="col-sm-2 control-label" for="faspaycc-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
				<select name="faspaycc_status" placeholder="Select your status enable here" id="faspaycc-status" class="form-control">
                      <?php if ($faspaycc_status) { ?>
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

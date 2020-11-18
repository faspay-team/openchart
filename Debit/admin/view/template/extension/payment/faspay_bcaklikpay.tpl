<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-faspay-bcaklikpay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
    </div>
      <h1><img src="view/image/payment/faspay/bcaklikpay_faspay.png" alt="" /></h1>
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
        <li><a href="#tab-bca-config" data-toggle="tab"><?php echo $tab_bca; ?></a></li>
      </ul>

  <div class="tab-content">
        <div class="tab-pane active" id="tab-account">

      <!--Status Channel-->
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="faspay-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_status" placeholder="Select your status enable here" id="faspay-bcaklikpay-status" class="form-control">
                      <?php if ($faspay_bcaklikpay_status) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                </select>
          </div>
      </div>

      <!--SERVER-->
      <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-faspay-server"><?php echo $entry_server; ?></label>
                <div class="col-sm-10">
                  <select name="faspay_bcaklikpay_server" id="faspaybcaklikpay-server" class="form-control">
                    <?php if ($faspay_bcaklikpay_server) { ?>
                    <option value="1" selected="selected"><?php echo $faspay_bcaklikpay_server='Production'; ?></option>
                    <option value="0"><?php echo $faspay_bcaklikpay_server='Development'; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $faspay_bcaklikpay_server='Production'; ?></option>
                    <option value="0" selected="selected"><?php echo $faspay_bcaklikpay_server='Development'; ?></option>
                    <?php } ?>
                  </select>
                </div>
            </div>


      <!--Geo Zone-->
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_geo_zone_id" placeholder="Select your geo zone here" id="input-geo-zone" class="form-control">
                        <option value="0"><?php echo $text_all_zones; ?></option>
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                            <?php if ($geo_zone['geo_zone_id'] == $faspay_bcaklikpay_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
            </div>
      </div>

      <!--Expired Order in Hours-->
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-transaction-expired"><?php echo $entry_expired_order; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_expired_order" id="input-transaction-expired" placeholder="Select your Expired Date Order here" class="form-control">
                    <?php $i = 1;
                        while ($i <= 24):
                    ?>
                    <?php if ($faspay_bcaklikpay_expired_order == $i){ ?>
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

      <!--Sort Order-->
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order ?></label>
        <div class="col-sm-10">
          <input type="text" name="faspay_bcaklikpay_sort_order" value="<?php echo $faspay_bcaklikpay_sort_order; ?>"  id="input-sort-order" class="form-control" />
        </div>
      </div>


    </div>
    <div class="tab-pane" id="tab-bca-config">

      <!--BCA KLIKPAY CONFIGURATION-->
      <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-clearkey"><?php echo $entry_bcaklikpay_clearkey; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_clearkey" value="<?php echo $faspay_bcaklikpay_clearkey; ?>" placeholder="<?php echo $entry_bcaklikpay_clearkey; ?>" id="input-bca-clearkey" class="form-control" />
        </div>
      </div>

      <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-klikpaycode"><?php echo $entry_bcaklikpay_code; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_code" value="<?php echo $faspay_bcaklikpay_code; ?>" placeholder="<?php echo $entry_bcaklikpay_code; ?>" id="input-bcaklikpay-code" class="form-control" />
        </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-mid-full"><?php echo $lbl_mid_full; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_mid_full" value="<?php echo $faspay_bcaklikpay_mid_full; ?>" placeholder="<?php echo $lbl_mid_full; ?>" id="input-bca-mid-full" class="form-control" />
        </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-mid3"><?php echo $lbl_mid_3; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_mid_3" value="<?php echo $faspay_bcaklikpay_mid_3; ?>" placeholder="<?php echo $lbl_mid_3; ?>" id="input-bca-mid3" class="form-control" />
        </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-min3"><?php echo $lbl_min_price_3; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_minprice_3" value="<?php echo $faspay_bcaklikpay_minprice_3; ?>" placeholder="<?php echo $lbl_min_price_3; ?>" id="input-bca-min3" class="form-control" />
        </div>
    </div>

    <div class="form-group ">
        <label class="col-sm-2 control-label" for="faspay-status-mid3"><?php echo $lbl_stat_mid_3; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_statmid_3" placeholder="Select your status enable here" id="faspay-bcaklikpay-status-mid3" class="form-control">
                      <?php if ($faspay_bcaklikpay_statmid_3) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                </select>
        </div>
      </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-mid6"><?php echo $lbl_mid_6; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_mid_6" value="<?php echo $faspay_bcaklikpay_mid_6; ?>" placeholder="<?php echo $lbl_mid_6; ?>" id="input-bca-mid6" class="form-control" />
        </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-min3"><?php echo $lbl_min_price_6; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_minprice_6" value="<?php echo $faspay_bcaklikpay_minprice_6; ?>" placeholder="<?php echo $lbl_min_price_6; ?>" id="input-bca-min6" class="form-control" />
        </div>
    </div>

    <div class="form-group ">
        <label class="col-sm-2 control-label" for="faspay-status-mid6"><?php echo $lbl_stat_mid_6; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_statmid_6" placeholder="Select your status enable here" id="faspay-bcaklikpay-status-mid6" class="form-control">
                      <?php if ($faspay_bcaklikpay_statmid_6) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                </select>
        </div>
      </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-mid12"><?php echo $lbl_mid_12; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_mid_12" value="<?php echo $faspay_bcaklikpay_mid_12; ?>" placeholder="<?php echo $lbl_mid_12; ?>" id="input-bca-mid12" class="form-control" />
        </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-min12"><?php echo $lbl_min_price_12; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_minprice_12" value="<?php echo $faspay_bcaklikpay_minprice_12; ?>" placeholder="<?php echo $lbl_min_price_12; ?>" id="input-bca-min12" class="form-control" />
        </div>
    </div>

    <div class="form-group ">
        <label class="col-sm-2 control-label" for="faspay-status-mid12"><?php echo $lbl_stat_mid_12; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_statmid_12" placeholder="Select your status enable here" id="faspay-bcaklikpay-status-mid12" class="form-control">
                      <?php if ($faspay_bcaklikpay_statmid_12) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                </select>
        </div>
      </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-mid24"><?php echo $lbl_mid_24; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_mid_24" value="<?php echo $faspay_bcaklikpay_mid_24; ?>" placeholder="<?php echo $lbl_mid_24; ?>" id="input-bca-mid24" class="form-control" />
        </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-bca-min24"><?php echo $lbl_min_price_24; ?></label>
        <div class="col-sm-10">
        <input type="text" name="faspay_bcaklikpay_minprice_24" value="<?php echo $faspay_bcaklikpay_minprice_24; ?>" placeholder="<?php echo $lbl_min_price_24; ?>" id="input-bca-min24" class="form-control" />
        </div>
    </div>

    <div class="form-group ">
        <label class="col-sm-2 control-label" for="faspay-status-mid24"><?php echo $lbl_stat_mid_24; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_statmid_24" placeholder="Select your status enable here" id="faspay-status-mid24" class="form-control">
                      <?php if ($faspay_bcaklikpay_statmid_24) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                </select>
        </div>
      </div>

    <div class="form-group ">
        <label class="col-sm-2 control-label" for="faspay-status-mix"><?php echo $lbl_stat_mix; ?></label>
        <div class="col-sm-10">
        <select name="faspay_bcaklikpay_statmid_mix" placeholder="Select your status enable here" id="faspay-status-mix" class="form-control">
                      <?php if ($faspay_bcaklikpay_statmid_mix) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                </select>
              </div>
            </div>

          </div>
        </div>

      </form>  

    </div>    
  </div>
</div>
            
<?php echo $footer; ?> 

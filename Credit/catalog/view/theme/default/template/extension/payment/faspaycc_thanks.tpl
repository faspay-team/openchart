<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
	<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/extension_payment_faspaycc_thanks.css" />
	<div id="content"><?php echo $content_top; ?>
		<div class="wrapper">
			<div class="page">
				<div class="main-container col1-layout">
					<div class="main">
						<div class="col-main body">
						<?php
							if ($faspaycc_status =="SUCCESS"){
								echo "<center><h1><p> <font size=10pt>Thank You</font> </p></h1></center><br>";
								echo "<center><font color=red size=4pt>Your Payment process with the following order id =<font color=Black size=5>".$order_id."</font> has been succeed</font><br></center><br><br>";
								echo "<center><div class='right'><a href='$continue' class='btn btn-primary'>$button_continue</a></div></center>";

							}elseif ($faspaycc_status =="PENDING"){
								echo "<center><h1><p> <font size=10pt>Payment Pending</font> </p></h1></center>><br>";
								echo "<center><font color=red size=4pt>Your Payment with the following order id =<font color=Black size=5> ".$order_id."</font> still on process</font><br></center><br><br>";
								echo "<center><div class='right'><a href='$continue' class='btn btn-primary'>$button_continue</a></div></center>";

							}else{
								echo "<center><h1><p> <font size=10pt>Sorry</font> </p></h1><br></center>";
								echo "<center>Your Payment process with the following order id = ".$order_id." has been failed, please try again later or contact your merchant if still facing same difficulties</center><br><br>";
								echo "<center><font color=red size=4pt>".$errorMessage."</font></center>><br><br>";
								echo "<center><div class='right'><a href='$continue' class='btn btn-primary'>$button_continue</a></div></center>";
							}
						?>   
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>

<script type="text/javascript">
	WebFontConfig = {
		google: { families: [ 'Josefin Sans Std Light', 'Lobster' ] }
	};
	(function() {
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
		'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
	})();
	</script>
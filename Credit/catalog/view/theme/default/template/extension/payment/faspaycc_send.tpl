<form name="form" action="<?php echo $action; ?>" method="post">
	<?php echo $message; ?>
	<?php $post!=0; ?>
	<?php foreach($post as $name => $value) { ?>
		<?php echo"<input type='hidden' name='".$name."' value='".$value."'>" ?>
	<?php } ?>

	<p><input type="submit" value="<?php echo $button ?>"></p>
	   <input type="hidden" name="post" value="<?php echo $post ?>" />
	   <input type="hidden" name=".$name." value=".$value." /> 
</form>

<script type="text/javascript">
	document.form.submit();
</script>
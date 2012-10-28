<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
	  <div class="left"></div>
	  <div class="right"></div>
	 <div class="heading">
		<h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
	  </div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		  <div id="tab_general" class="page">
			<table class="form">
			  <tr>
				<td width="25%"><span class="required">*</span> <?php echo $entry_id_comercio; ?></td>
				<td><input type="text" name="bbva_id_comercio" value="<?php echo $bbva_id_comercio; ?>" size="15" /></td>
			  </tr>
			  <tr>
				<td width="25%"><span class="required">*</span><?php echo $entry_terminal; ?></td>
				<td><input type="text" name="bbva_terminal" value="<?php echo $bbva_terminal; ?>" size="6" /></td>
			  </tr>
			  <tr>
				<td width="25%"><span class="required">*</span><?php echo $entry_clave; ?></td>
				<td><input type="text" name="bbva_clave" value="<?php echo $bbva_clave; ?>" size="8" /></td>
			  </tr>
			 <tr>
				<td width="25%"><span class="required">*</span><?php echo $entry_obfuscated; ?></td>
				<td><input type="text" name="bbva_obfuscated" value="<?php echo $bbva_obfuscated; ?>" size="70" /></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_completed_status; ?></td>
				<td><select name="bbva_completed_status_id">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $bbva_completed_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_denied_status; ?></td>
				<td><select name="bbva_denied_status_id">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $bbva_denied_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_failed_status; ?></td>
				<td><select name="bbva_failed_status_id">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $bbva_failed_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_pending_status; ?></td>
				<td><select name="bbva_pending_status_id">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $bbva_pending_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_error_status; ?></td>
				<td><select name="bbva_error_status_id">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $bbva_error_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_cancel_status; ?></td>
				<td><select name="bbva_cancel_status_id">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $bbva_cancel_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_geo_zone; ?></td>
				<td><select name="bbva_geo_zone_id">
					<option value="0"><?php echo $text_all_zones; ?></option>
					<?php foreach ($geo_zones as $geo_zone) { ?>
					<?php if ($geo_zone['geo_zone_id'] == $bbva_geo_zone_id) { ?>
					<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_status; ?></td>
				<td><select name="bbva_status">
					<?php if ($bbva_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_sort_order; ?></td>
				<td><input type="text" name="bbva_sort_order" value="<?php echo $bbva_sort_order; ?>" size="1" /></td>
			  </tr>
			</table>
		  </div>
		</form>
	</div>
</div>
<?php echo $footer; ?>
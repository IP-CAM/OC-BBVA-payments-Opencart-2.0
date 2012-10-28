<form action="<?php echo $action; ?>" method="post" id="payment">
	<input type="hidden" name="peticion" value="<?php echo $peticion; ?>" />
</form>
<div class="buttons">
	<div class="right"><a id="button-confirm" class="button" onclick="$('#payment').submit();"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	$validation = strtolower($validation);
	
	$required = "";
	if(strpos(strtolower($validation), 'not_empty') !== FALSE)
	{
		$required = 'REQUIRED';
	}

	// add class title if the field is title !!
	$classtitle = "";
	if($model == 'Entry' && $counter == 0)
	{
		$classtitle = "Title";
	}

	$colorvalue = (isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:$value);
?>
<script>
	$(document).ready(function(){
		$('input.<?php echo $shortkey; ?>').prev('input[type=color]').change(function(){
			$('input.<?php echo $shortkey; ?>').val( $(this).val() );
		});

		$('input.<?php echo $shortkey; ?>').change(function(){
			var myval = $(this).val();
			if(myval.length > 0)
			{
				$(this).prev('input[type=color]').val( myval );

				// update back color ...
				$(this).val( $(this).prev('input[type=color]').val() );
			}
		});
	});
</script>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (!empty($required)?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls">
		<input class="input-mini" type="color" value="<?php echo $colorvalue; ?>" />
		<input <?php echo $required; ?> type="text" class="<?php echo $shortkey; ?> <?php echo $classtitle; ?> input-mini" value="<?php echo $colorvalue; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]" />
		<p class="help-block">
            <?php echo $p; ?>
        </p>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>
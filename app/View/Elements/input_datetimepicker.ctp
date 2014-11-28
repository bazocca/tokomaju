<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	
	$nowDate = getdate();
	$year = $nowDate['year'];
	$month = sprintf("%02d",$nowDate['mon']);
    $day = sprintf("%02d",$nowDate['mday']);
	$hour = sprintf("%02d",$nowDate['hours']);
    $minute = sprintf("%02d",$nowDate['minutes']);
	
	$required = "";
	if(strpos(strtolower($validation), 'not_empty') !== FALSE)
	{
		$required = 'REQUIRED';
	}	
?>
<script>
	$(document).ready(function(){
		// init datetime picker function
		$('input.dtimepicker.<?php echo $shortkey; ?>').datetimepicker({
		    changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-80:+20",
		});
	});
</script>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>
	<label class="control-label" <?php echo (!empty($required)?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls">
		<input <?php echo $required; ?> class="input-medium dtimepicker <?php echo $shortkey; ?>" type="text" value="<?php echo (isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:(empty($value)?(strpos(strtolower($validation), 'not_empty') !== FALSE?$month."/".$day."/".$year." ".$hour.":".$minute:""):$value)); ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]"/>
		<?php
			if(!empty($p))
			{
				echo '<p class="help-block">'.$p.'</p>';
			}
		?>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>
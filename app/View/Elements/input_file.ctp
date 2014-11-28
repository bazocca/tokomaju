<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	
	$required = "";
	if(strpos(strtolower($validation), 'not_empty') !== FALSE && empty($myEntry)) // SPECIAL CASE FOR INPUT FILE !!
	{
		$required = 'REQUIRED';
	}
?>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (!empty($required)?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls">
		<input <?php echo $required; ?> class="<?php echo $shortkey; ?>" type="file" placeholder="<?php echo $placeholder; ?>" name="<?php echo $key; ?>"/>
		<?php
            if(empty($myEntry))
            {
                echo '<p class="help-block">';
                echo (empty($p)?'Upload File which is downloadable by guest.':$p);
                echo '</p>';
            }
            else
            {
                echo '<p class="help-block" style="color:red;">';
                echo 'IGNORE this field if there are no any changes to the previous file.';
                echo '</p>';
            }
        ?>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>
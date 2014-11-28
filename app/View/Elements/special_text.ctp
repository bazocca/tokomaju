<div class="control-group">            
	<!-- <label class="control-label">
		<?php
			echo string_unslug(substr($key, 5 ));
			if(strpos(strtolower($validation), 'not_empty') !== FALSE)
			{
				echo '*';
			} 
		?>
	</label> -->
	<label class="control-label" <?php echo (strpos(strtolower($validation), 'not_empty') !== FALSE?'style="color: red;"':''); ?>>
        <?php echo string_unslug(substr($key, 5 )); ?>
    </label>
	<div class="controls">
		<input class="input-xlarge" placeholder="<?php echo $placeholder; ?>" type="text" size="200" value="<?php echo $value; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]"/>
		<a alt="<?php echo $counter+1; ?>" href="javascript:void(0)" class="btn del_setting">Remove</a>
		<?php
			if(!empty($p))
			{
				echo '<p class="help-block">'.$p.'</p>';
			}
		?>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" size="100" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>	
	<input type="hidden" value="<?php echo $input_type; ?>" size="100" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>	
	<textarea type="text" style="display: none" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"><?php echo $validation; ?></textarea>
</div>
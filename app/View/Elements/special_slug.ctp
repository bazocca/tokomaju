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
		<p><input class="input-xlarge" type="text" size="200" value="<?php echo $value; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]"/></p>
		<p id='slug_source' class="help-block" style="display: inline-block"><?php echo $p; ?></p>
		<input class="input-medium" id='slug_value' style="display: none;" type="text" size="200" value=""/>
		<a id="edit_slug" class="btn btn-info" onclick="javascript : $.fn.editSlug(<?php echo $id; ?>);" href="javascript:void(0)">Edit Slug</a>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" size="100" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>	
	<input type="hidden" value="<?php echo $input_type; ?>" size="100" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>	
	<textarea type="text" style="display: none" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"><?php echo $validation; ?></textarea>
</div>
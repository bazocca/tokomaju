<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	$validation = strtolower($validation);

	$required = "";
	if(strpos($validation, 'not_empty') !== FALSE)
	{
		$required = 'REQUIRED';
	}

	// characters limitation !!
	$maxchar = 0;
	$posMaxLength = strpos($validation, 'max_length');
	if($posMaxLength !== FALSE)
	{
		$tempstart = $posMaxLength+11;
		$caripentung = strpos($validation, '|' , $posMaxLength);
		if($caripentung === FALSE)
		{
			$maxchar = substr($validation, $tempstart);
		}
		else
		{
			$maxchar = substr($validation, $tempstart , $caripentung - $tempstart );
		}
	}
?>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (!empty($required)&&!$view_mode?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls">		
		<?php
            if($view_mode)
            {
                echo '<div class="view-mode '.$shortkey.'">';
                echo (empty($value)?'-':str_replace(chr(10) , '<br/>' , $value));
                echo '</div>';
            }
            else
            {
                ?>
        <textarea rows="5" <?php echo ($maxchar > 0?'maxlength="'.$maxchar.'"':''); ?> <?php echo $required; ?> class="<?php echo ($shortkey=='keterangan'?'large':'medium').' '.$shortkey; ?>" type="text" placeholder="<?php echo $placeholder; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]"><?php echo (isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:$value); ?></textarea>
                <?php
                
                if(!empty($p))
                {
                    echo '<p class="help-block">'.$p.'</p>';
                }
            }
		?>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>
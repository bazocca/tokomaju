<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	$validation = strtolower($validation);
	
	$required = "";
	if(strpos($validation, 'not_empty') !== FALSE)
	{
		$required = 'REQUIRED';
	}
	
	$detail_type = "text";
	if(strpos($validation, 'is_email') !== FALSE)
	{
		$detail_type = 'email';
	}
	else if(strpos($validation, 'is_numeric') !== FALSE)
	{
		$detail_type = 'number';
	}
	else if(strpos($validation, 'is_url') !== FALSE)
	{
		$detail_type = 'url';
	}

	// add class title if the field is title !!
	$classtitle = "";
	if($model == 'Entry' && $counter == 0)
	{
		$classtitle = "Title";
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

	// set specific input class !!
	if(empty($inputsize))
	{
		$inputsize = 'input-xlarge';
	}
	
	if($shortkey == 'diskon' || $shortkey == 'berat' || $shortkey == 'jumlah' || $shortkey == 'stock')
	{
		$inputsize = 'input-mini';
	}
	else if($shortkey == "harga" || $shortkey == "harga_beli" || $shortkey == "harga_jual" || $shortkey == "total_harga" || $shortkey == "mutasi_debet" || $shortkey == "mutasi_kredit")
	{
		$inputsize = 'input-small';
	}
?>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (!empty($required)&&!$view_mode?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls">
		<?php
            if($view_mode)  echo '<div class="view-mode">';

            // header string !!
            if($shortkey == "harga" || $shortkey == "harga_beli" || $shortkey == "harga_jual" || $shortkey == "total_harga" || $shortkey == "mutasi_debet" || $shortkey == "mutasi_kredit")
			{
				echo 'Rp.';
			}

            if($view_mode)
            {
                echo (empty($value)?'-':$value);
            }
            else
            {
                ?>
<input id="<?php echo $id; ?>" <?php echo ($maxchar > 0?'maxlength="'.$maxchar.'"':''); ?> <?php echo ($detail_type=='number'?'step="1" min="1"':''); ?> <?php echo (!empty($readonly)?'readonly="true"':''); ?> <?php echo $required; ?> class="<?php echo $inputsize.' '.$shortkey.' '.$classtitle; ?>" type="<?php echo $detail_type; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo (isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:$value); ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]"/>
                <?php
            }
		
            // footer string !!
			if($shortkey == 'diskon')
			{
				echo '% OFF';
			}
			else if($shortkey == "harga" || $shortkey == "harga_beli" || $shortkey == "harga_jual" || $shortkey == "total_harga" || $shortkey == "mutasi_debet" || $shortkey == "mutasi_kredit")
			{
				echo ',-';
			}
			else if($shortkey == 'berat')
			{
				echo 'kg';
			}
			else if($shortkey == 'jumlah')
			{
				echo 'unit(s)';
			}
            else if($shortkey == 'stock')
            {
                echo '<span class="stock-satuan">unit(s)</span>';
            }

            if($view_mode)  echo '</div>';
		?>
		<p class="help-block">
            <?php echo ($view_mode?'':$p); ?>
        </p>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>
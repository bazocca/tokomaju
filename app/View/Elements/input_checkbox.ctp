<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	
	if(strpos(strtolower($validation), 'browse_module') !== FALSE)
	{
		$sqldata = $this->Get->list_entry(array(
			'type' => get_slug($shortkey),
			'orderField' => 'title',
			'orderDirection' => 'ASC',
			'raw' => 1
		));
		
		$list = array();
		foreach ($sqldata['myList'] as $sqlkey => $sqlvalue) 
		{
			$list[$sqlkey]['id'] = $list[$sqlkey]['name'] = $sqlvalue['Entry']['title'];
		}
	}
?>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (strpos(strtolower($validation), 'not_empty') !== FALSE?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls checkbox" style="margin-top: 5px;">
		<?php
			$value = isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:explode("|", $value);	
			foreach ($list as $key10 => $value10)
			{
				$existed = 0;
				foreach ($value as $key20 => $value20) 
				{
					if(strtolower($value10['id']) == strtolower($value20))	
					{	
						$existed = 1;
						break;
					}
				}

				$checkboxName = "data[".$model."][".$counter."][value][".$value10['id']."]";
				echo "<input class='".$shortkey."' ".($existed==0?'':'CHECKED')." value='".$value10['id']."' type='checkbox' name='".$checkboxName."' id='".$checkboxName."'/><label for='".$checkboxName."'>".$value10['name']."</label>";
			}
			if(!empty($p))
			{
				echo '<p class="help-block">'.$p.'</p>';
			}
		?>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $optionlist; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][optionlist]"/>	
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>
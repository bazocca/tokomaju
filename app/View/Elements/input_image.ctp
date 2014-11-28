<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	
	$value = (isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:(empty($value)?0:$value));
	$p = (empty($p)?'JPG, PNG, or GIF and 1MB max':$p);
	
	// find use manual crop or not !!
	$crop = -1;
	foreach ($myType['TypeMeta'] as $key10 => $value10) 
	{
		if($value10['TypeMeta']['key'] == "display_crop")
		{
			$crop = $value10['TypeMeta']['value'];
			break;
		}
	}
	
	// IN EDIT MODE, WITH LAST CROP X1, Y1, X2, Y2
	$childImage = array();
	if($crop == 2 && $value > 0)
	{
		$this->Get->create($data);
		$childImage = $this->Get->meta_details(NULL , NULL , NULL , $value);
		if($childImage['Entry']['parent_id'] > 0)
		{
			$value = $childImage['Entry']['parent_id'];
		}
	}
?>
<script>
	$(document).ready(function(){
		<?php if($crop == 2): ?>
		    // Simple event handler, called from onChange and onSelect
            // event handlers, as per the Jcrop invocation above
            function showCoords_<?php echo $counter; ?>(c)
            {
              $('#x1_<?php echo $counter; ?>').val(c.x);
              $('#y1_<?php echo $counter; ?>').val(c.y);
              $('#x2_<?php echo $counter; ?>').val(c.x2);
              $('#y2_<?php echo $counter; ?>').val(c.y2);
              $('#w_<?php echo $counter; ?>').val(c.w);
              $('#h_<?php echo $counter; ?>').val(c.h);
            }
            
            function clearCoords_<?php echo $counter; ?>()
            {
              $('div#imageinfo_<?php echo $counter; ?> input').val('');
            }
            
            $('img#myEditCoverImage_<?php echo $counter; ?>').load(function(){
                // Activate jCrop Plugin ...
                jcrop_api[<?php echo $counter; ?>] = $.Jcrop('img#myEditCoverImage_<?php echo $counter; ?>', {
                    onChange:   showCoords_<?php echo $counter; ?>,
                    onSelect:   showCoords_<?php echo $counter; ?>,
                    onRelease:  clearCoords_<?php echo $counter; ?>,
                    bgColor:    'black',
                                    
                    <?php if(!empty($childImage) && ($childImage['Entry']['parent_id'] > 0 && is_numeric($childImage['EntryMeta']['image_x']) && is_numeric($childImage['EntryMeta']['image_y']) && is_numeric($childImage['EntryMeta']['image_width']) && is_numeric($childImage['EntryMeta']['image_height']) || is_numeric($_POST['data'][$model][$counter]['x1']) && is_numeric($_POST['data'][$model][$counter]['x2']) && is_numeric($_POST['data'][$model][$counter]['y1']) && is_numeric($_POST['data'][$model][$counter]['y2']) )): ?>
                    setSelect:  [ <?php echo (is_numeric($_POST['data'][$model][$counter]['x1'])?$_POST['data'][$model][$counter]['x1']:$childImage['EntryMeta']['image_x']); ?>,
                    <?php echo (is_numeric($_POST['data'][$model][$counter]['y1'])?$_POST['data'][$model][$counter]['y1']:$childImage['EntryMeta']['image_y']); ?>,
                    <?php echo (is_numeric($_POST['data'][$model][$counter]['x2'])?$_POST['data'][$model][$counter]['x2']:$childImage['EntryMeta']['image_x'] + $childImage['EntryMeta']['image_width']); ?> ,
                    <?php echo (is_numeric($_POST['data'][$model][$counter]['y2'])?$_POST['data'][$model][$counter]['y2']:$childImage['EntryMeta']['image_y'] + $childImage['EntryMeta']['image_height']); ?> ]
                    <?php endif; ?>
                });
                
                // SET CROP SELECTION MANUALLY FROM INPUT TYPES !!
                $('input[type=text]#x1_<?php echo $counter; ?>').change(function(){
                    $.fn.jCropSetSelectCoord(<?php echo $counter; ?>);
                });
                $('input[type=text]#y1_<?php echo $counter; ?>').change(function(){
                    $.fn.jCropSetSelectCoord(<?php echo $counter; ?>);
                });
                $('input[type=text]#x2_<?php echo $counter; ?>').change(function(){
                    $.fn.jCropSetSelectCoord(<?php echo $counter; ?>);
                });
                $('input[type=text]#y2_<?php echo $counter; ?>').change(function(){
                    $.fn.jCropSetSelectCoord(<?php echo $counter; ?>);
                });
                $('input[type=text]#w_<?php echo $counter; ?>').change(function(){
                    $.fn.jCropSetSelectSize(<?php echo $counter; ?>);
                });
                $('input[type=text]#h_<?php echo $counter; ?>').change(function(){
                    $.fn.jCropSetSelectSize(<?php echo $counter; ?>);
                });
            });
            
		<?php endif; ?>
	});
</script>
<div class="control-group control-image" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (strpos(strtolower($validation), 'not_empty') !== FALSE?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls">
		<?php
			echo $this->Html->image('upload/'.($crop==2?'':'thumb/').$value.'.'.(empty($value)?'jpg':$myImageTypeList[$value]),array('id'=>'myEditCoverImage_'.$counter));
		?>
	</div>
	<div class="controls" style="margin-top:10px">
		<?php
			if($crop == 2)
			{
				?>
				<div id="imageinfo_<?php echo $counter; ?>" class="info" <?php echo ($value==0?'style="display:none;"':''); ?>>
			      <label>X1 <input type="text" size="4" id="x1_<?php echo $counter; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][x1]" /></label>
			      <label>Y1 <input type="text" size="4" id="y1_<?php echo $counter; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][y1]" /></label>
			      <label>X2 <input type="text" size="4" id="x2_<?php echo $counter; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][x2]" /></label>
			      <label>Y2 <input type="text" size="4" id="y2_<?php echo $counter; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][y2]" /></label>
			      <label>W <input type="text" size="4" id="w_<?php echo $counter; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][w]" /></label>
			      <label>H <input type="text" size="4" id="h_<?php echo $counter; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][h]" /></label>
			    </div>
				<?php
			}
		?>
		<?php echo $this->Form->Html->link('Change',array('controller'=>'entries','action'=>'media_popup_single','1','myEditCoverImage_'.$counter,(empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']),'admin'=>false),array('class'=>'btn btn-info get-from-library'));	?>
		<a class="btn btn-danger" onclick="javascript : $.fn.removePicture(<?php echo $counter; ?>,<?php echo $crop; ?>);" href="javascript:void(0)">Remove</a>
		<p class="help-block"><?php echo $p; ?></p>
	</div>
	<input class="<?php echo $shortkey; ?>" type="hidden" value="<?php echo $value; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]" id="myEditCoverId_<?php echo $counter; ?>"/>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
	<?php if(!empty($childImage) && $childImage['Entry']['parent_id'] > 0): ?>
	<input type="hidden" value="<?php echo $childImage['Entry']['id']; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][childImageId]"/>
    <?php endif; ?>
</div>
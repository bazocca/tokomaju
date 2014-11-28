<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	
	$browse_slug = get_slug($shortkey);
	$metaDetails = array();
	if(empty($_POST['data'][$model][$counter]['value']) && !empty($value))
	{
		$metaDetails = $this->Get->meta_details($value , $browse_slug);
	}
	
	$required = "";
	if(strpos(strtolower($validation), 'not_empty') !== FALSE)
	{
		$required = 'REQUIRED';
	}
?>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (!empty($required)?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls">
		
		<input <?php echo $required; ?> <?php echo (empty($display)?'id="'.$browse_slug.'"':''); ?> class="targetID input-large" placeholder="<?php echo $placeholder; ?>" value="<?php echo (isset($_POST['data'][$model][$counter]['temp'])?$_POST['data'][$model][$counter]['temp']:$metaDetails['Entry']['title']); ?>" type="text" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][temp]" readonly="true"/>
        <?php
            echo $this->Form->Html->link('Browse',array('controller'=>'entries','action'=>$browse_slug,'admin'=>true,'?'=>array('popup'=>'init')),array('class'=>'btn btn-info get-from-table'));
        ?>
        <input class="<?php echo $shortkey; ?>" type="hidden" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value]" value="<?php echo (isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:$value); ?>"/>
        
        <?php if(empty($required)): ?>
            <a class="btn btn-danger removeID" href="javascript:void(0)">Clear</a>  
        <?php endif; ?>
		
		<p class="help-block">
			Want to create new one? Click <?php echo $this->Form->Html->link('here<img alt="External Icon" src="'.$imagePath.'img/external-icon.gif">',array('controller'=>'entries','action'=>$browse_slug.'/add'),array("target"=>"SingleSecondaryWindowName","onclick"=>"javascript:openRequestedSinglePopup(this.href); return false;","escape"=>false)); ?>.<br/>
            <?php echo $p; ?>
        </p>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>
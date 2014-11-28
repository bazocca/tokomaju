<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	$var_stream = $shortkey.'_stream';	
	$browse_slug = get_slug($shortkey);
?>
<script type="text/javascript">
	var <?php echo $var_stream; ?>;
			
	$(document).ready(function(){
		$('div.<?php echo $browse_slug; ?>-group').closest('div.control-group').find('a.add-raw').click(function(){
            var content = '<div class="row-fluid <?php echo $browse_slug; ?>-detail bottom-spacer">';            
            content += '<input REQUIRED id="<?php echo $browse_slug; ?>'+<?php echo $var_stream; ?>+'" class="input-xlarge" type="text" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][temp][]" readonly="true"/>';            		            
            content += '&nbsp;<a class="btn btn-info get-from-table" href="'+linkpath+'admin/entries/<?php echo $browse_slug; ?>?popup=init&stream='+<?php echo $var_stream; ?>+'">Browse</a>';            
            content += '<input class="<?php echo $shortkey; ?>" type="hidden" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][value][]" />';
            content += '&nbsp;<a class="btn btn-danger del-raw" href="javascript:void(0)"><i class="icon-trash icon-white"></i></a>';
            content += '</div>';
            
            $('div.<?php echo $browse_slug; ?>-group').append(content);
            <?php echo $var_stream; ?>++;
            $('.get-from-table').colorbox({reposition: false});
        });
        
		($('#colorbox').length>0&&$('#colorbox').is(':visible')?$('#colorbox').children().last().children():$(document)).on("click",'div.<?php echo $browse_slug; ?>-group a.del-raw',function(e){
            $(this).closest('div.<?php echo $browse_slug; ?>-detail').animate({opacity : 0 , height : 0, marginBottom : 0},1000,function(){
                $(this).detach();
            });
        });
	});
</script>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>
	<label class="control-label" <?php echo (strpos(strtolower($validation), 'not_empty') !== FALSE?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls <?php echo $browse_slug; ?>-group">
		<?php
			$raw_stream = 1;
			
			// Check data POST first !!
			if(!empty($_POST['data'][$model][$counter]['value']))
			{
				foreach ($_POST['data'][$model][$counter]['value'] as $salekey => $salevalue) 
				{
					if(!empty($salevalue))
					{
						echo '<div class="row-fluid '.$browse_slug.'-detail bottom-spacer">';					
						echo '<input REQUIRED id="'.$browse_slug.$raw_stream.'" class="input-xlarge" type="text" name="data['.$model.']['.$counter.'][temp][]" value="'.$_POST['data'][$model][$counter]['temp'][$salekey].'" readonly="true"/>';					
						echo '&nbsp;'.$this->Form->Html->link('Browse',array('controller'=>'entries','action'=>$browse_slug,'admin'=>true,'?'=>array('popup'=>'init', 'stream'=>$raw_stream)),array('class'=>'btn btn-info get-from-table'));
	                    echo '<input class="'.$shortkey.'" type="hidden" name="data['.$model.']['.$counter.'][value][]" value="'.$salevalue.'"/>';
	                    echo '&nbsp;<a class="btn btn-danger del-raw" href="javascript:void(0)"><i class="icon-trash icon-white"></i></a>';					
						echo '</div>';
						
						$raw_stream++;
					}
				}
			}
			else if(!empty($value))
			{
				$sale = explode('|', $value);
				foreach ($sale as $salekey => $salevalue) 
				{
					$saledetail = $this->Get->meta_details($salevalue , $browse_slug);
					
					if(!empty($saledetail))
					{
						echo '<div class="row-fluid '.$browse_slug.'-detail bottom-spacer">';
					
						if(!empty($saledetail['EntryMeta']['name']))
						{
							echo '<input REQUIRED id="'.$browse_slug.$raw_stream.'" class="input-xlarge" type="text" name="data['.$model.']['.$counter.'][temp][]" value="'.$saledetail['EntryMeta']['name'].' ('.$saledetail['Entry']['title'].')'.'" readonly="true"/>';
						}
						else
						{
							echo '<input REQUIRED id="'.$browse_slug.$raw_stream.'" class="input-xlarge" type="text" name="data['.$model.']['.$counter.'][temp][]" value="'.$saledetail['Entry']['title'].'" readonly="true"/>';
						}
						
						echo '&nbsp;'.$this->Form->Html->link('Browse',array('controller'=>'entries','action'=>$browse_slug,'admin'=>true,'?'=>array('popup'=>'init', 'stream'=>$raw_stream)),array('class'=>'btn btn-info get-from-table'));
	                    echo '<input class="'.$shortkey.'" type="hidden" name="data['.$model.']['.$counter.'][value][]" value="'.$saledetail['Entry']['slug'].'"/>';
	                    echo '&nbsp;<a class="btn btn-danger del-raw" href="javascript:void(0)"><i class="icon-trash icon-white"></i></a>';
						
						echo '</div>';
						
						$raw_stream++;
					}
				}
			}
		?>
	</div>
	
	<script>
        <?php echo $var_stream; ?> = <?php echo $raw_stream; ?>;
        
        // if NO browse record displayed, then show it one !!
        <?php if($raw_stream == 1): ?>
$(document).ready(function(){
	$('div.<?php echo $browse_slug; ?>-group').closest('div.control-group').find('a.add-raw').click();
});
        <?php endif; ?>
    </script>
    
	<div class="controls">
		<a href="javascript:void(0)" class="add-raw" style="text-decoration: underline;">Add a <?php echo str_replace('_', ' ', $shortkey); ?></a>
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
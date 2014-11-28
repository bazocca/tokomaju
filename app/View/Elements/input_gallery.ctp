<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );

	$required = "";
	if(strpos(strtolower($validation), 'not_empty') !== FALSE)
	{
		$required = 'REQUIRED';
	}
?>
<script>
	$(document).ready(function(){
        // print total pictures...
        $('div#<?php echo $key; ?>').prevAll('.galleryCount:first').find('span').html( $('div#<?php echo $key; ?>').find('div.photo').length );

        // cek validation ...
		<?php if(!empty($required)): ?>
		$('form').submit(function(e){
			if( !$.trim($('div#<?php echo $key; ?>').html()) )
			{
				$('div#<?php echo $key; ?>').prevAll('a.get-from-library:first').focus();
				alert('Field <?php echo strtoupper(string_unslug($shortkey)); ?> could not be empty!');
				return false;
			}
		});
		<?php endif; ?>		
	});
</script>
<?php
	echo '<span class="galleryCount" '.(!empty($required)?'style="color: red;"':'').'>'.string_unslug($shortkey).' Pictures (<span></span>)</span>';
    echo $this->Form->Html->link('Add Picture',array('action'=>'media_popup_single',1,'myInputWrapper',$key,'admin'=>false),array('class'=>'btn btn-inverse fr get-from-library'));

    if(!empty($p))
	{
		echo '<p class="help-block">'.$p.'</p>';
	}
    
    echo '<div class="inner-content pictures '.$shortkey.'" id="'.$key.'">';
    if(!empty($myEntry))
    {
        foreach ($myEntry['ChildEntry'] as $index => $findDetail)
        {
            $findDetail = $findDetail['Entry']; // SPECIAL CASE, COZ IT'S BEEN MODIFIED IN CONTROLLER !!
            if($findDetail['entry_type'] == $key)
            {
                ?>
                    <div class="photo">
                        <div class="image">
                            <?php echo $this->Html->image('upload/thumb/'.$findDetail['main_image'].'.'.$myImageTypeList[$findDetail['main_image']], array('width'=>150,'alt'=>$findDetail['title'],'title'=>$findDetail['title'])); ?>
                        </div>
                        <div class="description">
                            <p><?php echo $findDetail['title']; ?></p>
                            <a href="javascript:void(0)" onclick="javascript:deleteChildPic(this);" class="icon-remove icon-white"></a>
                        </div>
                        <input type="hidden" value="<?php echo $findDetail['main_image']; ?>" name="data[Entry][fieldimage][<?php echo $key; ?>][]" />
                    </div>                          
                <?php                            
            }
        }
    }
    echo '</div>';
?>
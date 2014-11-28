<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
?>
<div class="inner-header">
	<?php
		if(count($mySetting['language']) > 1)
		{
			?>
	<div class="btn-group lang-selector">
		<a id="lang_identifier" class="btn" href="#"><?php echo strtoupper(isset($_POST['data']['language'])?$_POST['data']['language']:(empty($lang)?substr((empty($myEntry)?$mySetting['language'][0]:$myEntry['Entry']['lang_code']), 0,2):$lang)); ?></a>
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span>&nbsp;</a>
		<ul class="dropdown-menu">
		<?php
			foreach ($mySetting['language'] as $key => $value) 
			{
				$default_lang = strtolower(substr($mySetting['language'][0], 0,2));
				$this_lang = strtolower(substr($value, 0,2));
				if(empty($myParentEntry) || !empty($myParentEntry) && !empty($parent_language[$this_lang]))
				{	
					echo '<li>';

					$extensionLang = array();	
					if(!empty($myParentEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug'])
					{
						$extensionLang['type'] = $myChildType['Type']['slug'];
					}
					if(empty($language_link[$this_lang]))
					{
						$extensionLang['lang'] = $this_lang;
					}					
					echo $this->Form->Html->link($value,(empty($myEntry)?"#":array('controller'=>$myType['Type']['slug'].(empty($myParentEntry)?'':'/'.$parent_language[$this_lang]),'action'=>'edit',(empty($language_link[$this_lang])?(empty($language_link[$default_lang])? $language_link[substr($myEntry['Entry']['lang_code'],0,2)]:$language_link[$default_lang]):$language_link[$this_lang]),'?'=>$extensionLang)),array("class"=>"ajax_myform"));
					
					echo '</li>';
				}
			}					
		?>
		</ul>
	</div>		
			<?php
		}
	?>
	<div class="thumbs">
		<?php
			echo $this->Html->image('upload/thumb/'.(isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value'].'.'.$myImageTypeList[$_POST['data']['Entry'][2]['value']]:(empty($myEntry)?'0.jpg':$myEntry['Entry']['main_image'].'.'.$myImageTypeList[$myEntry['Entry']['main_image']])),array('id'=>'mySelectCoverAlbum'));
		?>			
	</div>
	<div class="title">
		<?php
			if(empty($myEntry))
			{
				echo '<h2>ADD '.(empty($myChildType)?$myType['Type']['name']:$myParentEntry['Entry']['title'].' '.$myChildType['Type']['name']).'</h2>';
			}
			else
			{
				echo '<h2 id="form-title-entry">'.(empty($lang)?'':'TRANSLATE (').$myEntry['Entry']['title'].(empty($lang)?'':')').'</h2>';
				?>
				<p id="id-title-description" class="title-description"></p>
				<?php
			}
		?>
		<div class="change-pic">
			<i class="icon-cog"></i>			
			<?php 
				if( (isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value']:$myEntry['Entry']['main_image']) > 0)
				{
				?>
					<a href='javascript:void(0)' class='remove' onclick='javascript : $.fn.removePicture();'>Remove /</a> <?php echo $this->Form->Html->link('Change Cover',array('action'=>'media_popup_single',1,'mySelectCoverAlbum',(empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']),'admin'=>false),array('class'=>'get-from-library')); ?>
				<?php
				}
				else
				{
				?>
					<a href='javascript:void(0)' class='remove' onclick='javascript : $.fn.removePicture();' style="display:none">Remove / </a> <?php echo $this->Form->Html->link('Select Cover',array('action'=>'media_popup_single',1,'mySelectCoverAlbum',(empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']),'admin'=>false),array('class'=>'get-from-library')); ?>
				<?php
				}
			?>			
		</div>
	</div>
</div>
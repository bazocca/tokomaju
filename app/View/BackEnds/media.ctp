<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$_SESSION['now'] = str_replace('&amp;','&',htmlentities($_SERVER['REQUEST_URI']));
	if($isAjax == 0)
	{
		?>
<?php
	$this->Html->addCrumb($myType['Type']['name'], '/admin/entries/'.$myType['Type']['slug']);	
?>
<script type="text/javascript">
	$("a#<?php echo $myType['Type']['slug']; ?>").addClass("active");
	$(document).ready(function(){
		// media sortable
		$("div.list").sortable({ opacity: 0.6, cursor: 'move',
			stop: function(event, ui) {
				var tmp = '';
				// construct
				$('div.list div.photo').each(function(){
					tmp += $(this).attr('alt') + ',';
				});				
				$.ajaxSetup({cache: false});
				$.post(site+'entries/reorder_list',{
					src_order: $('input[type=hidden]#determine').val(),
					dst_order: tmp
				});
			}
		});
	});
</script>
<div class="inner-header">
	<div class="title">
		<h2><?php echo $totalList.' '.strtoupper(empty($myEntry)?$myType['Type']['name']:$myEntry['Entry']['title']); ?></h2>
		<?php
			if(!empty($lastModified))
			{
				?>
				<p id="id-title-description" class="title-description">Last updated by <a href="#"><?php echo (empty($lastModified['AccountModifiedBy']['username'])?$lastModified['AccountModifiedBy']['email']:$lastModified['AccountModifiedBy']['username']).'</a> on '.date_converter($lastModified['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></p>
				<?php
			}
		?>
	</div>
	<?php echo $this->Form->Html->link('Upload Image',array('action'=>'upload_popup',(empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']),'admin'=>false),array('class'=>'btn btn-primary fr get-from-library')); ?>
</div>		
		<?php
		echo '<div class="inner-content">';
		echo '<div id="ajaxed" class="list">';
	}
	else
	{
		?>
			<script type="text/javascript">
				$(document).ready(function(){
					$('#cmsAlert').css('display' , 'none');		
				});
			</script>
		<?php
	}
?>
<?php
	$orderlist = "";
	foreach ($myList as $p):
	$orderlist .= $p['Entry']['sort_order'].",";
		?>
<div class="photo" alt="<?php echo $p['Entry']['id']; ?>">
	<div class="image">
		<?php echo $this->Html->image('upload/thumb/'.$p['Entry']['id'].'.'.$myImageTypeList[$p['Entry']['id']], array('alt' => $p['Entry']['title'],'title' => $p['Entry']['title'],'width'=>150)); ?>
	</div>
	<div class="description">
		<p><?php echo $p['Entry']['title']; ?></p>
		<?php echo $this->Form->Html->link('',array('action'=>'mediaused',$p['Entry']['id'],'admin'=>false),array('class'=>'icon-remove icon-white' , 'id'=>'myDeleteMedia')); ?>			
	</div>
	<input type='hidden' value ='<img style="width:150px" alt="<?php $p['Entry']['title']; ?>" title="<?php echo $p['Entry']['title']; ?>" src="<?php echo $imagePath; ?>img/upload/<?php echo $p['Entry']['id'].'.'.$myImageTypeList[$p['Entry']['id']]; ?>" />'>
</div>	
<?php endforeach; ?>
<input type="hidden" id="determine" value="<?php echo $orderlist; ?>" />
<div class="clear"></div>
<input type="hidden" value="<?php echo $countPage; ?>" size="50" id="myCountPage"/>
<input type="hidden" value="<?php echo $left_limit; ?>" size="50" id="myLeftLimit"/>
<input type="hidden" value="<?php echo $right_limit; ?>" size="50" id="myRightLimit"/>
<!-- myTypeSlug is for media upload settings purpose !! -->
<input type="hidden" value="<?php echo $myType['Type']['slug']; ?>" size="100" id="myTypeSlug"/>
<?php
	if($isAjax == 0)
	{
		echo '</div>';
		echo $this->element('admin_footer');
		echo '</div>';
	}
?>

<?php if($totalList <= 0){ ?>
	<div class="empty-state item">
		<div class="wrapper-empty-state">
			<div class="pic"></div>
			<h2>No Items Found!</h2>
			<!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ante nisi, fermentum eu mattis id, vehicula dapibus sapien.</p>-->
			<?php 
				echo $this->Form->Html->link('Get Started',array('action'=>'upload_popup',(empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']),'admin'=>false),array('class'=>'btn btn-primary get-from-library')); 
			?>
		</div>
	</div>
<?php } ?>
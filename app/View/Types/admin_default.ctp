<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$_SESSION['now'] = str_replace('&amp;','&',htmlentities($_SERVER['REQUEST_URI']));
	if($isAjax == 0)
	{
		$this->Html->addCrumb('Master', '#');
		$this->Html->addCrumb('Database', '/admin/master/types');
		if(!empty($myParentType))
		{
			$this->Html->addCrumb($myParentType['Type']['name'], '/admin/master/types/'.$myParentType['Type']['slug']);
		}
		?>
		<script type="text/javascript">
			$("a#master").addClass("active");
		</script>
		
		<div class="inner-header">	
			<div id="child-menu">
				<?php echo $this->Form->Html->link('Add Database',array('controller'=>'master', 'action'=>'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'add'),array('class'=>'btn btn-primary fr right-btn')); ?>
				<div class="btn-group fr">
					<?php echo $this->Form->Html->link('Databases',array('controller'=>'master', 'action'=>'types'), array('class'=>'btn active')); ?>
					<?php echo $this->Form->Html->link('Roles',array('controller'=>'master', 'action'=>'roles'), array('class'=>'btn')); ?>
				</div>
			</div>		
			<div class="title">
				<h2><?php echo $totalList.' '.strtoupper(empty($myParentType)?'':$myParentType['Type']['name'].' ').'DATABASES'; ?></h2>
				<p id="id-title-description" class="title-description">
					<?php
						if(empty($myParentType))
						{
							echo 'Last updated by <a href="#">'.(empty($lastModified['AccountModifiedBy']['username'])?$lastModified['AccountModifiedBy']['email']:$lastModified['AccountModifiedBy']['username']).'</a> at '.date_converter($lastModified['Type']['modified'], $mySetting['date_format'] , $mySetting['time_format']);
						}
						else
						{
							echo $myParentType['Type']['description'];
						}
					?>
				</p>
			</div>
		</div>		
		<div class="inner-content">
		<div id="ajaxed">		
		<?php
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
<table id="myTableList" class="list" style="cursor: default;">
	<tr>
		<th>TITLE</th>
		<th><?php echo (empty($myParentType)?'CHILDREN':''); ?></th>
		<th>LAST MODIFIED</th>
		<th></th>
	</tr>
	
	<?php		
		foreach ($myList as $value):
	?>	
	<tr>
		<td>
			<h5 style="margin: 0;"><?php echo $this->Form->Html->link($value['Type']['name'],array('controller'=>'master' , 'action'=>'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'edit',$value['Type']['slug'])); ?></h5>
			<p>
				<?php
					if(!empty($value['Type']['description']))
					{
						$description = $value['Type']['description'];
						echo (strlen($description) > 30? substr($description,0,30)."..." : $description);
					}
				?>
			</p>
		</td>
		<td>
			<?php
				if(empty($myParentType)) // if this is a parent Type !!
				{
					echo '<span class="badge badge-info">'.$this->Form->Html->link($value['Type']['count'],array('controller'=>'master', 'action'=>'types',$value['Type']['slug'])).'</span>';
				}	
			?>
		</td>
		<td><?php echo date_converter($value['Type']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></td>
		<td>						
			<a href="javascript:void(0)" onclick="show_confirm('Are you sure want to delete <?php echo $value['Type']['name']; ?> ?','types/delete/<?php echo $value['Type']['id']; ?>')" class="btn btn-danger"><i class="icon-trash icon-white"></i></a>
		</td>
	</tr>
	
	<?php
		endforeach;
	?>
</table>
<div class="clear"></div>
<input type="hidden" value="<?php echo $countPage; ?>" size="50" id="myCountPage"/>
<input type="hidden" value="<?php echo $left_limit; ?>" size="50" id="myLeftLimit"/>
<input type="hidden" value="<?php echo $right_limit; ?>" size="50" id="myRightLimit"/>
<?php
	if($isAjax == 0)
	{
		echo '</div>';
		if($totalList > 0)
		{
			?>
				<!-- per 15 content -->
				<div class="pagination fr">
					<ul>
						<?php
							echo '<li id="myPagingFirst" class="'.($paging<=1?"disabled":"").'">';
							echo $this->Form->Html->link("First",array("controller"=>"master" , "action"=>"types".(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'index' , 1) , array("class"=>"ajax_mypage"));
							echo '</li>';
							
							echo '<li id="myPagingPrev" class="'.($paging<=1?"disabled":"").'">';
							echo str_replace('amp;', '', $this->Form->Html->link("&laquo;",array("controller"=>"master" , "action"=>"types".(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'index' , $paging-1), array("class"=>"ajax_mypage")));
							echo '</li>';
							
							for ($i = $left_limit , $index = 1; $i <= $right_limit; $i++ , $index++)
							{
								echo '<li id="myPagingNum'.$index.'" class="'.($i==$paging?"active":"").'">';
								echo $this->Form->Html->link($i,array("controller"=>"master" , "action"=>"types".(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'index' , $i) , array("class"=>"ajax_mypage"));				
								echo '</li>';
							}
						
							echo '<li id="myPagingNext" class="'.($paging>=$countPage?"disabled":"").'">';
							echo str_replace("amp;", "", $this->Form->Html->link("&raquo;",array("controller"=>"master" , "action"=>"types".(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'index' , $paging+1) , array("class"=>"ajax_mypage")));
							echo '</li>';
							
							echo '<li id="myPagingLast" class="'.($paging>=$countPage?"disabled":"").'">';
							echo $this->Form->Html->link("Last",array("controller"=>"master" , "action"=>"types".(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'index' , $countPage), array("class"=>"ajax_mypage"));
							echo '</li>';
						?>
					</ul>
				</div>
			<?php
		}
		echo '</div>';
	}
?>
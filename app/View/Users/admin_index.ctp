<?php
	if(empty($popup))
	{
		$_SESSION['now'] = str_replace('&amp;','&',htmlentities($_SERVER['REQUEST_URI']));
	}
	if($isAjax == 0)
	{
		?>
<!--      ----------------------------------------------------------------------------------------------------------		 -->
<?php
	$this->Html->addCrumb('Users', '/admin/users');
?>
<script type="text/javascript">
	$(document).ready(function(){
		if("<?php echo (empty($popup)?'kosong':'berisi'); ?>"=="kosong")
		{
			$("a#users").addClass("active");
		}
		$('input#searchMe').change(function(){
			$('a.searchMeLink').click();
		});		
	});
</script>

<div class="inner-header <?php echo (empty($popup)?'':'layout-header-popup'); ?> row-fluid">
	<div class="span5">
		<div class="title">
			<h2>USERS</h2>
			<p id="id-title-description" class="title-description">
				<?php
					if(!empty($lastModified))
					{
						echo 'Last updated by <a href="#">'.(empty($lastModified['AccountModifiedBy']['username'])?$lastModified['AccountModifiedBy']['email']:$lastModified['AccountModifiedBy']['username']).'</a> at '.date_converter($lastModified['User']['modified'], $mySetting['date_format'] , $mySetting['time_format']);
					}
				?>
			</p>
		</div>
	</div>
	<div class="span7">
		<?php echo (empty($popup)?$this->Form->Html->link('Add User',array('action'=>'add'),array('class'=>'btn btn-primary fr right-btn')):''); ?>
		<div class="input-prepend" style="margin-right: 5px;">
			<span class="add-on" style="margin-right: 3px; margin-top : 9px;">
				<?php
					echo $this->Form->Html->link('<i class="icon-search"></i>',array("action"=>"index","1",'?'=>(empty($popup)?'':array('popup'=>'ajax'))) , array("class"=>"ajax_mypage searchMeLink","escape"=>false));
				?>
			</span>
			<input style="width: 160px;" id="searchMe" class="span2" type="text" placeholder="search item here...">
		</div>
	</div>
</div>

<div class="inner-content <?php echo (empty($popup)?'':'layout-content-popup'); ?>" id="inner-content">
	<div class="autoscroll" id="ajaxed">
<!--      ----------------------------------------------------------------------------------------------------------		 -->		
		<?php
	}
	else
	{
		if($search == "yes")
		{
			echo '<div class="autoscroll" id="ajaxed">';
		}
		?>
			<script type="text/javascript">
				$(document).ready(function(){
					$('#cmsAlert').css('display' , 'none');		
				});
			</script>
		<?php
	}
?>
<script>
	$(document).ready(function(){
		if("<?php echo (empty($popup)?'kosong':'berisi'); ?>"=="berisi")
		{	
			$('table#myTableList tr').css('cursor' , 'pointer');
			$("table#myTableList tr").click(function(){
				$("input#user").val( $(this).find("h5.name-code").html().trim() );
				$("input#user").nextAll('input[type=hidden]').val( $(this).find("input[type=hidden].id-code").val() );
				$.colorbox.close();
			});
		}
	});
</script>
<?php if($totalList <= 0){ ?>
	<div class="empty-state item">
		<div class="wrapper-empty-state">
			<div class="pic"></div>
			<h2>No Items Found!</h2>
			<?php echo (empty($popup)?$this->Form->Html->link('Get Started',array('action'=>'add'),array('class'=>'btn btn-primary')):''); ?>
		</div>
	</div>
<?php }else{ ?>
<table id="myTableList" class="list">
	<thead>
		<tr>
			<th>NAME</th>
			<th>GENDER</th>
			<th>ADDRESS</th>
			<th>ZIP CODE</th>
			<th>CITY</th>
			<th>PHONE</th>
			<th>MOBILE PHONE</th>
			<th>DATE OF BIRTH</th>
			<th>LAST MODIFIED</th>
			<th>STATUS</th>
			<?php
				if(empty($popup))
				{
					echo "<th></th>";
				}
			?>
		</tr>
	</thead>
	<tbody>
	<?php		
		foreach ($myList as $value):
	?>	
	<tr>
		<td>
			<input class="id-code" type="hidden" value="<?php echo $value['User']['id']; ?>" />
			<h5 class="name-code">
				<?php
					if(!empty($popup))
					{
						echo $value['User']['firstname']." ".$value['User']['lastname'];
					}
					else 
					{
						echo $this->Form->Html->link($value['User']['firstname']." ".$value['User']['lastname'],array('action'=>'edit', $value['User']['id']));
					} 
				?>
			</h5>
		</td>
		<td><?php echo string_unslug($value['UserMeta']['gender']); ?></td>
		<td><?php echo (empty($value['UserMeta']['address'])?"-":$value['UserMeta']['address']); ?></td>
		<td><?php echo (empty($value['UserMeta']['zip_code'])?"-":$value['UserMeta']['zip_code']); ?></td>
		<td><?php echo (empty($value['UserMeta']['city'])?"-":$value['UserMeta']['city']); ?></td>
		<td><?php echo (empty($value['UserMeta']['phone'])?"-":$value['UserMeta']['phone']); ?></td>
		<td><?php echo (empty($value['UserMeta']['mobile_phone'])?"-":$value['UserMeta']['mobile_phone']); ?></td>		
		<td><?php echo sprintf("%02d" , $value['UserMeta']['dob_day']).' '.date("F", mktime(0, 0, 0, $value['UserMeta']['dob_month'], 10)).' '.$value['UserMeta']['dob_year']; ?></td>				
		<td><?php echo date_converter($value['User']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></td>
		<td <?php echo (empty($popup)?'':'class="offbutt"'); ?>>
			<span class="label <?php echo $value['User']['status']==0?'label-important':'label-success'; ?>">
				<?php
					if($value['User']['status'] == 0)
						echo "Disabled";
					else
						echo "Active";
				?>
			</span>
		</td>
		<?php
			if(empty($popup))
			{
				echo "<td>";
				$confirm = null;
				$targetURL = 'users/change_status/'.$value['User']['id'];
				if($value['User']['status'] == 0)
				{
					echo '<a href="javascript:void(0)" onclick="changeLocation(\''.$targetURL.'\')" class="btn btn-info"><i class="icon-ok icon-white"></i></a>';					
				}
				else
				{
					$confirm = 'Are you sure want to disable this user ?';
					echo '<a href="javascript:void(0)" onclick="show_confirm(\''.$confirm.'\',\''.$targetURL.'\')" class="btn btn-warning"><i class="icon-ban-circle icon-white"></i></a>';					
				}
				?>
				<a href="javascript:void(0)" onclick="show_confirm('Are you sure want to delete this user ?','users/delete/<?php echo $value['User']['id']; ?>')" class="btn btn-danger"><i class="icon-trash icon-white"></i></a>
				<?php
				echo "</td>";
			}
		?>
	</tr>	
	<?php
		endforeach;
	?>
	</tbody>
</table>
<div class="clear"></div>
<input type="hidden" value="<?php echo $countPage; ?>" size="50" id="myCountPage"/>
<input type="hidden" value="<?php echo $left_limit; ?>" size="50" id="myLeftLimit"/>
<input type="hidden" value="<?php echo $right_limit; ?>" size="50" id="myRightLimit"/>

<?php
	if($isAjax == 0 || $isAjax == 1 && $search == "yes")
	{
		?>
<!--      ----------------------------------------------------------------------------------------------------------		 -->
	</div>	
	<?php
		if($totalList > 0){
			?>
				<!-- per 15 content -->
				<div class="pagination fr">
					<ul>
						<?php
							echo '<li id="myPagingFirst" class="'.($paging<=1?"disabled":"").'">';
							echo $this->Form->Html->link("First",array("action"=>"index",'1','?'=>(empty($popup)?'':array('popup'=>'ajax'))) , array("class"=>"ajax_mypage"));
							echo '</li>';
							
							echo '<li id="myPagingPrev" class="'.($paging<=1?"disabled":"").'">';
							echo str_replace("amp;", "", $this->Form->Html->link("&laquo;",array("action"=>"index",($paging-1),'?'=>(empty($popup)?'':array('popup'=>'ajax'))), array("class"=>"ajax_mypage")));
							echo '</li>';
							
							for ($i = $left_limit , $index = 1; $i <= $right_limit; $i++ , $index++)
							{
								echo '<li id="myPagingNum'.$index.'" class="'.($i==$paging?"active":"").'">';
								echo $this->Form->Html->link($i,array("action"=>"index",$i,'?'=>(empty($popup)?'':array('popup'=>'ajax'))) , array("class"=>"ajax_mypage"));				
								echo '</li>';
							}
						
							echo '<li id="myPagingNext" class="'.($paging>=$countPage?"disabled":"").'">';
							echo str_replace("amp;", "", $this->Form->Html->link("&raquo;",array("action"=>"index",($paging+1),'?'=>(empty($popup)?'':array('popup'=>'ajax'))) , array("class"=>"ajax_mypage")));
							echo '</li>';
							
							echo '<li id="myPagingLast" class="'.($paging>=$countPage?"disabled":"").'">';
							echo $this->Form->Html->link("Last",array("action"=>"index",$countPage,'?'=>(empty($popup)?'':array('popup'=>'ajax'))), array("class"=>"ajax_mypage"));
							echo '</li>';
						?>
					</ul>
				</div>
			<?php
		}
	?>
	<div class="clear"></div>
<!--      ----------------------------------------------------------------------------------------------------------		 -->		
		<?php
		echo ($isAjax==0?"</div>":"");
	}
?>

<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
        <?php if(empty($popup)): ?>
            if(window.opener != null && window.name.length > 0)
            {
            	setTimeout("window.close()" , delayCloseWindow);
            }
        <?php endif; ?>
    });         
</script>
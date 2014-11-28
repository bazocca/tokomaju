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
	$this->Html->addCrumb('Accounts', '/admin/accounts');
?>
<script type="text/javascript">
	$(document).ready(function(){
		if("<?php echo (empty($popup)?'kosong':'berisi'); ?>"=="kosong")
		{
			$("a#accounts").addClass("active");
		}
		$('input#searchMe').change(function(){
			$('a.searchMeLink').click();
		});		
	});
</script>

<div class="inner-header <?php echo (empty($popup)?'':'layout-header-popup'); ?> row-fluid">
	<div class="span5">
		<div class="title">
			<h2>ACCOUNTS</h2>
			<p id="id-title-description" class="title-description">
			<?php
				if(!empty($lastModified))
				{
					echo 'Last updated by <a href="#">'.(empty($lastModified['ParentModifiedBy']['username'])?$lastModified['ParentModifiedBy']['email']:$lastModified['ParentModifiedBy']['username']).'</a> at '.date_converter($lastModified['Account']['modified'], $mySetting['date_format'] , $mySetting['time_format']);
				}
			?>
			</p>
		</div>
	</div>
	<div class="span7">
		<?php echo (empty($popup)?$this->Form->Html->link('Add Account',array('action'=>'add'),array('class'=>'btn btn-primary fr right-btn')):''); ?>
		<div class="input-prepend" style="margin-right: 5px;">
			<span class="add-on" style="margin-right: 3px; margin-top : 9px;">
				<?php
					$cakeUrl = array("action"=>"index","1");
					if(!empty($popup))
					{
						$cakeUrl['?'] = array('popup'=>'ajax' , 'role' => $popupRole);
					}
					echo $this->Form->Html->link('<i class="icon-search"></i>', $cakeUrl , array("class"=>"ajax_mypage searchMeLink","escape"=>false));
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
			// just for choose employee !!
			$('table#myTableList tr').css('cursor' , 'pointer');
			$("table#myTableList tr").click(function(){
				// $("input#employee").val( $(this).find("td.name-code").html().trim() );
				// $("input#employee").nextAll('input[type=hidden]').val( $(this).find("input[type=hidden].id-code").val() );
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
			<th>USERNAME</th>
			<th>NAME</th>
			<?php
				if(empty($popup))
				{
					echo "<th>ROLE</th>";
				}
			?>
			<th>LAST LOGIN</th>
			<th>LAST MODIFIED</th>
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
			<input class="id-code" type="hidden" value="<?php echo $value['Account']['id']; ?>" />
			<h5>
				<?php
					if(!empty($popup))
					{
						echo $value['Account']['email'];
					}
					else 
					{
						echo $this->Form->Html->link($value['Account']['email'],array('action'=>'edit', $value['Account']['id']));
					} 
				?>
			</h5>
			<p><?php echo $value['Account']['username']; ?></p>
		</td>
		<td class="name-code">
			<?php
				echo $value['User']['firstname'] . ' ' . $value['User']['lastname']; 
			?>
		</td>
		<?php echo (empty($popup)?"<td>".$value['Role']['name']."</td>":""); ?>
		<td>
			<?php
				if(substr($value['Account']['last_login'], 0 , 4) == '0000')
				{
					echo '-';
				}
				else
				{
					echo date_converter($value['Account']['last_login'], $mySetting['date_format'] , $mySetting['time_format']);
				} 
			?>
		</td>
		<td <?php echo (empty($popup)?'':'class="offbutt"'); ?>><?php echo date_converter($value['Account']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></td>
		<?php
			if(empty($popup))
			{
				echo "<td>";
				?>
					<a href="javascript:void(0)" onclick="show_confirm('Are you sure want to delete this account ?','accounts/delete/<?php echo $value['Account']['id']; ?>')" class="btn btn-danger"><i class="icon-trash icon-white"></i></a>	
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
							echo $this->Form->Html->link("First",array("action"=>"index",'1','?'=>(empty($popup)?'':array('popup'=>'ajax' , 'role' => $popupRole))) , array("class"=>"ajax_mypage"));
							echo '</li>';
							
							echo '<li id="myPagingPrev" class="'.($paging<=1?"disabled":"").'">';
							echo str_replace("amp;", "", $this->Form->Html->link("&laquo;",array("action"=>"index",($paging-1),'?'=>(empty($popup)?'':array('popup'=>'ajax' , 'role' => $popupRole))), array("class"=>"ajax_mypage")));
							echo '</li>';
							
							for ($i = $left_limit , $index = 1; $i <= $right_limit; $i++ , $index++)
							{
								echo '<li id="myPagingNum'.$index.'" class="'.($i==$paging?"active":"").'">';
								echo $this->Form->Html->link($i,array("action"=>"index",$i,'?'=>(empty($popup)?'':array('popup'=>'ajax' , 'role' => $popupRole))) , array("class"=>"ajax_mypage"));				
								echo '</li>';
							}
						
							echo '<li id="myPagingNext" class="'.($paging>=$countPage?"disabled":"").'">';
							echo str_replace("amp;", "", $this->Form->Html->link("&raquo;",array("action"=>"index",($paging+1),'?'=>(empty($popup)?'':array('popup'=>'ajax' , 'role' => $popupRole))) , array("class"=>"ajax_mypage")));
							echo '</li>';
							
							echo '<li id="myPagingLast" class="'.($paging>=$countPage?"disabled":"").'">';
							echo $this->Form->Html->link("Last",array("action"=>"index",$countPage,'?'=>(empty($popup)?'':array('popup'=>'ajax' , 'role' => $popupRole))), array("class"=>"ajax_mypage"));
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
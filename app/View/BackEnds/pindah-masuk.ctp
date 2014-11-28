<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);
    // initialize $extensionPaging for URL Query ...
    $extensionPaging = array();	
	if(!empty($myEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug'])
	{
		$extensionPaging['type'] = $myChildType['Type']['slug'];
	}
	if(empty($popup))
	{
		$_SESSION['now'] = str_replace('&amp;','&',htmlentities($_SERVER['REQUEST_URI']));
	}
    else
    {
        $extensionPaging['popup'] = 'ajax';
    }
    // end of initialize $extensionPaging ...

	if($isAjax == 0)
	{
		echo $this->element('admin_header', array('extensionPaging' => $extensionPaging));
		echo '<div class="inner-content '.(empty($popup)?'':'layout-content-popup').'" id="inner-content">';
		echo '<div class="autoscroll" id="ajaxed">';
        ?>
        
<script type="text/javascript">
	$(document).ready(function(){        
        var content = '<div style="margin:0 !important" class="clear"></div>';
        content += '<div class="fr">';
        content += '<span style="vertical-align: 20%;padding-right: 5px;font-weight:bold;">Tanggal :</span> ';
        content += '<input id="tanggal" class="input-medium" type="date" />';
        content += '</div>';
        $('input#searchMe').closest('div').after(content);
        
		$('#tanggal').change(function(){
			$('a.searchMeLink').click();
		});
	});
</script>
        <?php
	}
	else
	{
		if($search == "yes")
		{
			echo '<div class="autoscroll" id="ajaxed">';
		}
		?>
			<script>
				$(document).ready(function(){
					$('#cmsAlert').css('display' , 'none');
				});
			</script>
		<?php
	}
?>
<script>
	$.fn.updateAttachButton = function(){
		if($('#attach-checked-data').length > 0)
		{
			var attach_status = false;
			$('input.check-record').each(function(i,el){
				if($(this).attr('checked'))
				{
					attach_status = true;
					return false;
				}
			});

			if(attach_status)
			{
				$('#attach-checked-data').removeClass('disabled');
			}
			else
			{
				$('#attach-checked-data').addClass('disabled');	
			}
		}
	}

	$(document).ready(function(){

		// attach checkbox on each record...
		if($('input#query-stream').length > 0 || <?php echo (empty($popup)?'true':'false'); ?>)
		{
			$('table#myTableList thead tr').prepend('<th><input type="checkbox" id="check-all" /></th>');
			$('table#myTableList tbody tr').each(function(i,el){
				$(this).prepend('<td style="min-width: 0px;"><input type="checkbox" class="check-record" value="'+$(this).attr('alt')+'" /></td>');
			});

			$('input#check-all').change(function(){
				$('input.check-record').attr('checked' , $(this).attr('checked')?true:false);
				$.fn.updateAttachButton();
			});
		}
		
		<?php if(empty($popup)): ?>
			$('table#myTableList tr').css('cursor' , 'default');

			// submit bulk action checkbox !!
			$('form#global-action').submit(function(){				
				var records = [];
				$('input.check-record').each(function(i,el){
					if($(el).attr('checked'))
					{
						records.push($(el).val());
					}
				});
				
				if(records.length > 0)
				{
					if(confirm('Are you sure to execute this BULK action ?'))
					{
						$(this).find('input#action-records').val( records.join(',') );
					}
					else
					{
						return false;
					}
				}
				else
				{
					alert('Please select the record first before doing action !!');
					return false;
				}
			});
			
			// ---------------------------------------------------------------------- >>>
			// FOR AJAX REASON !!
			// ---------------------------------------------------------------------- >>>
			$('p#id-title-description').html('Last updated by <a href="#"><?php echo (empty($lastModified['AccountModifiedBy']['username'])?$lastModified['AccountModifiedBy']['email']:$lastModified['AccountModifiedBy']['username']).'</a> at '.date_converter($lastModified['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?>');
			$('p#id-title-description').css('display','<?php echo (empty($totalList)?'none':'block'); ?>');
			
			// UPDATE TITLE HEADER !!
			$('div.title > h2').html('<?php echo strtoupper(empty($myEntry)?$myType['Type']['name']:$myEntry['Entry']['title'].' - '.$myChildType['Type']['name']); ?>');
			
		<?php else: ?>
			$('table#myTableList tbody tr').css('cursor' , 'pointer');
			$('input[type=checkbox]').css('cursor' , 'default');

			$('table#myTableList tbody tr').click(function(e){
				if(!$('input[type=checkbox]').is(e.target))
				{
					var targetID = "<?php echo (empty($myEntry)?$myType['Type']['slug']:$myChildType['Type']['slug']); ?>"+($('input#query-stream').length > 0?$('input#query-stream').val():'');
					if($(this).find("td.form-name").length > 0)
					{
					    $("input#"+targetID).val( $(this).find("td.form-name").text()+' ('+$(this).find("h5.title-code").text()+')');
					}
					else
					{
					    $("input#"+targetID).val( $(this).find("h5.title-code").text() );
					}
					
					$("input#"+targetID).nextAll("input[type=hidden]").val( $(this).find("input[type=hidden].slug-code").val() );
					$("input#"+targetID).change();

					// Update the subcategory dropdown value, if existed !!
					if($('select.subcategory').length > 0)
					{
						$('select.subcategory').html('');
						
						var catcheck = $(this).find("td.form-subcategory").html();
						
						if(catcheck != '-')
						{
							var subcat = catcheck.split('<br>');
						
							$.each(subcat , function(i,el){
								$('select.subcategory').append('<option value="'+el+'">'+el+'</option>');
							});
						}
						
					}

					$.colorbox.close();
				}
				else
				{
					$.fn.updateAttachButton();
				}
			});
		<?php endif; ?>
		// ---------------------------------------------------------------------- >>>
		// FOR AJAX REASON !!
		// ---------------------------------------------------------------------- >>>
		
		// UPDATE SEARCH LINK !!
		$('a.searchMeLink').attr('href',site+'admin/entries/<?php echo $myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']); ?>/index/1<?php echo (!empty($myEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:'').(empty($popup)?'':'?popup=ajax'); ?>');
		
		// UPDATE ADD NEW DATABASE LINK !!
		$('a.get-started').attr('href',site+'admin/entries/<?php echo $myType['Type']['slug'].'/'.(empty($myEntry)?'':$myEntry['Entry']['slug'].'/').'add'.(!empty($myEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:''); ?>');
        // Hide Add Button !!
        $('a.get-started').addClass('hide');
		
		// disable language selector ONLY IF one language available !!		
		var myLangSelector = ($('#colorbox').length > 0 && $('#colorbox').is(':visible')? $('#colorbox').find('div.lang-selector:first') : $('div.lang-selector')  );
		if(myLangSelector.find('ul.dropdown-menu li').length <= 1)	myLangSelector.hide();
	});
</script>
<?php if($totalList <= 0){ ?>
	<div class="empty-state item">
		<div class="wrapper-empty-state">
			<div class="pic"></div>
			<h2>No History Found!</h2>
		</div>
	</div>
<?php }else{ ?>
<table id="myTableList" class="list">
	<thead>
	<tr>
		<?php
            $sortASC = '&#9650;';
            $sortDESC = '&#9660;';
		?>
		<th class="date-field">
            <?php
                $entityTitle = "modified";
                echo $this->Form->Html->link('tanggal ('.$totalList.')'.($_SESSION['order_by'] == $entityTitle.' asc'?' <span class="sort-symbol">'.$sortASC.'</span>':($_SESSION['order_by'] == $entityTitle.' desc'?' <span class="sort-symbol">'.$sortDESC.'</span>':'')),array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage" , "escape" => false , "title" => "Click to Sort" , "alt"=>$entityTitle.($_SESSION['order_by'] == $entityTitle.' asc'?" desc":" asc") ));
            ?>
        </th>
		<th>NAMA BARANG</th>
		<th>JUMLAH</th>
		<th>ENTITY ASAL</th>
		<th>NAMA ASAL</th>
		<th class="keterangan">KETERANGAN</th>
		<?php
			if(empty($popup))
			{
				?>
		<th>
			<form id="global-action" style="margin: 0;" action="#" accept-charset="utf-8" method="post" enctype="multipart/form-data">
				<select REQUIRED name="data[action]" class="input-small">
					<option style="font-weight: bold;" value="">Action :</option>
					<option class="hide" value="active">Publish</option>
					<option class="hide" value="disable">Draft</option>
					<option value="delete">Delete</option>
				</select>
				<input type="hidden" name="data[record]" id="action-records" />
				<button type="submit" style="margin-top: -10px;" class="btn btn-success"><strong>GO!</strong></button>
			</form>
		</th>	
				<?php
			}
		?>
	</tr>
	</thead>
	
	<tbody>
	<?php		
		$orderlist = "";
		foreach ($myList as $value):
		$orderlist .= $value['Entry']['sort_order'].",";
	?>	
	<tr class="orderlist" alt="<?php echo $value['Entry']['id']; ?>">
	    <td><?php echo date_converter($value['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></td>
	    <?php
			$transfer = explode('_', $value['Entry']['title']);
			$detailBarang = $this->Get->meta_details($transfer[1] , "barang-dagang");
		?>		
		<td class="main-title">
		    <h5 style="margin: 0;" class="title-code">
		        <a href="<?php echo $imagePath.'admin/entries/'.$detailBarang['Entry']['entry_type'].'/edit/'.$detailBarang['Entry']['slug']; ?>">
		            <?php echo $detailBarang['Entry']['title']; ?>
		        </a>
		    </h5>
		</td>
		<td><?php echo $value['Entry']['main_image'].' '.$detailBarang['EntryMeta']['satuan']; ?></td>
		<?php
            $identifier = array();
            $entity = "";
            $identifierLink = "#";
            if($transfer[0] == 'automatic')
            {
                $identifier['Entry']['title'] = 'Administrator';
                $entity = "System";
            }
            else
            {
                $identifier = $this->Get->meta_details($transfer[0]);
                $entity = string_unslug($identifier['Entry']['entry_type']);                
                $identifierLink = $imagePath.'admin/entries/'.$identifier['Entry']['entry_type'].'/edit/'.$identifier['Entry']['slug'];
            }
        ?>
		<td><?php echo $entity; ?></td>
		<td>
            <h5 style="margin: 0;">
                <a href="<?php echo $identifierLink; ?>">                
                    <?php echo $identifier['Entry']['title']; ?>
                </a>
            </h5>		    
		</td>
		<td><?php echo str_replace(chr(10) , '<br/>' , $value['Entry']['description']); ?></td>		
		<?php
			if(empty($popup))
			{
				echo "<td>";
				if(!($myType['Type']['slug'] == 'pages' && $user['role_id'] >= 2))
				{
					?>
						<a href="javascript:void(0)" onclick="show_confirm('Are you sure want to delete this history ?','entries/delete/<?php echo $value['Entry']['id']; ?>')" class="btn btn-danger"><i class="icon-trash icon-white"></i></a>
					<?php
				}
				echo "</td>";
			}				
		?>
	</tr>
	
	<?php
		endforeach;
	?>
	</tbody>
</table>
<input type="hidden" id="determine" value="<?php echo $orderlist; ?>" />
<div class="clear"></div>
<input type="hidden" value="<?php echo $countPage; ?>" id="myCountPage"/>
<input type="hidden" value="<?php echo $left_limit; ?>" id="myLeftLimit"/>
<input type="hidden" value="<?php echo $right_limit; ?>" id="myRightLimit"/>
<?php
	if($isAjax == 0 || $isAjax == 1 && $search == "yes")
	{
		echo '</div>';
		echo $this->element('admin_footer');
		echo '<div class="clear"></div>';
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
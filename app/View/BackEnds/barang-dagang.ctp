<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);
    // initialize $extensionPaging for URL Query ...
    $extensionPaging = $this->request->query;
    unset($extensionPaging['lang']);
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
                    
                    // update satuan in stock field ...
                    if($('span.stock-satuan').length > 0)
                    {
                        $('span.stock-satuan').html($(this).find('td.form-satuan').html());
                    }
                    
                    // update jenis barang ...
                    if($('input#jenis-barang').length > 0)
                    {
                        $('input#jenis-barang').val($(this).find('td.form-jenis_barang h5').html());
                    }
                    
                    // ADD DEFAULT PRICE !!
                    if($('input[type=hidden]#myTypeSlug').val() == 'purchase-order')
                    {					
                        $("input[type=text].price").val( $(this).find("td.form-harga_beli input[type=hidden]").val() );
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
		$('a.searchMeLink').attr('href',site+'admin/entries/<?php echo $myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']); ?>/index/1<?php echo get_more_extension($extensionPaging); ?>');
		
		// UPDATE ADD NEW DATABASE LINK !!
		$('a.get-started').attr('href',site+'admin/entries/<?php echo $myType['Type']['slug'].'/'.(empty($myEntry)?'':$myEntry['Entry']['slug'].'/').'add'.(!empty($extensionPaging['type'])?'?type='.$extensionPaging['type']:''); ?>');
		
		// disable language selector ONLY IF one language available !!		
		var myLangSelector = ($('#colorbox').length > 0 && $('#colorbox').is(':visible')? $('#colorbox').find('div.lang-selector:first') : $('div.lang-selector')  );
		if(myLangSelector.find('ul.dropdown-menu li').length <= 1)	myLangSelector.hide();
	});
</script>
<?php if($totalList <= 0){ ?>
	<div class="empty-state item">
		<div class="wrapper-empty-state">
			<div class="pic"></div>
			<h2>No Items Found!</h2>
			<?php echo (!($myType['Type']['slug'] == 'pages' && $user['role_id'] >= 2 || !empty($popup))?$this->Form->Html->link('Get Started',array('action'=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'add','?'=> (!empty($myEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?array('type'=>$myChildType['Type']['slug']):'') ),array('class'=>'btn btn-primary')):''); ?>
		</div>
	</div>
<?php }else{ ?>
<table id="myTableList" class="list">
	<thead>
	<tr>
		<?php
            $sortASC = '&#9650;';
            $sortDESC = '&#9660;';
			$myAutomatic = (empty($myChildType)?$myType['TypeMeta']:$myChildType['TypeMeta']);
			$titlekey = "Title";
			foreach ($myAutomatic as $key => $value)
			{
				if($value['TypeMeta']['key'] == 'title_key')
				{
					$titlekey = $value['TypeMeta']['value'];
					break;
				}
			}
		?>
		<th>
		    <?php
                echo $this->Form->Html->link($titlekey.' ('.$totalList.')'.($_SESSION['order_by'] == 'title ASC'?' <span class="sort-symbol">'.$sortASC.'</span>':($_SESSION['order_by'] == 'title DESC'?' <span class="sort-symbol">'.$sortDESC.'</span>':'')),array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage" , "escape" => false , "title" => "Click to Sort" , "alt"=>$_SESSION['order_by'] == 'title ASC'?"z_to_a":"a_to_z"));
            ?>
		</th>
		
		<?php
			// if this is a parent Entry !!
			if(empty($myEntry) && empty($popup)) 
			{
				foreach ($myType['ChildType'] as $key10 => $value10)
				{
					echo '<th>'.$value10['name'].'</th>';
				}
			}
			
			// check for simple or complex table view !!
			if($mySetting['table_view'] == "complex")
			{
				$metaFields = (empty($myEntry)?$myType:$myChildType); 
				foreach ( $metaFields['TypeMeta'] as $key => $value) 
				{
					if(substr($value['TypeMeta']['key'], 0,5) == 'form-')
					{
						$entityTitle = $value['TypeMeta']['key'];
                        $hideKeyQuery = '';
                        if(!empty($popup) && $this->request->query['key'] == substr($entityTitle, 5))
                        {
                            $hideKeyQuery = 'hide';
                        }
                        echo "<th ".($value['TypeMeta']['input_type'] == 'textarea' || $value['TypeMeta']['input_type'] == 'ckeditor'?"style='min-width:200px;'":"")." class='".$hideKeyQuery."'>";
                        echo $this->Form->Html->link(string_unslug(substr($entityTitle, 5)).($_SESSION['order_by'] == $entityTitle.' asc'?' <span class="sort-symbol">'.$sortASC.'</span>':($_SESSION['order_by'] == $entityTitle.' desc'?' <span class="sort-symbol">'.$sortDESC.'</span>':'')),array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage" , "escape" => false , "title" => "Click to Sort" , "alt"=>$entityTitle.($_SESSION['order_by'] == $entityTitle.' asc'?" desc":" asc") ));
						echo "</th>";
					}
				}
			}	
		?>
		<th>LABA KOTOR</th>
		<th class="date-field">
            <?php
                $entityTitle = "modified";
                echo $this->Form->Html->link('last '.string_unslug($entityTitle).($_SESSION['order_by'] == $entityTitle.' asc'?' <span class="sort-symbol">'.$sortASC.'</span>':($_SESSION['order_by'] == $entityTitle.' desc'?' <span class="sort-symbol">'.$sortDESC.'</span>':'')),array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage" , "escape" => false , "title" => "Click to Sort" , "alt"=>$entityTitle.($_SESSION['order_by'] == $entityTitle.' asc'?" desc":" asc") ));
            ?>
        </th>
		<th class="hide">
		    <?php
                $entityTitle = "status";
                echo $this->Form->Html->link(string_unslug($entityTitle).($_SESSION['order_by'] == $entityTitle.' asc'?' <span class="sort-symbol">'.$sortASC.'</span>':($_SESSION['order_by'] == $entityTitle.' desc'?' <span class="sort-symbol">'.$sortDESC.'</span>':'')),array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage" , "escape" => false , "title" => "Click to Sort" , "alt"=>$entityTitle.($_SESSION['order_by'] == $entityTitle.' asc'?" desc":" asc") ));
            ?>
		</th>
		<?php
			if(empty($popup))
			{
				?>
		<th class="action">
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
		<td class="main-title">
			<?php
				if($imageUsed == 1)
				{
					echo '<div class="thumbs">';
					echo (empty($popup)?$this->Html->link($this->Html->image('upload/thumb/'.$value['Entry']['main_image'].'.'.$myImageTypeList[$value['Entry']['main_image']], array('alt'=>$value['ParentImageEntry']['title'],'title' => $value['ParentImageEntry']['title'])),array('action'=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']).'/edit/'.$value['Entry']['slug'].(!empty($myEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:'')),array("escape"=>false)):$this->Html->image('upload/thumb/'.$value['Entry']['main_image'].'.'.$myImageTypeList[$value['Entry']['main_image']], array('alt'=>$value['ParentImageEntry']['title'],'title' => $value['ParentImageEntry']['title'])));
					echo '</div>';
				}
			?>
			<input class="slug-code" type="hidden" value="<?php echo $value['Entry']['slug']; ?>" />
			<h5 style="margin: 0;" class="title-code"><?php echo (empty($popup)?$this->Form->Html->link($value['Entry']['title'],array('action'=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'edit',$value['Entry']['slug'] ,'?'=> (!empty($myEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?array('type'=>$myChildType['Type']['slug']):'')   )  ):$value['Entry']['title']); ?></h5>
			<p>
				<?php
					if($descriptionUsed == 1 && !empty($value['Entry']['description']))
					{
						$description = strip_tags($value['Entry']['description']);
						echo (strlen($description) > 30? substr($description,0,30)."..." : $description);
					}
				?>
			</p>
		</td>
		<?php
			if(empty($myEntry) && empty($popup)) // if this is a parent Entry !!
			{
				foreach ($myType['ChildType'] as $key10 => $value10)
				{
					$childCount = 0;
					foreach ($value['EntryMeta'] as $key20 => $value20) 
					{
						if($value20['key'] == 'count-'.$value10['slug'])
						{
							$childCount = $value20['value'];
							break;
						}
					}
					echo '<td><span class="badge badge-info">'.$this->Form->Html->link($childCount,array('action'=>$myType['Type']['slug'],$value['Entry']['slug'],'?'=>array('type'=>$value10['slug'], 'lang'=>$_SESSION['lang']))).'</span></td>';
				}
			}

			// check for simple or complex table view !!
			if($mySetting['table_view'] == "complex")
			{				 
				foreach ( $metaFields['TypeMeta'] as $key10 => $value10) 
				{
					if(substr($value10['TypeMeta']['key'], 0,5) == 'form-')
					{
						$shortkey = substr($value10['TypeMeta']['key'], 5);
                        $displayValue = $value['EntryMeta'][$shortkey];
                        $hideKeyQuery = '';
                        if(!empty($popup) && $this->request->query['key'] == $shortkey)
                        {
                            $hideKeyQuery = 'hide';
                        }
                        
                        echo "<td class='".$value10['TypeMeta']['key']." ".$hideKeyQuery."'>";
                        if(empty($displayValue))
                        {
                        	if($value10['TypeMeta']['input_type'] == 'gallery' && !empty($value['EntryMeta']['count-'.$value10['TypeMeta']['key']]))
                        	{
                        		$queryURL = array('anchor' => $shortkey );
                        		if( !empty($myEntry) && $myType['Type']['slug']!=$myChildType['Type']['slug'] )
                        		{
                        			$queryURL['type'] = $myChildType['Type']['slug'];
                        		}
                        		echo '<span class="badge badge-info">'.(empty($popup)?$this->Form->Html->link($value['EntryMeta']['count-'.$value10['TypeMeta']['key']].' <i class="icon-picture icon-white"></i>',array('action'=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']) , 'edit' , $value['Entry']['slug'] , '?' => $queryURL ), array('escape'=>false,'title' => 'Click to see all images.')):$value['EntryMeta']['count-'.$value10['TypeMeta']['key']].' <i class="icon-picture icon-white"></i>').'</span>';
                        	}
                        	else
                        	{
                        		echo '-';	
                        	}
                        }
                        else if($value10['TypeMeta']['input_type'] == 'multibrowse')
						{
							$browse_slug = get_slug($shortkey);
							$displayValue = explode('|', $displayValue);
							
							$emptybrowse = 0;
							foreach ($displayValue as $brokekey => $brokevalue) 
							{
								$mydetails = $this->Get->meta_details($brokevalue , $browse_slug );
								if(!empty($mydetails))
								{
									$emptybrowse = 1;
									$outputResult = (empty($mydetails['EntryMeta']['name'])?$mydetails['Entry']['title']:$mydetails['EntryMeta']['name']);
									echo '<p>'.(empty($popup)?$this->Form->Html->link($outputResult,array('controller'=>'entries','action'=>$mydetails['Entry']['entry_type'],'edit',$mydetails['Entry']['slug']),array('target'=>'_blank')):$outputResult).'</p>';
								}
							}
							
							if($emptybrowse == 0)
							{
								echo '-';
							}
						}
                        else if($value10['TypeMeta']['input_type'] == 'browse')
                        {
                        	$entrydetail = $this->Get->meta_details($displayValue , get_slug($shortkey));
							if(empty($entrydetail))
							{
								echo '-';
							}
							else
							{
								$outputResult = (empty($entrydetail['EntryMeta']['name'])?$entrydetail['Entry']['title']:$entrydetail['EntryMeta']['name']);
								echo '<h5 style="margin: 0;">'.(empty($popup)?$this->Form->Html->link($outputResult,array("controller"=>"entries","action"=>$entrydetail['Entry']['entry_type']."/edit/".$entrydetail['Entry']['slug']),array('target'=>'_blank')):$outputResult).'</h5>';							
                            	echo '<p>';
                                // Try to use Primary EntryMeta first !!
                                if(!empty($entrydetail['EntryMeta'][0]['value']))
                                {
                                    echo $entrydetail['EntryMeta'][0]['value'];
                                }
                                else
                                {
                                    $description = strip_tags($entrydetail['Entry']['description']);
                            	    echo (strlen($description) > 30 ? substr($description,0,30)."..." : $description);
                                }                                
                                echo '</p>';
							}
                        }
                        else
                        {
                        	echo $this->Get->outputConverter($value10['TypeMeta']['input_type'] , $displayValue , $myImageTypeList , $shortkey);
                        }
                        echo "</td>";
					}
				}
			}	
		?>
		<td>
			<?php echo (empty($value['EntryMeta']['harga_jual'])||empty($value['EntryMeta']['harga_beli'])?'-':'Rp '.str_replace(',', '.', toMoney( $value['EntryMeta']['harga_jual'] - $value['EntryMeta']['harga_beli'] , true , true) ).',-'); ?>
			<input type="hidden" value="<?php echo (empty($value['EntryMeta']['harga_jual'])||empty($value['EntryMeta']['harga_beli'])?'0':$value['EntryMeta']['harga_jual'] - $value['EntryMeta']['harga_beli']); ?>">
		</td>
		<td><?php echo date_converter($value['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></td>
		<td class="hide" style='min-width: 0px;' <?php echo (empty($popup)?'':'class="offbutt"'); ?>>
			<span class="label <?php echo $value['Entry']['status']==0?'label-important':'label-success'; ?>">
				<?php
					if($value['Entry']['status'] == 0)
						echo "Draft";
					else
						echo "Published";
				?>
			</span>
		</td>
		<?php
			if(empty($popup))
			{
				echo "<td>";
                ?>
                <a class="btn btn-info" title="Lihat Penempatan Gudang!" href="<?php echo $imagePath.'admin/entries/'.$myType['Type']['slug'].'/view/'.$value['Entry']['slug']; ?>"><i class="icon-search icon-white"></i></a>&nbsp;
                <?php
				if(!($myType['Type']['slug'] == 'pages' && $user['role_id'] >= 2))
				{
					?>
						<a href="javascript:void(0)" onclick="show_confirm('Are you sure want to delete <?php echo strtoupper($value['Entry']['title']); ?> ?','entries/delete/<?php echo $value['Entry']['id']; ?>')" class="btn btn-danger"><i class="icon-trash icon-white"></i></a>
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
		echo $this->element('admin_footer', array('extensionPaging' => $extensionPaging));
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
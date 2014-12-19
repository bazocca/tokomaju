<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);

    $actionType = $this->Get->getType($this->request->query['action']);

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
	$(document).ready(function(){
        $('table#myTableList tbody tr').css('cursor' , 'pointer');
		<?php if(empty($popup)): ?>
            $('table#myTableList tbody tr').click(function(e){
				window.location = site+"admin/entries/<?php echo $myType['Type']['slug']; ?>/"+$(this).find("input[type=hidden].slug-code").val()+"?type=<?php echo $actionType['Type']['slug']; ?>";
			});
			// ---------------------------------------------------------------------- >>>
			// FOR AJAX REASON !!
			// ---------------------------------------------------------------------- >>>
            $('div.breadcrumbs p a:last').text('<?php echo $actionType['Type']['name']; ?>');
    
            $("a#<?php echo $myType['Type']['slug']; ?>").removeClass("active");
            $("a#<?php echo $actionType['Type']['slug']; ?>").addClass("active");
    
            $('p#module-description').html('<?php echo $actionType['Type']['description']; ?>');
			$('p#id-title-description').html('<span style="color:red;">Pilih salah satu PO untuk melihat detail berikutnya.</span>');
			$('p#id-title-description').css('display','<?php echo (empty($totalList)?'none':'block'); ?>');
			
			// UPDATE TITLE HEADER !!
			$('div.title > h2').html('<?php echo string_unslug($this->request->query['action']); ?>');
        
		<?php else: ?>			
			$('table#myTableList tbody tr').click(function(e){
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
			});
		<?php endif; ?>		
        // ---------------------------------------------------------------------- >>>
		// FOR AJAX REASON !!
		// ---------------------------------------------------------------------- >>>
        $('div.inner-header > div:first').removeClass('span5').addClass('span7');
        $('div.inner-header > div:last').removeClass('span7').addClass('span5');
    
		// UPDATE SEARCH LINK !!
		$('a.searchMeLink').attr('href',site+'admin/entries/<?php echo $myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']); ?>/index/1<?php echo get_more_extension($extensionPaging); ?>');
		
		// HIDE ADD NEW DATABASE LINK !!
		$('a.get-started').hide();
		
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
                        $shortkey = substr($entityTitle, 5);
                        if(!empty($popup) && $this->request->query['key'] == $shortkey || $shortkey == 'status_kirim')
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
		<th class="hide">
		    <?php
                $entityTitle = "status";
                echo $this->Form->Html->link(string_unslug($entityTitle).($_SESSION['order_by'] == $entityTitle.' asc'?' <span class="sort-symbol">'.$sortASC.'</span>':($_SESSION['order_by'] == $entityTitle.' desc'?' <span class="sort-symbol">'.$sortDESC.'</span>':'')),array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage" , "escape" => false , "title" => "Click to Sort" , "alt"=>$entityTitle.($_SESSION['order_by'] == $entityTitle.' asc'?" desc":" asc") ));
            ?>
		</th>		
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
			<input class="slug-code" type="hidden" value="<?php echo $value['Entry']['slug']; ?>" />
			<h5 class="title-code"><?php echo $value['Entry']['title']; ?></h5>
			<p>
				<?php
					if($descriptionUsed == 1 && !empty($value['Entry']['description']))
					{
						$description = strip_tags($value['Entry']['description']);
						echo nl2br($description);
					}
				?>
			</p>
		</td>
		<?php
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
                        if(!empty($popup) && $this->request->query['key'] == $shortkey || $shortkey == 'status_kirim')
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
                        		echo '<span class="badge badge-info">'.$value['EntryMeta']['count-'.$value10['TypeMeta']['key']].' <i class="icon-picture icon-white"></i>'.'</span>';
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
									echo '<p>'.$outputResult.'</p>';
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
								echo '<h5>'.$outputResult.'</h5>';
                                
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
<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);

    $salHeader = $this->Get->meta_details($myEntry['Entry']['slug'] , "sales-order");

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
        if($salHeader['EntryMeta']['status_bayar'] == "Lunas")
		{
			?>
			<div class="alert alert-info full fl">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				Transaksi pembayaran invoice <?php echo $myEntry['Entry']['title']; ?> sudah lunas.
			</div>
			<?php
		}
        else
        {
            ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // Add INSTANT PAID BUTTON !! (lunas)
                $('a.get-started').after('<a title="Tekan untuk pelunasan invoice secara langsung." href="'+site+'entries/pelunasan_tagihan/<?php echo $myEntry['Entry']['slug']; ?>" class="btn btn-success fr right-btn">Instant Paid Off</a>');
            });
        </script>    
            <?php
        }
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
		<?php if(empty($popup)): ?>
			$('table#myTableList tr').css('cursor' , 'default');
			// ---------------------------------------------------------------------- >>>
			// FOR AJAX REASON !!
			// ---------------------------------------------------------------------- >>>
            $("a#<?php echo $myType['Type']['slug']; ?>").removeClass("active");
            $("a#<?php echo $myChildType['Type']['slug']; ?>").addClass("active");
        
            $('a.get-started').text('Add Transaction');
        
			$('p#id-title-description').html('Last updated by <a href="#"><?php echo (empty($lastModified['AccountModifiedBy']['username'])?$lastModified['AccountModifiedBy']['email']:$lastModified['AccountModifiedBy']['username']).'</a> at '.date_converter($lastModified['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?>');
			$('p#id-title-description').css('display','<?php echo (empty($totalList)?'none':'block'); ?>');
			
			// UPDATE TITLE HEADER !!
			$('div.title > h2').html('<?php echo strtoupper(empty($myEntry)?$myType['Type']['name']:$myEntry['Entry']['title'].' - REKENING KORAN'); ?>');
			
		<?php else: ?>
			$('table#myTableList tbody tr').css('cursor' , 'pointer');
			$('input[type=checkbox]').css('cursor' , 'default');

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
        $('div.inner-header > div:first').removeClass('span5').addClass('span9');
        $('div.inner-header > div:last').removeClass('span7').addClass('span3');
    
		// DELETE SEARCH LINK !!
		$('a.searchMeLink').closest('div.input-prepend').hide();
        
        // HIDE SORT UTILITY !!
        $('a.order_by:first').closest('div.btn-group').hide();
		
		// UPDATE ADD NEW DATABASE LINK !!
		$('a.get-started').attr('href',site+'admin/entries/<?php echo $myType['Type']['slug'].'/'.(empty($myEntry)?'':$myEntry['Entry']['slug'].'/').'add'.(!empty($extensionPaging['type'])?'?type='.$extensionPaging['type']:''); ?>');
		
		// disable language selector ONLY IF one language available !!		
		var myLangSelector = ($('#colorbox').length > 0 && $('#colorbox').is(':visible')? $('#colorbox').find('div.lang-selector:first') : $('div.lang-selector')  );
		if(myLangSelector.find('ul.dropdown-menu li').length <= 1)	myLangSelector.hide();
        
        // switch column position !!
        $('table#myTableList thead tr').prepend( $('table#myTableList thead tr th:eq(2)') );
        $('table#myTableList tbody tr').each(function(i,el){
            $(el).prepend( $(el).find('td:eq(2)') );
        });
        
        // give bold effect to last balance !!
        $('table#myTableList tbody tr:last td.walking-balance').css('font-weight' , 'bold');
        $('table#myTableList tbody tr:last td.walking-balance').css('color' , 'red');
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
		    <?php echo $titlekey.' ('.$totalList.')'; ?>
		</th>
		<th class="keterangan">KETERANGAN</th>
		
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
                        if(!empty($popup) && $this->request->query['key'] == substr($entityTitle, 5))
                        {
                            $hideKeyQuery = 'hide';
                        }
                        echo "<th ".($value['TypeMeta']['input_type'] == 'textarea' || $value['TypeMeta']['input_type'] == 'ckeditor'?"style='min-width:200px;'":"")." class='".$hideKeyQuery."'>";
                        echo string_unslug(substr($entityTitle, 5));                        
						echo "</th>";
					}
				}
			}	
		?>
		<th>BALANCE</th>
	</tr>
	</thead>
	
	<tbody>
	<?php
        $walking_balance = 0;
		$orderlist = "";
		foreach ($myList as $value):
		$orderlist .= $value['Entry']['sort_order'].",";
	?>	
	<tr class="orderlist" alt="<?php echo $value['Entry']['id']; ?>">
		<td>
			<input class="slug-code" type="hidden" value="<?php echo $value['Entry']['slug']; ?>" />
			<h5 class="title-code"><?php echo $value['Entry']['title']; ?></h5>
		</td>
		<td>
		    <?php
                echo (empty($value['Entry']['description'])?'-':nl2br($value['Entry']['description']));
            ?>
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
								echo '<h5>'.(empty($popup)?$this->Form->Html->link($outputResult,array("controller"=>"entries","action"=>$entrydetail['Entry']['entry_type']."/edit/".$entrydetail['Entry']['slug']),array('target'=>'_blank')):$outputResult).'</h5>';
                                
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
		<td class="walking-balance">
		    <?php
				$walking_balance = $walking_balance + (empty($value['EntryMeta']['mutasi_debet'])?0:$value['EntryMeta']['mutasi_debet']) - (empty($value['EntryMeta']['mutasi_kredit'])?0:$value['EntryMeta']['mutasi_kredit']);
				echo 'Rp.'.str_replace(',', '.', toMoney( $walking_balance , true , true) ).',-';
			?>
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
        // echo $this->element('admin_footer', array('extensionPaging' => $extensionPaging));
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
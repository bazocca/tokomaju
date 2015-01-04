<?php
    // CUMAN BISA ADD, TIDAK BISA EDIT OR DELETE !!!!
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);
	if($isAjax == 0)
	{
		echo $this->element('admin_header_add');
		?>
		<script>
			$(document).ready(function(){
                $('div.title > h2').html('FORM RETUR PENJUALAN <?php echo $myParentEntry['Entry']['title']; ?>');
                
                $("a#<?php echo $myType['Type']['slug']; ?>").removeClass("active");
                $("a#<?php echo $myChildType['Type']['slug']; ?>").addClass("active");
                
				// disable language selector ONLY IF one language available !!
				if($('div.lang-selector ul.dropdown-menu li').length <= 1)
				{
					$('div.lang-selector').hide();
				}

				// focus on anchor query url IF ANY ...
				<?php if(!empty($this->request->query['anchor'])): ?>
					$('div#form-<?php echo $this->request->query['anchor']; ?>').prevAll('a.get-from-library:first').focus();
				<?php endif; ?>
			});
		</script>
		<?php
		echo '<div id="ajaxed" class="inner-content">';
	}
	else 
	{
		?>
		<script>
			$(document).ready(function(){
				$('#cmsAlert').css('display' , 'none');
				$(".get-from-table").colorbox({ // REFRESH - POPUP ADMIN_DEFAULT.CTP
					reposition: false,
					onLoad: function() {
						$('#cboxClose').show();
					}
				});
			});
		</script>
		<?php
	}
	$myChildTypeLink = (!empty($myParentEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:'');
	$myTranslation = ( empty($lang)||empty($myEntry) ? '' : (empty($myChildTypeLink)?'?':'&').'lang='.$lang);
	$targetSubmit = (empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).(empty($myEntry)?'/add':'/edit/'.$myEntry['Entry']['slug']).$myChildTypeLink.$myTranslation;
	$saveButton = (empty($myEntry)?'Submit Form':(empty($lang)?'Save Changes':'Add Translation'));
	echo $this->Form->create('Entry', array('action'=>$targetSubmit,'type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));	
?>
	<fieldset>
		<script>
			$(document).ready(function(){
				if($('p#id-title-description').length > 0)
				{
					$('p#id-title-description').html('Last updated by <a href="#"><?php echo (empty($myEntry['AccountModifiedBy']['username'])?$myEntry['AccountModifiedBy']['email']:$myEntry['AccountModifiedBy']['username']).'</a> at '.date_converter($myEntry['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?>');
					$('p#id-title-description').css('display','<?php echo (!empty($lang)?'none':'block'); ?>');
				}
				
				// save as published button !!
				$('button#save-button').click(function(){
					$(this).closest('form').find('button[type=submit]:first').click();
				});
                
                // Form interaction !!
                $('#form-retur-jual tr td input.datang').change(function(){
                    var total = parseInt($(this).next("input[type=hidden].total").val());
                    var datang = parseInt($(this).val());
                    $(this).parents("tr").find("td.sisa h5").html(total - datang);
                });
                
                $('form').submit(function()
                {   
                    var gudang = $("input[type=text]#gudang").val();
                    var message = "";
                    if(gudang.length == 0)
                    {
                        message = "Invalid input! Please fill the required fields.";
                        alert(message);
                        return false;
                    }
                    
                    // second check !!
                    var validku = false;
                    $('#form-retur-jual tr td input.datang').each(function(){                        
                        if( parseInt($(this).val()) > 0)
                        {
                            validku = true;
                            return false;
                        }
                    });
                    
                    if(!validku)
                    {
                        message = "Select at least one item to import!";
                        alert(message);                        
                        $('#form-retur-jual tr td input.datang:first').focus();                        
                        return false;
                    }
                    else
                    {
                        message = "Are you sure to submit this? You can't undo this process!";
                        return confirm(message);    
                    }
                });
			});
		</script>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		<input type="hidden" value="<?php echo (isset($_POST['data']['language'])?$_POST['data']['language']:(empty($lang)?substr($myEntry['Entry']['lang_code'], 0,2):$lang)); ?>" name="data[language]" id="myLanguage"/>
		<input type="hidden" value="<?php echo (isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value']:(empty($myEntry)?'0':$myEntry['Entry']['main_image'])); ?>" name="data[Entry][2][value]" id="mySelectCoverId"/>
		<input type='hidden' id="entry_image_type" value="<?php echo $myImageTypeList[isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value']:(empty($myEntry)?'0':$myEntry['Entry']['main_image'])]; ?>" />
		<?php
			$myAutomatic = (empty($myChildType)?$myType['TypeMeta']:$myChildType['TypeMeta']);
		?>
		<!-- BEGIN TO LIST META ATTRIBUTES -->
		<?php
			$counter = 3;
			foreach ($myAutomatic as $key => $value)
			{
				$value = $value['TypeMeta']; // SPECIAL CASE, COZ IT'S BEEN MODIFIED IN CONTROLLER !!
				if(substr($value['key'], 0 , 5) == 'form-' && ($value['key']=='form-tanggal' || $value['key']=='form-gudang') )
				{
					$value['optionlist'] = $value['value'];
					unset($value['value']);

					// now get value from EntryMeta if existed !!
					foreach ($myEntry['EntryMeta'] as $key10 => $value10) 
					{						
						if($value['key'] == $value10['key'])
						{
							$value['value'] = $value10['value'];
							break;
						}
					}
					$value['model'] = 'EntryMeta';
					$value['counter'] = $counter++;
					$value['p'] = $value['instruction'];
					switch ($value['input_type']) 
					{
						case 'checkbox':
						case 'radio':
						case 'dropdown':
							$temp = explode(chr(13).chr(10), $value['optionlist']);
							foreach ($temp as $key50 => $value50) 
							{
								$value['list'][$key50]['id'] = $value50;
								$value['list'][$key50]['name'] = string_unslug($value50);
							}
							break;
						default:
							break;
					}
					echo $this->element('input_'.$value['input_type'] , $value);
				}
			}
		?>		
		<!-- END OF META ATTRIBUTES -->
		<table class="list" id="form-retur-jual">
			<thead>
			<tr>
                <th>JUMLAH<br/>TERKIRIM</th>
				<th>JENIS / NAMA BARANG</th>				
				<th>JUMLAH<br/>RETUR</th>
				<th>SISA</th>
				<th>KETERANGAN</th>
			</tr>
			</thead>
			
			<tbody>
			<?php
				foreach ($pesanan['myList'] as $value):
				$sisa = $value['EntryMeta']['terkirim'] - $value['EntryMeta']['retur'];
				if(empty($sisa))
				{
					continue;
				}

                $detailbarang = $this->Get->meta_details($value['Entry']['title'] , "barang-dagang");
                $jenisbarang = $this->Get->meta_details($detailbarang['EntryMeta']['jenis_barang'] , "jenis-barang");
			?>	
			<tr>
                <td class="jumlah-terkirim">
                    <h5><?php echo $value['EntryMeta']['terkirim'].' '.$detailbarang['EntryMeta']['satuan']; ?></h5>
                </td>
                <td class="nama">
                    <?php echo $jenisbarang['Entry']['title']." / ".$detailbarang['Entry']['title']; ?>
                </td>
				<td class="jumlah-datang">
                    <input min="0" max="<?php echo $sisa; ?>" type="number" class="datang input-mini" name="data[barang][<?php echo $value['Entry']['title']; ?>][jumlah]" value="0" />
					<input type="hidden" class="total" value="<?php echo $sisa; ?>" />
				</td>
				<td class="sisa">
				    <h5><?php echo $sisa; ?></h5>
				</td>
				<td class="keterangan">
                    <textarea rows="1" name="data[barang][<?php echo $value['Entry']['title']; ?>][keterangan]" placeholder="Put your comment here..."></textarea>
				</td>				
			</tr>
			
			<?php
				endforeach;
			?>
			</tbody>
		</table>
		
		<!-- myTypeSlug is for media upload settings purpose !! -->
		<input type="hidden" value="<?php echo (empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']); ?>" id="myTypeSlug"/>
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<!-- always use submit button to submit form -->
			<button class="hide" type="submit"></button>

			<button id="save-button" type="button" class="btn btn-primary"><?php echo $saveButton; ?></button>
			<?php
				if(empty($myEntry))
				{
					echo '<button id="save-as-draft" type="button" class="btn btn-inverse hide">Save as Draft</button>';
				}
			?>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/entries/<?php echo (empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).$myChildTypeLink.(empty($myEntry)?'':(empty($myChildTypeLink)?'?':'&').'lang='.(empty($lang)?substr($myEntry['Entry']['lang_code'], 0,2):$lang)); ?>'">Cancel</button>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>
	<div class="clear"></div>
<?php
	if($isAjax == 0)
	{
		echo '</div>';
	}
?>
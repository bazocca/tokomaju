<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);
	if($isAjax == 0)
	{
		echo $this->element('admin_header_add');
		?>
		<script>
			$(document).ready(function(){
				// disable language selector ONLY IF one language available !!
				if($('div.lang-selector ul.dropdown-menu li').length <= 1)
				{
					$('div.lang-selector').hide();
				}

				// focus on anchor query url IF ANY ...
				<?php if(!empty($this->request->query['anchor'])): ?>
					$('div#form-<?php echo $this->request->query['anchor']; ?>').prevAll('a.get-from-library:first').focus();
				<?php endif; ?>
                
                // Tabel Pesanan Barang !!
                $("input[type=text]#barang-dagang").change(function(){
                    if($(this).val() == "")
                    {	
                        $("div#optgoods").slideUp('fast',function(){
                            $("input[type=text]#jumlah").val("0");
                        });
                    }
                    else
                    {				
                        $("div#optgoods").slideDown('fast');
                    }
                });

                $("button[type=button]#addToCart").click(function(){
                    var id = $("input[type=text]#barang-dagang").val();
                    var jumlah = parseInt($("input[type=text]#jumlah").val());
                    var harga = parseInt($("input[type=text]#harga").val());
                    var subtotal = jumlah * harga;

                    if(isNaN(jumlah) || isNaN(harga) || jumlah <= 0 || harga <= 0)
                    {
                        alert("Invalid input. Please try again.");
                        return;
                    }

                    var check = true;                    
                    var namabarang = "";
                    $('tbody#myInputWrapper tr').each(function(i,el){                        
                        namabarang = $(el).find('td.nama').text().split('/');                        
                        namabarang = $.trim(namabarang[1]);
                        if(namabarang == id)
                        {
                            check = false;                            
                            return;
                        }
                    });			
                    if(check == false)
                    {
                        alert("Barang sudah terdaftar. Silahkan tambahkan barang yang lainnya.");
                        return;
                    }
                    
                    var content = '<tr>';
                    content += '<td class="jumlah"><h5>'+jumlah+' '+$('span.stock-satuan').text()+'</h5></td>';
                    content += '<td class="nama">'+$("input#jenis-barang").val()+' / '+$('input#barang-dagang').val()+'</td>';                    
                    content += '<td class="harga">Rp '+number_format (harga, 0, ',', '.')+',-</td>';
                    content += '<td class="subtotal">Rp '+number_format (subtotal, 0, ',', '.')+',-<input type="hidden" value="'+subtotal+'"></td>';
                    content += '<td class="action">';
                    content += '<a href="javascript:void(0)" class="btn btn-danger del-barang" style="display: inline;"><i class="icon-trash icon-white"></i></a>';
                    content += '<input type="hidden" name="data[barang][id][]" value="'+id+'"/>';
                    content += '<input type="hidden" name="data[barang][jumlah][]" value="'+jumlah+'"/>';
                    content += '<input type="hidden" name="data[barang][harga][]" value="'+harga+'"/>';
                    content += '</td>';

                    content += '</tr>';
                    $('tbody#myInputWrapper').append(content).each(function(){
                        $("input[type=text]#barang-dagang").val("");                        
                        $("input[type=text]#barang-dagang").change();
                        $('input#supplier').nextAll('a.cboxElement').addClass('disabled');

                        var grandtotal = parseInt($("#grandtotal > input[type=hidden]").val());
                        grandtotal += subtotal;                        
                        $("#grandtotal > input[type=hidden]").val(grandtotal);                        
                        $("#grandtotal > strong").html('Rp '+number_format(grandtotal, 0, ',', '.')+',-');
                    });
                });

                $(document).on("click","a.del-barang",function(){
                    $(this).parents('tr').animate({opacity : 0},1000,function(){
                        var grandtotal = parseInt($("#grandtotal > input[type=hidden]").val());
                        var minus = parseInt($(this).find('td.subtotal input[type=hidden]').val());
                        grandtotal -= minus;
                        $("#grandtotal > input[type=hidden]").val(grandtotal);                        
                        $("#grandtotal > strong").html('Rp '+number_format(grandtotal, 0, ',', '.')+',-');
                        $(this).detach();
                        
                        // re-enable browse supplier !!
                        if($('tbody#myInputWrapper tr').length == 0)
                        {
                            $('input#supplier').nextAll('a.cboxElement').removeClass('disabled');
                        }
                    });
                });
                
                $('a#add-invoice-barang').click(function(e){
                    e.preventDefault();
                    $.colorbox({
                        href: $(this).attr('href')+"&key=supplier&value="+$(this).attr('data-supplier'),
                        reposition: false,
                        onLoad: function() {
                            $('#cboxClose').show();
                        }
                    });
                });
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
				$('.get-from-table').colorbox({reposition: false});
			});
		</script>
		<?php
	}
	$myChildTypeLink = (!empty($myParentEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:'');
	$myTranslation = ( empty($lang)||empty($myEntry) ? '' : (empty($myChildTypeLink)?'?':'&').'lang='.$lang);
	$targetSubmit = (empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).(empty($myEntry)?'/add':'/edit/'.$myEntry['Entry']['slug']).$myChildTypeLink.$myTranslation;
	$saveButton = (empty($myEntry)?'Add New':(empty($lang)?'Save Changes':'Add Translation'));
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
				
				// media sortable
				if($("div#myPictureWrapper").length > 0)
				{
					$("div#myPictureWrapper").sortable({ opacity: 0.6, cursor: 'move'});
					// print total pictures...
					$('div#myPictureWrapper').prevAll('.galleryCount:first').find('span').html( $('div#myPictureWrapper').find('div.photo').length );
				}
				
				// save as draft button !!
				$('button#save-as-draft').click(function(){
					// set last status button as draft !!
					$('select.status:last').val('0');
					$(this).closest('form').find('button[type=submit]:first').click();
				});
				
				// save as published button !!
				$('button#save-button').click(function(){
					<?php if(empty($myEntry)): ?>
					// set last status button as published !!
					$('select.status:last').val('1');
					<?php endif; ?>
					$(this).closest('form').find('button[type=submit]:first').click();
				});
			});
		</script>
		<p class="notes important <?php echo ($view_mode?'hide':''); ?>" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		<input type="hidden" value="<?php echo (isset($_POST['data']['language'])?$_POST['data']['language']:(empty($lang)?substr($myEntry['Entry']['lang_code'], 0,2):$lang)); ?>" name="data[language]" id="myLanguage"/>
		<input type="hidden" value="<?php echo (isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value']:(empty($myEntry)?'0':$myEntry['Entry']['main_image'])); ?>" name="data[Entry][2][value]" id="mySelectCoverId"/>
		<input type='hidden' id="entry_image_type" value="<?php echo $myImageTypeList[isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value']:(empty($myEntry)?'0':$myEntry['Entry']['main_image'])]; ?>" />
        <?php
			$myAutomatic = (empty($myChildType)?$myType['TypeMeta']:$myChildType['TypeMeta']);
			$titlekey = "title";
			foreach ($myAutomatic as $key => $value)
			{
				if($value['TypeMeta']['key'] == 'title_key')
				{
					$titlekey = $value['TypeMeta']['value'];
					break;
				}
			}
			
			$value = array();
			$value['key'] = 'form-'.Inflector::slug($titlekey);
			$value['validation'] = 'not_empty';
			$value['model'] = 'Entry';
			$value['counter'] = 0;
			$value['input_type'] = 'text';
            $value['p'] = 'Auto generated Code';
            $value['readonly'] = 'readonly';
            $value['id'] = 'entry_id';
            $value['inputsize'] = 'input-medium';
            if(!empty($myEntry))
            {
                $value['display'] = 'none';
            }
			$value['value'] = (isset($_POST['data'][$value['model']][$value['counter']]['value'])?$_POST['data'][$value['model']][$value['counter']]['value']:$myEntry[$value['model']]['title']);
			echo $this->element('input_'.$value['input_type'] , $value);
		?>
		<!-- BEGIN TO LIST META ATTRIBUTES -->
		<?php
			$counter = 3;
			foreach ($myAutomatic as $key => $value)
			{
				$value = $value['TypeMeta']; // SPECIAL CASE, COZ IT'S BEEN MODIFIED IN CONTROLLER !!
				if(substr($value['key'], 0 , 5) == 'form-')
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
                    
                    // custom function !!
                    if($value['key'] == 'form-status_kirim' && empty($myEntry) || $value['key'] == 'form-total_harga')
                    {
                        $value['display'] = 'none';
                    }
                    else if($value['key'] == 'form-nama_pegawai' && empty($myEntry))
                    {
                        $value['value'] = $user['User']['firstname'].' '.$user['User']['lastname'];
                    }                    
					echo $this->element('input_'.$value['input_type'] , $value);
				}
			}
			// HIDE THE BROKEN INPUT TYPE !!!!!!!!!!!!!
			foreach ($myEntry['EntryMeta'] as $key => $value)
			{
				if(substr($value['key'], 0 , 5) == 'form-')
				{
					$broken = 1;
					foreach ($myAutomatic as $key20 => $value20) 
					{
						$value20 = $value20['TypeMeta']; // SPECIAL CASE, COZ IT'S BEEN MODIFIED IN CONTROLLER !!
						if($value['key'] == $value20['key'])
						{
							$broken = 0;
							break;
						}
					}
					if($broken == 1)
					{
						$value['display'] = 'none';
						$value['model'] = 'EntryMeta';
						$value['counter'] = $counter++;
						echo $this->element('input_textarea' , $value);
					}
				}
			}
		?>		
		<!-- END OF META ATTRIBUTES -->
        
        <div class="alert alert-info full fl">
			<strong>TABEL PESANAN BARANG</strong>
			<p style="color:red;" class="<?php echo (empty($myEntry)?'':'hide'); ?>">Silahkan pilih barang terlebih dahulu, lalu klik "Add to Cart" untuk menambahkan ke tabel pesanan barang.</p>
		</div>
		<div class="control-group <?php echo (empty($myEntry)?'':'hide'); ?>">
			<label class="control-label">Barang Dagang</label>
			<div class="controls">
				<input id="barang-dagang" class="input-medium" type="text" value="" readonly="true"/>
				<?php echo $this->Html->link('Browse',array('controller'=>'entries','action'=>'barang-dagang','admin'=>true, '?'=> array('popup'=>'init')),array('class'=>'btn btn-info disabled','id'=>'add-invoice-barang')); ?>
				<p class="help-block">Barang yg hendak dipesan dari Supplier terpilih.</p>
                <input type="hidden" id="jenis-barang">
			</div>
		</div>
		
		<div id="optgoods" style="display:none">
			<div class="control-group">
				<label class="control-label">Harga Beli</label>
				<div class="controls">
					<input id="harga" class="input-small price" type="text">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Jumlah Pesanan</label>
				<div class="controls">
					<input id="jumlah" class="input-small" type="text" value="0">
                    <span class="stock-satuan"></span>
				</div>
			</div>
			<div class="control-group">				
				<div class="controls">
					<button id="addToCart" type="button" class="btn btn-primary"><i class="icon-chevron-down icon-white"></i> Add to Cart <i class="icon-chevron-down icon-white"></i></button>
				</div>
			</div>			
		</div>
		
		<div>
			<table class="list bordered">
				<thead>
                    <tr>
                        <th>JUMLAH</th>
                        <th>JENIS / NAMA</th>                        
                        <th>HARGA</th>
                        <th>SUBTOTAL</th>
                        <th><?php echo (empty($myEntry)?'':'TERKIRIM'); ?></th>
                        <?php echo (empty($myEntry)?"":"<th>RETUR</th>"); ?>
                    </tr>
				</thead>
				
				<tbody id="myInputWrapper">
				<?php
					$grandtotal = 0;
					foreach ($myEntry['ChildEntry'] as $value)
                    {
                        if($value['Entry']['entry_type'] == 'purchase-detail')
                        {
                            $detailbarang = $this->Get->meta_details($value['Entry']['title'] , "barang-dagang");
                            $jenisbarang = $this->Get->meta_details($detailbarang['EntryMeta']['jenis_barang'] , "jenis-barang");
                            $subtotal = ($value['EntryMeta']['jumlah']-$value['EntryMeta']['retur'])*$value['EntryMeta']['harga'];
                            $grandtotal += $subtotal;
                            ?>
                <tr>					
					<td class="jumlah">
					    <h5><?php echo $value['EntryMeta']['jumlah'].' '.$detailbarang['EntryMeta']['satuan']; ?></h5>
					</td>
					<td class="nama">
					    <?php echo $jenisbarang['Entry']['title']." / ".$detailbarang['Entry']['title']; ?>
					</td>					
					<td class="harga">Rp <?php echo str_replace(',', '.', toMoney($value['EntryMeta']['harga'] , true , true) ); ?>,-</td>
					<td class="subtotal">Rp <?php echo str_replace(',', '.', toMoney($subtotal , true , true) ); ?>,-</td>
					<td class="terkirim"><?php echo (empty($value['EntryMeta']['terkirim'])?'-':$value['EntryMeta']['terkirim']); ?></td>
					<?php echo (empty($myEntry)?"":"<td class='retur'>".(empty($value['EntryMeta']['retur'])?'-':$value['EntryMeta']['retur'])."</td>"); ?>
				</tr>
                            <?php
                        }
                    }
				?>
				</tbody>
			</table>
			<div class="clear"></div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Grand Total</label>
			<div class="controls">
				<label style="margin-top: 5px;" id="grandtotal">
				    <strong>Rp <?php echo str_replace(',', '.', toMoney($grandtotal , true , true) ); ?>,-</strong>
                    <input type="hidden" value="<?php echo $grandtotal; ?>">
				</label>
			</div>
		</div>
		
		<?php
			// Our CKEditor Description Field !!
			$value = array();
			$value['key'] = 'form-keterangan';
			$value['validation'] = '';
			$value['model'] = 'Entry';
			$value['counter'] = 1;
			$value['input_type'] = 'textarea';
			$value['value'] = (isset($_POST['data'][$value['model']][$value['counter']]['value'])?$_POST['data'][$value['model']][$value['counter']]['value']:$myEntry[$value['model']]['description']);
			echo $this->element('input_'.$value['input_type'] , $value);
		?>
		<!-- myTypeSlug is for media upload settings purpose !! -->
        <input type="hidden" value="<?php echo getFrontCodeId(empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']); ?>" id="frontId"/>
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
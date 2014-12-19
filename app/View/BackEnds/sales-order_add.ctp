<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);
	if($isAjax == 0)
	{
		echo $this->element('admin_header_add');
		?>
		<script>
            $.fn.updateLabaBersih = function(){                
                var hiddenprofit = parseInt($("input[type=hidden]#hiddenprofit").val());                
                var diskon_nota = parseInt( $("input.diskon_nota").val() );                
                var ongkos_tambahan = parseInt( $("input.ongkos_tambahan").val() );
                
                var result = hiddenprofit - (isNaN(diskon_nota)?0:diskon_nota) - (isNaN(ongkos_tambahan)?0:ongkos_tambahan);                
                $("#cleanprofit > strong").html('Rp.'+number_format(result, 0, ',', '.')+',-');
            }
            
            $.fn.updateSisaBayar = function(){                
                var grandtotal = parseInt($("#grandtotal > input[type=hidden]").val());                
                var uang_muka = parseInt( $("input.uang_muka").val() );
                
                var result = grandtotal - (isNaN(uang_muka)?0:uang_muka);                
                $("#sisabayar > strong").html('Rp.'+number_format(result, 0, ',', '.')+',-');
            }
            
            $.fn.updateTotalHarga = function(){
                var hiddentotal = parseInt($("input[type=hidden]#hiddentotal").val());                
                var diskon_nota = parseInt( $("input.diskon_nota").val() );
                
                var result = hiddentotal - (isNaN(diskon_nota)?0:diskon_nota);                
                $("#grandtotal > strong").html('Rp.'+number_format(result, 0, ',', '.')+',-');                
                $("#grandtotal > input[type=hidden]").val(result);
                
                $.fn.updateSisaBayar();
            }
            
            $.fn.updateLabaKotor = function(value){                
                var hiddenprofit = parseInt($("input[type=hidden]#hiddenprofit").val());
                var result = hiddenprofit + parseInt(value) ;
                
                $("input[type=hidden]#hiddenprofit").val(result);                
                $("#grandprofit > strong").html('Rp.'+number_format(result, 0, ',', '.')+',-');
                
                $.fn.updateLabaBersih();
            }
            
            $.fn.updateHiddenTotal = function(value){                
                var hiddentotal = parseInt($("input[type=hidden]#hiddentotal").val());                
                var result = hiddentotal + parseInt(value);
                
                $("input[type=hidden]#hiddentotal").val(result);                
                $.fn.updateTotalHarga();
            }
            
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
                
                // teleport some element !!
                $('<?php echo ($view_mode?'div':'input'); ?>.diskon_nota').closest('div.control-group').appendTo('div.portal-diskon_nota');
                $('<?php echo ($view_mode?'div':'input'); ?>.uang_muka').closest('div.control-group').appendTo('div.portal-uang_muka');
                $('<?php echo ($view_mode?'div':'input'); ?>.ongkos_tambahan').closest('div.control-group').appendTo('div.portal-ongkos_tambahan');
                
                // disable uang_muka jika pembayaran langsung lunas !!
                $('input[type=radio].status_bayar').change(function(){
                    if($(this).val() == 'Lunas')
                    {
                        $('input.uang_muka').val('0');
                        $("input.uang_muka").change();
                        $('input.uang_muka').attr('readonly' , 'readonly');
                    }
                    else
                    {
                        $('input.uang_muka').removeAttr('readonly');
                    }
                });
                
                // Tabel Pesanan Barang !!
                $("input#barang-dagang").change(function(){
                    if($(this).val() == "")
                    {	
                        $("div#optgoods").slideUp('fast',function(){
                            $("input#jumlah").val("0");
                            $("input#diskon").val("0");
                        });
                    }
                    else
                    {				
                        $("div#optgoods").slideDown('fast');
                    }
                });
                
                $("button[type=button]#addToCart").click(function(){
                    var id = $("input#barang-dagang").val();
                    var jumlah = parseInt($("input#jumlah").val());                    
                    var harga = parseInt($("input#harga").val());
                    var diskon = parseInt($("input#diskon").val());                    
                    var kulakan = parseInt($("input[type=hidden]#buy-price").val());
                    var subtotal = jumlah*harga-diskon;
                    var profit = subtotal-jumlah*kulakan;
                    
                    // validate input !!
                    if(isNaN(jumlah) || isNaN(harga) || isNaN(diskon) || jumlah <= 0 || harga <= 0 || diskon < 0)
                    {
                        alert("Invalid input. Please try again.");
                        return;
                    }
                    
                    // cek barang sudah di list ato tidak...
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
                    
                    // isi tabel ...
                    var content = '<tr>';
                    content += '<td class="jumlah"><h5>'+jumlah+' '+$('span.stock-satuan').text()+'</h5></td>';
                    content += '<td class="nama">'+$("input#jenis-barang").val()+' / '+$('input#barang-dagang').val()+'</td>';                    
                    content += '<td class="harga">Rp.'+number_format (harga, 0, ',', '.')+',-</td>';
                    content += '<td class="diskon">Rp.'+number_format (diskon, 0, ',', '.')+',-</td>';
                    content += '<td class="subtotal">Rp.'+number_format (subtotal, 0, ',', '.')+',-<input type="hidden" value="'+subtotal+'"></td>';
                    content += '<td class="profit">Rp.'+number_format (profit, 0, ',', '.')+',-<input type="hidden" value="'+profit+'"></td>';
                    content += '<td class="action">';
                    content += '<a href="javascript:void(0)" class="btn btn-danger del-barang" style="display: inline;"><i class="icon-trash icon-white"></i></a>';
                    content += '<input type="hidden" name="data[barang][id][]" value="'+id+'"/>';
                    content += '<input type="hidden" name="data[barang][jumlah][]" value="'+jumlah+'"/>';
                    content += '<input type="hidden" name="data[barang][harga][]" value="'+harga+'"/>';                    
                    content += '<input type="hidden" name="data[barang][diskon][]" value="'+diskon+'"/>';                    
                    content += '<input type="hidden" name="data[barang][profit][]" value="'+profit+'"/>';                    
                    content += '</td>';
                    content += '</tr>';
                    
                    $('tbody#myInputWrapper').append(content).each(function(){
                        $("input#barang-dagang").val("");                        
                        $("input#barang-dagang").change();
                        
                        $.fn.updateHiddenTotal(subtotal);
                        $.fn.updateLabaKotor(profit);
                    });
                });

                $(document).on("click","a.del-barang",function(){
                    $(this).parents('tr').animate({opacity : 0},1000,function(){
                        $.fn.updateHiddenTotal('-' + $(this).find('td.subtotal input[type=hidden]').val() );                        
                        $.fn.updateLabaKotor('-' + $(this).find('td.profit input[type=hidden]').val() );
                        $(this).detach();
                    });
                });

                $("input.diskon_nota").change(function(){
                    $.fn.updateTotalHarga();
                    $.fn.updateLabaBersih();
                });
                
                $("input.uang_muka").change(function(){
                    $.fn.updateSisaBayar();
                });
                
                $("input.ongkos_tambahan").change(function(){
                    $.fn.updateLabaBersih();
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
				
				// save as published button !!
				$('button#save-button').click(function(){
					// cek barang ada atau tidak ...
                    if($("#grandtotal > input[type=hidden]").val() > 0)
                    {
                        <?php if(empty($myEntry)): ?>
                        // set last status button as published !!
                        $('select.status:last').val('1');
                        <?php endif; ?>
                        $(this).closest('form').find('button[type=submit]:first').click();
                    }
                    else
                    {
                        alert('Pesanan barang masih kosong!\nSilahkan pesan barang terlebih dahulu.');
                    }
				});
                
                // prevent from form submit !!!
                $('#optgoods input').keypress(function(e){
                    if(e.keyCode == 13)
                    {
                        e.preventDefault();                    
                        $("button[type=button]#addToCart").click();
                    }
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
                    if($value['key'] == 'form-status_kirim' && empty($myEntry) || $value['key'] == 'form-laba_bersih' || $value['key'] == 'form-total_harga')
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
		?>		
		<!-- END OF META ATTRIBUTES -->
		
		<div class="alert alert-info full fl">
			<strong>TABEL PESANAN BARANG</strong>
			<p style="color:red;" class="<?php echo (empty($myEntry)?'':'hide'); ?>">Silahkan pilih barang terlebih dahulu, lalu klik "Add to Cart" untuk menambahkan ke tabel pesanan barang.</p>
		</div>
		<div class="control-group <?php echo (empty($myEntry)?'':'hide'); ?>">
			<label class="control-label">Barang Dagang</label>
			<div class="controls">
				<input id="barang-dagang" class="input-large" type="text" value="" readonly="true"/>
				<?php echo $this->Html->link('Browse',array('controller'=>'entries','action'=>'barang-dagang','admin'=>true, '?'=> array('popup'=>'init')),array('class'=>'btn btn-info get-from-table')); ?>
				<p class="help-block">Barang yang hendak dipesan oleh Customer terpilih.</p>
                <input type="hidden" id="jenis-barang">
			</div>
		</div>
		
		<div id="optgoods" style="display:none">
		    <div class="control-group">
				<label class="control-label">Harga Jual</label>
				<div class="controls">
					Rp. <input id="harga" class="input-small price" type="number"> ,-
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Jumlah Pesanan</label>
				<div class="controls">
					<input id="jumlah" class="input-small" type="number" value="0">
                    <span class="stock-satuan"></span>
					<p id="stock-left" style="color:red;" class="help-block"></p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Diskon Item</label>
				<div class="controls">
					Rp. <input id="diskon" class="input-small" type="number" value="0"> ,-
					<p class="help-block">Diskon yang dipotongkan terhadap harga subtotal (harga x jumlah)</p>
				</div>
			</div>
			
			<input type="hidden" id="buy-price" value="0" />
			
			<div class="control-group">				
				<div class="controls">
					<button id="addToCart" type="button" class="btn btn-primary"><i class="icon-chevron-down icon-white"></i> Add to Cart <i class="icon-chevron-down icon-white"></i></button>
				</div>
			</div>			
		</div>
		
		<div>
			<table class="list bordered" id="tabel-pesanan-barang">
				<thead>
                    <tr>
                        <th>JUMLAH</th>					
                        <th>JENIS / NAMA BARANG</th>					
                        <th>HARGA</th>
                        <th>DISKON</th>
                        <th>SUBTOTAL</th>
                        <th>PROFIT</th>
                        <th><?php echo (empty($myEntry)?'':'TERKIRIM'); ?></th>
                        <?php echo (empty($myEntry)?"":"<th class='retur'>RETUR</th>"); ?>
                    </tr>
				</thead>
				
				<tbody id="myInputWrapper">
				<?php
					$grandtotal = 0;
					$grandprofit = 0;
                    $retur_existed = false;
					foreach ($myEntry['ChildEntry'] as $value)
                    {
                        if($value['Entry']['entry_type'] == 'sales-detail')
                        {
                            $detailbarang = $this->Get->meta_details($value['Entry']['title'] , "barang-dagang");
                            $jenisbarang = $this->Get->meta_details($detailbarang['EntryMeta']['jenis_barang'] , "jenis-barang");
                            
                            $subtotal = $value['EntryMeta']['jumlah'] * $value['EntryMeta']['harga']-$value['EntryMeta']['diskon'];
                            $grandtotal += $subtotal;

                            $grandprofit += $value['EntryMeta']['profit'];
                            ?>
                <tr>					
					<td class="jumlah">
					    <h5><?php echo $value['EntryMeta']['jumlah'].' '.$detailbarang['EntryMeta']['satuan']; ?></h5>
					</td>
					<td class="nama">
					    <?php echo $jenisbarang['Entry']['title']." / ".$detailbarang['Entry']['title']; ?>
					</td>					
					<td class="harga">Rp.<?php echo str_replace(',', '.', toMoney($value['EntryMeta']['harga'] , true , true) ); ?>,-</td>
					<td class="diskon">Rp.<?php echo str_replace(',', '.', toMoney($value['EntryMeta']['diskon'] , true , true) ); ?>,-</td>
					<td class="subtotal">Rp.<?php echo str_replace(',', '.', toMoney($subtotal , true , true) ); ?>,-</td>					
					<td class="profit">Rp.<?php echo str_replace(',', '.', toMoney($value['EntryMeta']['profit'] , true , true) ); ?>,-</td>
					<td class="terkirim"><?php echo (empty($value['EntryMeta']['terkirim'])?'-':$value['EntryMeta']['terkirim']); ?></td>
					<?php
                        if(!empty($myEntry))
                        {
                            echo "<td class='retur'>";
                            if(empty($value['EntryMeta']['retur']))
                            {
                                echo '-';
                            }
                            else
                            {
                                echo $value['EntryMeta']['retur'];
                                $retur_existed = true;
                            }
                            echo "</td>";
                        }
                    ?>
				</tr>    
                            <?php
                        }
                    }

                    if(!$retur_existed)
                    {
                        ?>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#tabel-pesanan-barang .retur').hide();
                        });
                    </script>
                        <?php
                    }
				?>
				</tbody>
			</table>
			<div class="clear"></div>
		</div>
		<input type="hidden" id="hiddentotal" value="<?php echo $grandtotal; ?>" />
		<input type="hidden" id="hiddenprofit" value="<?php echo $grandprofit; ?>" />
		
		<div class="control-group">
			<label class="control-label">Laba Kotor</label>
			<div class="controls">			
			    <div class="view-mode" id="grandprofit">
				    <strong>Rp.<?php echo str_replace(',', '.', toMoney($grandprofit , true , true) ); ?>,-</strong>
				</div>
			</div>
		</div>
		
<!--		teleport some element to these area-->
		<div class="portal-diskon_nota"></div>
		
		<div class="control-group">
			<label class="control-label">Total Harga</label>
			<?php
                if(!empty($myEntry['EntryMeta']['diskon_nota']))
                {
                    $grandtotal-= $myEntry['EntryMeta']['diskon_nota'];
                }
            ?>
			<div class="controls">
				<div class="view-mode" id="grandtotal">
				    <strong>Rp.<?php echo str_replace(',', '.', toMoney($grandtotal , true , true) ); ?>,-</strong>
                    <input type="hidden" value="<?php echo $grandtotal; ?>">
				</div>
			</div>
		</div>
<!--		teleport some element to these area-->
		<div class="portal-uang_muka"></div>
		
		<div class="control-group">
			<label class="control-label">Sisa Pembayaran</label>
			<div class="controls inline">
				<div class="view-mode" id="sisabayar">
				    <strong>Rp.<?php echo str_replace(',', '.', toMoney($myEntry['EntryMeta']['balance'] , true , true) ); ?>,-</strong>
				</div>
			</div>
			<div class="controls inline <?php echo (empty($myEntry)?'hide':''); ?>">
				<?php echo $this->Html->link('View Details',array('controller'=>'entries','action'=>$myType['Type']['slug'].'/'.$myEntry['Entry']['slug'],'admin'=>true, '?'=>array('type' => 'piutang') ),array('class'=>'btn btn-mini btn-primary'));	?>
			</div>
		</div>
		
<!--		teleport some element to these area-->
		<div class="portal-ongkos_tambahan"></div>
		
		<div class="control-group">
			<label class="control-label">Laba Bersih</label>
			<div class="controls">			
			    <div class="view-mode" id="cleanprofit">
				    <strong>Rp.<?php echo str_replace(',', '.', toMoney( (empty($myEntry['EntryMeta']['laba_bersih'])?'0':$myEntry['EntryMeta']['laba_bersih']) , true , true) ); ?>,-</strong>
				</div>
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

			// show status field if update (NEW ZPANEL FEATURE) !!
			$value = array();
			$value['counter'] = 3;
			$value['key'] = 'form-status';
			$value['validation'] = 'not_empty';
			$value['model'] = 'Entry';
			$value['input_type'] = 'dropdown';
			$value['list'][0]['id'] = '1';
			$value['list'][0]['name'] = 'Published';
			$value['list'][1]['id'] = '0';
			$value['list'][1]['name'] = 'Draft';
			$value['value'] = (isset($_POST['data'][$value['model']][$value['counter']]['value'])?$_POST['data'][$value['model']][$value['counter']]['value']:$myEntry[$value['model']]['status']);
			$value['display'] = 'none';
			echo $this->element('input_'.$value['input_type'] , $value);
		?>
		
		<!-- myTypeSlug is for media upload settings purpose !! -->
        <input type="hidden" value="<?php echo getFrontCodeId(empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']); ?>" id="frontId"/>
		<input type="hidden" value="<?php echo (empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']); ?>" id="myTypeSlug"/>
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<!-- always use submit button to submit form -->
			<button class="hide" type="submit"></button>

			<button id="save-button" type="button" class="btn btn-primary <?php echo ($view_mode?'hide':''); ?>"><?php echo $saveButton; ?></button>
			<?php
				if(empty($myEntry))
				{
					echo '<button id="save-as-draft" type="button" class="btn btn-inverse hide">Save as Draft</button>';
				}
			?>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/entries/<?php echo (empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).$myChildTypeLink.(empty($myEntry)?'':(empty($myChildTypeLink)?'?':'&').'lang='.(empty($lang)?substr($myEntry['Entry']['lang_code'], 0,2):$lang)); ?>'"><?php echo ($view_mode?'&laquo; Back':'Cancel'); ?></button>
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
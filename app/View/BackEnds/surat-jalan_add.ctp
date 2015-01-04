<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);

    // Custom Check !!
    if(!empty($myEntry['EntryMeta']['purchase_order']))
    {
        $this->request->query['retur-beli'] = 'true';
    }

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
                
                // EDIT THE TITLE IF RETUR PEMBELIAN !!
                <?php
                    if(!empty($this->request->query['retur-beli']))
                    {
                        ?>
                    $("div.title > h2").append(" (RETUR PEMBELIAN)");
                    
                    // make retur-beli menu active !!
                    $("div.sidebar a").removeClass("active");
                    $("div.sidebar a#retur-beli").addClass("active");
                        <?php
                    }
                ?>
                
                // Tabel Pengiriman Barang !!
                $("input#barang-dagang").change(function(){
                    
                    if($(this).val() == "")
                    {	
                        $("div#optgoods").slideUp('fast');
                    }
                    else
                    {				
                        var myslug = $(this).nextAll("input[type=hidden]").val();
                        $("div#optgoods").slideDown('fast',function(){
                            $("a#browse-gudang").attr("data-barang-dagang" , myslug );
                        });
                    }
                    
                    $("input#gudang").val("");
                    $("span#jumlah-stok").text("-");
                });
                
                $("button[type=button]#addToCart").click(function(){
                    var id_barang = $("input#barang-dagang").val();
                    var slug_barang = $("input#barang-dagang").nextAll("input[type=hidden]").val();
                    
                    var id_gudang = $("input#gudang").val();                    
                    var slug_gudang = $("input#gudang").nextAll("input[type=hidden]").val();
                    
                    if(id_barang.length <= 0 || id_gudang.length <= 0)
                    {
                        alert("Invalid input. Please try again.");
                        return;
                    }
                    
                    // cek barang & gudang sudah di list ato tidak...
                    var check = true;                    
                    var namabarang = "";
                    var namagudang = "";
                    $('tbody#myInputWrapper tr').each(function(i,el){                        
                        namabarang = $(el).find('td.nama').text().split('/');                        
                        namabarang = $.trim(namabarang[1]);
                        
                        namagudang = $(el).find('td.gudang').text();
                        
                        if(namabarang == id_barang && namagudang == id_gudang)
                        {
                            check = false;                            
                            return;
                        }
                    });			
                    if(check == false)
                    {
                        alert("Barang dari gudang yang sama sudah terdaftar.\nSilahkan tambahkan barang yang lainnya.");
                        return;
                    }

                    // fill content to be appended !!
                    var satuan = $('span.stock-satuan').text();
                    var jumlah_stok = parseInt($("span#jumlah-stok").text());

                    var content = '<tr>';                    
                    content += '<td class="nama">'+$("input#jenis-barang").val()+' / '+id_barang+'</td>';
                    content += '<td class="jumlah"><input min="1" max="'+jumlah_stok+'" type="number" class="input-mini" name="data[barang][jumlah][]" value="1" /></td>';                    
                    content += '<td class="satuan">'+satuan+'</td>';
                    content += '<td class="gudang">'+id_gudang+'</td>';
                    
                    content += '<td class="action">';
                    content += '<a href="javascript:void(0)" class="btn btn-danger del-barang" style="display: inline;"><i class="icon-trash icon-white"></i></a>';
                    
                    content += '<input type="hidden" name="data[barang][id-barang][]" value="'+id_barang+'"/>';
                    content += '<input type="hidden" name="data[barang][slug-barang][]" value="'+slug_barang+'"/>';
                    content += '<input type="hidden" name="data[barang][id-gudang][]" value="'+id_gudang+'">';
                    content += '<input type="hidden" name="data[barang][slug-gudang][]" value="'+slug_gudang+'">';
                    
                    content += '</td>';
                    content += '</tr>';			

                    $('tbody#myInputWrapper').append(content).each(function(){
                        $("input#barang-dagang").val("");				
                        $("input#barang-dagang").change();
                        $('input#sales-order , input#purchase-order').nextAll('a.cboxElement').addClass('disabled');
                    });
                });
                
                $(document).on("click","a.del-barang",function(){
                    $(this).parents('tr').animate({opacity : 0},1000,function(){                        
                        $(this).detach();
                        // re-enable browse supplier !!
                        if($('tbody#myInputWrapper tr').length == 0)
                        {
                            $('input#sales-order , input#purchase-order').nextAll('a.cboxElement').removeClass('disabled');
                        }
                    });
                });
                
                $('a#add-invoice-barang').click(function(e){
                    e.preventDefault();
                    $.colorbox({
                        href: $(this).attr('href')+($(this).is("[data-invoice]")?"&invoice="+$(this).attr('data-invoice'):""),
                        reposition: false,
                        onLoad: function() {
                            $('#cboxClose').show();
                        }
                    });
                });
                
                $('a#browse-gudang').click(function(e){
                    e.preventDefault();
                    $.colorbox({
                        href: $(this).attr('href')+"&barang-dagang="+$(this).attr('data-barang-dagang'),
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
	$targetSubmit = (empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).(empty($myEntry)?'/add':'/edit/'.$myEntry['Entry']['slug']).(!empty($this->request->query['retur-beli'])?'?retur-beli=true':'');
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
				
				// save as draft button !!
				$('button#save-as-draft').click(function(){
					// set last status button as draft !!
					$('select.status:last').val('0');
					$(this).closest('form').find('button[type=submit]:first').click();
				});
				
				// save as published button !!
				$('button#save-button').click(function(){
					// cek barang ada atau tidak ...
                    if( $('#myInputWrapper tr').length > 0 )
                    {
                        <?php if(empty($myEntry)): ?>
                        // set last status button as published !!
                        $('select.status:last').val('1');
                        <?php endif; ?>
                        $(this).closest('form').find('button[type=submit]:first').click();
                    }
                    else
                    {
                        alert('Tabel pengiriman barang masih kosong!\nSilahkan masukan barang yang hendak dikirimkan terlebih dahulu.');
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
                    if(empty($this->request->query['retur-beli']))
                    {
                        if($value['key']=='form-purchase_order' || $value['key']=='form-supplier')
                        {
                            $value['display'] = 'none';
                        }
                        else if($value['key'] == 'form-customer') // add validation !!
                        {
                            $value['validation'] .= 'not_empty';
                        }
                    }
                    else
                    {
                        if($value['key']=='form-sales_order' || $value['key']=='form-customer')
                        {
                            $value['display'] = 'none';
                        }
                        // khusus retur beli, PO harus ada !!
                        else if($value['key'] == 'form-supplier' || $value['key']=='form-purchase_order') // add validation !!
                        {
                            $value['validation'] .= 'not_empty';
                        }
                    }
					echo $this->element('input_'.$value['input_type'] , $value);
				}
			}
		?>		
		<!-- END OF META ATTRIBUTES -->
		
		<div class="alert alert-info full fl">
			<strong>TABEL PENGIRIMAN BARANG</strong>
			<p style="color:red;" class="<?php echo (empty($myEntry)?'':'hide'); ?>">Silahkan pilih barang terlebih dahulu, lalu pilih asal gudang, dan klik "Add to Cart" untuk menambahkan ke tabel pengiriman barang.</p>
		</div>
		<div class="control-group <?php echo (empty($myEntry)?'':'hide'); ?>">
			<label class="control-label">Barang Dagang</label>
			<div class="controls">
				<input id="barang-dagang" class="input-large" type="text" value="" readonly="true"/>
				<?php echo $this->Html->link('Browse',array('controller'=>'entries','action'=>'barang-dagang','admin'=>true, '?'=> array('popup'=>'init' , 'caller'=>$myType['Type']['slug'])),array('class'=>'btn btn-info','id'=>'add-invoice-barang')); ?>
				<input type="hidden"/>   <!-- will be filled with slug-code -->
                <p class="help-block">Barang yang hendak dikirimkan.</p>
			</div>
			<!-- hidden data -->
			<input type="hidden" id="jenis-barang">
		</div>
		
		<div id="optgoods" style="display:none">
		    <div class="control-group">            
				<label class="control-label">Gudang</label>
				<div class="controls">
					<input id="gudang" class="input-large" type="text" value="" readonly="true"/>
					<?php echo $this->Html->link('Browse',array('controller'=>'entries','action'=>'gudang','admin'=>true, '?'=> array('popup'=>'init') ),array('class'=>'btn btn-info','id'=>'browse-gudang','data-barang-dagang'=>'')); ?>
					<input type="hidden"/>   <!-- will be filled with slug-code -->
					<p class="help-block">Gudang tempat pengambilan barang.</p>	
					<p class="help-block" style="color:red;">Jumlah tersedia : <span id="jumlah-stok">-</span> <span class="stock-satuan"></span></p>
				</div>
			</div>
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
                        <th>JENIS / NAMA BARANG</th>
                        <th>JUMLAH KIRIM</th>
                        <th>SATUAN</th>
                        <th>GUDANG</th>
                        <?php echo (empty($myEntry)?'<th></th>':''); ?>
                    </tr>
				</thead>
				
				<tbody id="myInputWrapper">
				<?php
                    if(!empty($this->request->data))
                    {
                        foreach($this->request->data['barang']['slug-barang'] as $key => $value)
                        {
                            $detailbarang = $this->Get->meta_details($value , "barang-dagang");
                            $jenisbarang = $this->Get->meta_details($detailbarang['EntryMeta']['jenis_barang'] , "jenis-barang");
                            $detailgudang = $this->Get->meta_details($this->request->data['barang']['slug-gudang'][$key] , "gudang");
                            ?>
                <tr>
				    <td class="nama">
					    <?php echo $jenisbarang['Entry']['title']." / ".$detailbarang['Entry']['title']; ?>
					</td>
					<td class="jumlah">
                        <input min="1" max="<?php echo $this->request->data['barang']['jumlah'][$key]; ?>" type="number" class="input-mini" name="data[barang][jumlah][]" value="<?php echo $this->request->data['barang']['jumlah'][$key]; ?>" />
					</td>					
					<td class="satuan"><?php echo $detailbarang['EntryMeta']['satuan']; ?></td>
					<td class="gudang"><?php echo $detailgudang['Entry']['title']; ?></td>
					<td class="action">                       
                        <a href="javascript:void(0)" class="btn btn-danger del-barang" style="display: inline;"><i class="icon-trash icon-white"></i></a>
                        <input type="hidden" name="data[barang][id-barang][]" value="<?php echo $this->request->data['barang']['id-barang'][$key]; ?>"/>
                        <input type="hidden" name="data[barang][slug-barang][]" value="<?php echo $this->request->data['barang']['slug-barang'][$key]; ?>"/>                        
                        <input type="hidden" name="data[barang][id-gudang][]" value="<?php echo $this->request->data['barang']['id-gudang'][$key]; ?>">                        
                        <input type="hidden" name="data[barang][slug-gudang][]" value="<?php echo $this->request->data['barang']['slug-gudang'][$key]; ?>">
					</td>
				</tr>                            
                            <?php
                        }
                        ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('input#sales-order , input#purchase-order').nextAll('a.cboxElement').addClass('disabled');
                    });
                </script>        
                        <?php
                    }
                    else // VIEW MODE !!
                    {
                        foreach ($myEntry['ChildEntry'] as $value)
                        {
                            $detailbarang = $this->Get->meta_details($value['Entry']['title'] , "barang-dagang");
                            $jenisbarang = $this->Get->meta_details($detailbarang['EntryMeta']['jenis_barang'] , "jenis-barang");
                            $detailgudang = $this->Get->meta_details($value['EntryMeta']['gudang'] , "gudang");
                            ?>
                <tr>
				    <td class="nama">
					    <?php echo $jenisbarang['Entry']['title']." / ".$detailbarang['Entry']['title']; ?>
					</td>
					<td class="jumlah">
					    <h5><?php echo $value['EntryMeta']['jumlah']; ?></h5>
					</td>					
					<td class="satuan"><?php echo $detailbarang['EntryMeta']['satuan']; ?></td>
					<td class="gudang"><?php echo $detailgudang['Entry']['title']; ?></td>
				</tr>                            
                            <?php
                        }
                    }   
				?>
				</tbody>
			</table>
			<div class="clear"></div>
		</div>
		
		<?php
			// Our CKEditor Description Field !!
			$value = array();
			$value['key'] = 'form-keterangan';
			$value['validation'] = '';
			$value['model'] = 'Entry';
			$value['counter'] = 1;
			$value['input_type'] = 'textarea';
            if(!empty($this->request->query['retur-beli']))
            {
                $value['p'] = 'Cantumkan juga berapa besar surplus modal yang diperoleh akibat RETUR PEMBELIAN ini. (optional)';
            }
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

                // $myChildTypeLink move here !!
                $myChildTypeLink = (!empty($this->request->query['retur-beli'])?'?key=customer':'');
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
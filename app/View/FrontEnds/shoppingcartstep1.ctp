<?php
if(is_array($data)) extract($data , EXTR_SKIP);
$this->Html->addCrumb('Shopping Cart', '/shoppingcart/step1');

if(!empty($error)):
echo $error;
else:
?>

<script>
	$(document).ready(function(){
		// use this script for enable the button all the time (on hover or not)
		$('table.list tr td .btn').css('display','inline');
		$('div#child-content').on("mouseleave", 'table tr', function(){	
			$(this).find('td .btn').css('display', 'inline');
		});
		
		// CALCULATE TOTAL WEIGHT ALL THE PRODUCTS !!!!
		$.fn.calc_weight = function(){
			var totalweight = 0;
			var qty = 0;
			var weight = 0;
			$("select.quantity").each(function(){
				qty = parseInt($(this).val());
				weight = parseFloat($(this).next("input[type=hidden]").val());
				totalweight += qty * 1.00 * weight;
			});
			return totalweight;
		}
		
		// -------------------------------------------------------------------- //
		$('select.quantity').change(function(){
			var qty = parseInt($(this).val());
			var price = parseFloat($(this).parent("td").prev().find("span").html());
			var oldtotal = parseFloat($(this).parent("td").next().find("span").html());
			
			var newtotal = qty * 1.00 * price;
			$(this).parent("td").next().find("span").html(newtotal.toFixed(2));
			
			var oldsubtotal = parseFloat($('td.subtotal span').html());
			var newsubtotal = oldsubtotal - oldtotal + newtotal;
			$('td.subtotal span').html(newsubtotal.toFixed(2));
			
			var basic_ongkir = parseFloat($("input[type=hidden].basic_ongkir").val());
			var total_ongkir = basic_ongkir * 1.00 * $.fn.calc_weight();
			$("td.ongkir span").html(total_ongkir.toFixed(2));
			
			var newgrandtotal = newsubtotal + total_ongkir;
			$('td.grandtotal span').html(newgrandtotal.toFixed(2));
			
		});
		
		// CLEAR JNE COST SESSION IN SERVER !!
		$("button#clearjne").click(function(){
			$.ajaxSetup({cache: false});
			$.get(site+"instant_payment_notifications/ajaxcost",function(data){
				$("input[type=text]#from").val("");
				$("input[type=text]#to").val("");
				
				$("span#error_jne").html("");
				$("td.ongkir span").html("0.00");
				$("td.ongkir").css("color" , "red");
				$('td.grandtotal span').html(  $('td.subtotal span').html()  );
				
				// update basic ongkir too !!
				$("input[type=hidden].basic_ongkir").val("0");
			},'json');
		});
		
		// AJAX JNE COST !!
		$("button#checkjne").click(function()
		{	
			var from = $('input[type=text]#from').val();
			var to = $('input[type=text]#to').val();
			
			if(from.length > 2 && to.length > 2)
			{
				$("button#checkjne i b").html("Please wait...");
				$.ajaxSetup({cache: false});
				$.post(site+"instant_payment_notifications/ajaxcost",{
					from: from,
					to: to,
					courier: $('select#courier').val()
				},function(data){
					$("button#checkjne i b").html("CHECK");
					var basic_ongkir = 0;
					var newongkir = 0;
					if(data != "failed" && data.error == 0)
					{
						basic_ongkir = parseFloat(data.value);
						newongkir = basic_ongkir * $.fn.calc_weight();
					}
					
					$("td.ongkir span").html(newongkir.toFixed(2));
					$("td.ongkir").css("color" , "red");
					
					var subtotal = parseFloat($('td.subtotal span').html());
					var newgrandtotal = subtotal + newongkir;
					$('td.grandtotal span').html(newgrandtotal.toFixed(2));
					
					// update basic ongkir too !!
					$("input[type=hidden].basic_ongkir").val(basic_ongkir);
					
					if(data == "failed")
					{
						alert("Invalid Post !");
					}
					else
					{
						if(data.error == 0)
						{	
							alert("Jalur Pengiriman ditemukan !");
						}
						else
						{
							$("span#error_jne").html(data.value);
							alert(data.value);
						}
					}
				},'json');
			}
			else
			{
				alert("Please fill the origin and destination city correctly !");
			}
		});
		
		//autocomplete untuk mencari kota asal
		 $("#from").autocomplete({
			 minLength: 3,
			 delay: 3,
			 source: function(request, response) 
			 {
				 $.ajax({
				 url: site+"instant_payment_notifications/ajaxcity?type=origin",
				 dataType: "json",
				 data: 
				 {
					 term : request.term,
					 from: $('#from').val(),
				 },
				 success: function(data) 
				 {
				 	 if(data.error == 1)
				 	 {
				 	 	 $("span#error_jne").html(data.value);
				 	 }
				 	 else
				 	 {
				 	 	 $("span#error_jne").html("");
				 	 	 response( $.map( data, function( item )
						 {
						 return{
						 label: item.nama_kota,
						 value: item.nama_kota,
						 }
						 }));
				 	 }
				 }
				 });
			 },
		 });
		
		//autocomplete untuk mencari kota tujuan
		$("#to").autocomplete({
			 minLength: 3,
			 delay: 3,
			 source: function(request, response) 
			 {
				 $.ajax({
				 url: site+"instant_payment_notifications/ajaxcity?type=destination",
				 dataType: "json",
				 data: 
				 {
					 term : request.term,
					 to: $('#to').val(),
				 },
				 success: function(data) 
				 {
				 	 if(data.error == 1)
				 	 {
				 	 	 $("span#error_jne").html(data.value);
				 	 }
				 	 else
				 	 {
				 	 	 $("span#error_jne").html("");
				 	 	 response( $.map( data, function( item )
						 {
						 return{
						 label: item.nama_kota,
						 value: item.nama_kota,
						 }
						 }));
				 	 }
				 }
				 });
			 },
		 });
	});
</script>
<form action='<?php echo $imagePath; ?>shoppingcart/step2' method='POST'>
<table class="list nohover">
	<thead>
		<tr>
			<th>PRODUCT NAME</th>
			<th>ACTION</th>
			<th>PRICE</th>
			<th>QTY</th>
			<th>TOTAL</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$subtotal = 0;
			$totalweight = 0;
			foreach ($shoppingcart as $key => $value):
			$myEntry = $value['detail'];
		?>
		<tr>
			<td style="max-width: 180px;">
				<h5 class="title-code">
					<?php
						if($myEntry['Entry']['parent_id'] == 0)
						{
							echo $this->Form->Html->link($myEntry['Entry']['title'] , array("controller"=>$myEntry['Entry']['entry_type'] , "action"=>$myEntry['Entry']['slug']));
						}
						else
						{
							echo $this->Form->Html->link($myEntry['Entry']['title'] , array("controller"=>$myEntry['ParentEntry']['entry_type'] , "action"=>$myEntry['ParentEntry']['slug'],$myEntry['Entry']['slug']));
						}
					?>
				</h5>
				<p>
					<?php
						echo strip_tags($myEntry['Entry']['description']);
					?>
				</p>
			</td>
			<td>
				<a href="javascript:void(0)" onclick="show_confirm('Are you sure want to remove this product from your shopping cart ?','instant_payment_notifications/deletecart/<?php echo $myEntry['Entry']['id']; ?>')" class="btn btn-danger"><i class="icon-trash icon-white"></i></a>
			</td>
			<td>
				<?php echo "$<span>".$myEntry['EntryMeta']['price']."</span> USD"; ?>
			</td>
			<td>
				<input type='hidden' name='data[item_number][]' value='<?php echo $myEntry['Entry']['id']; ?>'>
				<select class='quantity' name='data[quantity][]' style='width:<?php echo calcNumberWidth($myEntry['EntryMeta']['quantity']); ?>px;'>
					<?php
						for($i=1 ; $i <= $myEntry['EntryMeta']['quantity'] ; ++$i)
						{
							if($i == $value['quantity'])
							{
								echo "<option SELECTED value='$i'>$i</option>";
							}
							else
							{
								echo "<option value='$i'>$i</option>";
							}
						}
					?>
				</select>
				<?php
					$weight = (empty($myEntry['EntryMeta']['weight'])?0:$myEntry['EntryMeta']['weight']);
					$totalweight += $weight * 1.00 * $value['quantity'];
				?>
				<input type="hidden" value="<?php echo $weight; ?>" />
			</td>
			<td>
				<?php
					$temp = $myEntry['EntryMeta']['price'] * 1.00 * $value['quantity'];
					$subtotal += $temp;
					echo "$<span>".number_format($temp,2)."</span> USD";
				?>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="applyhover">
			<td style="text-align: right;" colspan="4"><b>Sub Total Cost</b></td>
			<td class="subtotal"><b><?php echo "$<span>".number_format($subtotal,2)."</span> USD"; ?></b></td>
		</tr>
<!--	--------------------------------- JNE FORM HERE !! ---------------------------------------------	-->
		<tr>
			<td colspan="4">
				<h2>&lt; Jasa Pengiriman &gt;</h2>
				<table width="20%" border="3" cellspacing="0" cellpadding="0">
					 <tr>
					 <td width="19%">From</td>
					 <td width="81%"><input placeholder="Minimal 3 Huruf Awal Kota" type="text" id="from" value="<?php echo strtoupper($shoppingongkir['from']); ?>" /></td>
					 </tr>
					 <tr>
					 <td>To</td>
					 <td><input placeholder="Minimal 3 Huruf Awal Kota" type="text" id="to" value="<?php echo strtoupper($shoppingongkir['to']); ?>" /></td>
					 </tr>
					 <tr>
					 <td>Couriers</td>
					 <td><select id="courier"><option value="jne">JNE Regular Service (REG)</option></select></td>
					 </tr>
					 <tr>
					 <td></td>
					 <td align="right"><button id="checkjne" type="button" class="btn btn-primary"><i><b>CHECK</b></i></button>&nbsp;&nbsp;<button id="clearjne" type="button" class="btn btn-danger"><i><b>IGNORE</b></i></button></td>
					 </tr>
				</table>
				<span style="color: red;font-weight: bolder;" id="error_jne"></span>
			</td>
			<?php
				$ongkir = 0;
				if(isset($shoppingongkir) && $shoppingongkir['error']==0)
				{
					$ongkir = $shoppingongkir['value'];
				}
				$totalongkir = $ongkir * 1.00 * $totalweight;
			?>
			<td class="ongkir" style="vertical-align: middle">
				<input type="hidden" class="basic_ongkir" value="<?php echo $ongkir; ?>" />
				$<span><?php echo number_format($totalongkir,2); ?></span> USD
			</td>
		</tr>
		<tr class="applyhover">
			<td style="text-align: right;" colspan="4"><b>TOTAL COST</b></td>
			<td class="grandtotal"><b><?php echo "$<span>".number_format($subtotal+$totalongkir,2)."</span> USD"; ?></b></td>
		</tr>
	</tbody>
</table>
<div class="control-action">
	<button style="float: right;" type="submit" class="btn btn-primary" name="tostep2" value="tostep2"><i><b>NEXT STEP</b></i></button>
</div>
</form>

<?php
endif;
?>
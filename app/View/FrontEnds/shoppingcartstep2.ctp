<?php
if(is_array($data)) extract($data , EXTR_SKIP);
$this->Html->addCrumb('Shopping Cart', '/shoppingcart/step1');
$this->Html->addCrumb('Confirmation', '/shoppingcart/step2');
?>
<script>
	$(document).ready(function(){
		// use this script for enable the button all the time (on hover or not)
		$('table.list tr td .btn').css('display','inline');
		$('div#child-content').on("mouseleave", 'table tr', function(){	
			$(this).find('td .btn').css('display', 'inline');
		});
	});
</script>
<h2>&lt; SHOPPING CART CONFIRMATION &gt;</h2>
<table class="list nohover">
	<thead>
		<tr>
			<th>PRODUCT NAME</th>
			<th>PRICE</th>
			<th>QTY</th>
			<th>TOTAL</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$subtotal = 0;
			foreach ($shoppingcart as $key => $value):
			$myEntry = $value['detail'];
		?>
		<tr>
			<td style="max-width: 200px;">
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
				<?php echo "$<span>".$myEntry['EntryMeta']['price']."</span> USD"; ?>
			</td>
			<td><?php echo $value['quantity']; ?></td>
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
			<td style="text-align: right;" colspan="3"><b>Sub Total Cost</b></td>
			<td class="subtotal"><b><?php echo "$<span>".number_format($subtotal,2)."</span> USD"; ?></b></td>
		</tr>
		<tr>
			<td style="text-align: left;" colspan="3">
				<?php
					if($shoppingongkir['value'] > 0)
					{
						echo "Jasa pengiriman yang Anda pilih: ".strtoupper($shoppingongkir['courier'])." Regular Service (REG) (".$shoppingongkir['to'].")";
					}
					else
					{
						$shoppingongkir['value'] = 0;
						echo "Jasa pengiriman dilakukan secara manual";
					}
				?>
			</td>
			<td class="subtotal"><?php echo "$<span>".number_format($shoppingongkir['value'],2)."</span> USD"; ?></td>
		</tr>
		<tr class="applyhover">
			<td style="text-align: right;" colspan="3"><b>TOTAL COST</b></td>
			<td class="grandtotal"><b><?php echo "$<span>".number_format($subtotal+$shoppingongkir['value'],2)."</span> USD"; ?></b></td>
		</tr>
	</tbody>
</table>
<div style="text-align: right;">
<?php echo $this->Paypal->initialize('cart' , $data); ?>	
</div>
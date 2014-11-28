<STYLE type="text/css">
	.tableTd {
	   	border-width: 0.5pt; 
		border: dotted; 
	}
	.tableTdContent{
		border-width: 0.5pt; 
		border: solid;
	}
	#titles{
		font-weight: bolder;
	}
   
</STYLE>
<table>
	<tr>
		<td style="text-align:center" colspan="2"><b>Export To Excel Sample<b></td>
	</tr>
	<tr>
		<td><b>Date:</b></td>
		<td><?php echo date("F j, Y, g:i a"); ?></td>
	</tr>
	<tr>
		<td><b>Number of Rows:</b></td>
		<td style="text-align:left"><?php echo count($rows);?></td>
	</tr>
	<tr>
		<td></td>
	</tr>
		<tr id="titles">
			<td class="tableTd">Column 1</td>
			<td class="tableTd" style="width:200px;">Column 2</td>
		</tr>		
		<?php foreach($rows as $row):
			echo '<tr>';
			echo '<td class="tableTdContent">'.$row['Entry']['title'].'</td>';
			echo '<td class="tableTdContent">'.$row['Entry']['description'].'</td>';
			echo '</tr>';
			endforeach;
		?>
</table>
<?php
	// I'M NOT USING ANY LAYOUT, SO I AM USING EXIT METHOD !!
	//exit();
?>
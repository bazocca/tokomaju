<?php
    $this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);

    $this->Html->addCrumb($myType['Type']['name'], '/admin/entries/'.$myType['Type']['slug']);
    $this->Html->addCrumb($myEntry['Entry']['title'], '/admin/entries/'.(empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).'/edit/'.$myEntry['Entry']['slug'].(!empty($myChildType)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:''));
	$this->Html->addCrumb('Lihat Penempatan Gudang', '#');
?>
<div class="inner-header">
	<div class="title">
		<h2>VIEW <?php echo $myEntry['Entry']['title']." (".$total." ".$myEntry['EntryMeta']['satuan'].")"; ?></h2>
		<p class="title-description">Melihat seluruh area penempatan barang di berbagai gudang tertentu.</p>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("a#<?php echo (empty($myType)?'pages':$myType['Type']['slug']); ?>").addClass("active");
	});
</script>
<div class="inner-content autoscroll" id="inner-content">
    <?php
        if(empty($total))
        {
            ?>
    <div class="empty-state item">
		<div class="wrapper-empty-state">
			<div class="pic"></div>
			<h2>Stok sedang kosong!</h2>
			<?php echo $this->Form->Html->link('Tambah Stok Barang ke Gudang',array('action'=>'gudang'),array('class'=>'btn btn-primary')); ?>
		</div>
	</div>            
            <?php
        }
        else
        {
            ?>
    <table class="list bordered">
		<thead>
            <tr>
                <th class="date-field">NAMA GUDANG</th>
                <th>PEGAWAI GUDANG</th>
                <th>STOCK</th>
                <th class="keterangan">KETERANGAN</th>
                <th class="date-field">LAST MODIFIED</th>
            </tr>
		</thead>
		<tbody>
		<?php
			foreach ($myList as $value):
		?>	
		<tr>
			<td>
				<h5><?php echo $value['ParentEntry']['Entry']['title']; ?></h5>
			</td>
			<td>
				<?php echo $value['ParentEntry']['EntryMeta']['nama_pegawai']; ?>
			</td>			
			<td><h5><?php echo $value['EntryMeta']['stock']; ?></h5></td>
			<td>
			    <?php echo (empty($value['Entry']['description'])?'-':str_replace(chr(10) , '<br/>' , $value['Entry']['description'])); ?>
			</td>
			<td><?php echo date_converter($value['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></td>
		</tr>			
		<?php
			endforeach;
		?>
		</tbody>
	</table>            
            <?php
        }
    ?>			
</div>
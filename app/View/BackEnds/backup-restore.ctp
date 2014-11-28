<?php
	$this->Html->addCrumb($myTitle, '#');
?>
<script type="text/javascript">	
	function checkfile(sender) 
	{
	    var validExts = new Array(".sql");
	    var fileExt = sender.value;
	    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	    if (validExts.indexOf(fileExt) < 0) {
	    	
	      alert("Invalid file selected, valid files are of " +
	               validExts.toString() + " types.");
	               
	      $(sender).val("");
	               
	      return false;
	    }
	    else return true;
	}
	
	$(document).ready(function(){
		$("a#backup").addClass("active");

		$("button#backup-files").click(function(){
			var url = "admin/entries/backup/backup-files"; 
			window.location = site + url;
		});
		
		$("button#backup").click(function(){
			var url = "admin/entries/backup/backup"; 
			window.location = site + url;
		});
		
		$("button#clean").click(function(){
			var message = "Are you sure to delete your database ?\nWARNING: You cannot undo this action & please backup first !!";
			var url = "admin/entries/backup/clean"; 
			show_confirm(message , url);
		});
		
		$("form#restore").submit(function(){
			var message = "Are you sure to restore this sql database file ?\nWARNING: You cannot undo this action & please backup first !!";
			return confirm(message);
		});
	});
</script>
<div class="inner-header">
	<div class="title">
		<h2><?php echo strtoupper($myTitle); ?></h2>
		<p class="title-description">Backup<!-- , Clean, or Restore --> your entire database / uploaded files.</p>
	</div>
</div>
	
<div class="inner-content">
	<div class="control-group">
		<div class="controls">
			<button id="backup" title="Backup all your database" type="button" class="btn btn-primary">Backup Database</button>				
		</div>
	</div>
	<div class="control-group hide">
		<div class="controls">
			<button id="clean" title="Clear your full database" type="button" class="btn btn-danger">Clean Database</button>
		</div>
	</div>
	<?php
		echo $this->Form->create('Entry', array('action'=>'backup/restore','id'=>'restore','enctype'=>'multipart/form-data','class' => 'hide'));
	?>
		<div class="control-group">
			<div class="controls">
				<input REQUIRED name="data[fileurl]" type="file" onchange='checkfile(this);'>
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<input value="Restore Database" type="submit" class="btn btn-inverse">
			</div>
		</div>
		
	<?php echo $this->Form->end(); ?>
	<hr>
	<div class="control-group">
		<div class="controls">
			<button id="backup-files" title="Backup all your uploaded files" type="button" class="btn btn-info">Backup Uploaded Files</button>
		</div>
	</div>		
</div>
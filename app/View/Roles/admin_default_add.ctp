<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$this->Html->addCrumb('Master', '#');
	$this->Html->addCrumb('Role', '/admin/master/roles');
	if(empty($myRole))
	{
		$this->Html->addCrumb('Add New', '/admin/master/roles/add');
	}
	else
	{
		$this->Html->addCrumb('Edit '.$myRole['Role']['name'], '/admin/master/roles/edit/'.$myRole['Role']['id']);
	}
?>
<script type="text/javascript">
	$("a#master").addClass("active");
</script>
<div class="inner-header">
	<div class="title">
		<h2><?php echo (empty($myRole)?'ADD ROLES':$myRole['Role']['name']); ?></h2>
	</div>
</div>
<div class="inner-content">
<?php
	$saveButton = (empty($myRole)?'Add New':'Save Changes');
	echo '<form class="notif-change form-horizontal fl" accept-charset="utf-8" method="post" enctype="multipart/form-data" action="'.$imagePath.'admin/master/roles/'.(empty($myRole)?'add':'edit/'.$myRole['Role']['id']).'">';	
?>
	<fieldset>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		<?php			
			$value['counter'] = 0;
			$value['key'] = 'form-Name';
			$value['validation'] = 'not_empty';
			$value['value'] = $myRole['Role']['name'];
			$value['model'] = 'Role';
			$value['input_type'] = 'text';
			echo $this->element('input_'.$value['input_type'] , $value);
			
			$value['counter'] = 1;
			$value['key'] = 'form-Description';
			$value['validation'] = '';
			$value['value'] = $myRole['Role']['description'];
			$value['model'] = 'Role';
			$value['input_type'] = 'textarea';
			echo $this->element('input_'.$value['input_type'] , $value);
		?>
		
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<button type="submit" class="btn btn-primary"><?php echo $saveButton; ?></button>
        	<button type="button" class="btn" onclick="javascript: window.location=site+'admin/master/roles'">Cancel</button>
		</div>
	</fieldset>
	</form>	
</div>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="<?php echo $mySetting['title']; ?>">
	<meta name="tagline" content="<?php echo $mySetting['tagline']; ?>">
	<meta name="description" content="<?php echo $mySetting['description']; ?>">
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
<!-- 		FAVICON IMAGE -->		
	<link rel="shortcut icon" href="<?php echo $imagePath."favicon.ico"; ?>" type="image/x-icon" />	
		<?php
			echo $this->Html->css('bootstrap');
			echo $this->Html->css('admin/style');
			
			echo $this->Html->script('jquery-1.7.2.min');
			echo $this->Html->script('jquery.color');
			echo $this->Html->script('bootstrap.min');
			echo $scripts_for_layout;
		?>	
</head>
<body>
<?php echo $content_for_layout; ?>
</body>
</html>
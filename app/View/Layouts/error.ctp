<!DOCTYPE html>
<html lang="en">
  <head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="<?php echo $mySetting['title']; ?>">
	<meta name="tagline" content="<?php echo $mySetting['tagline']; ?>">
	<meta name="description" content="<?php echo $mySetting['description']; ?>">	
	<title><?php echo $title_for_layout; ?></title>

<!-- 		FAVICON IMAGE -->
	<link rel="shortcut icon" href="<?php echo $imagePath."favicon.ico"; ?>" type="image/x-icon" />		
	<?php
		echo $this->Html->css('bootstrap');
		echo $this->Html->css('admin/style');
	?>
</head>
<body style="text-align: center; color: white;">
<?php echo $content_for_layout; ?>
</body>
</html>
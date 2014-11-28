<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?>
		</title>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="<?php echo $mySetting['title']; ?>">		
		<meta name="tagline" content="<?php echo $mySetting['tagline']; ?>">		
		<meta name="description" content="<?php echo $mySetting['description']; ?>">
		<META NAME="ROBOTS" CONTENT="NOINDEX">
<!-- 		FAVICON IMAGE -->		
		<link rel="shortcut icon" href="<?php echo $imagePath."favicon.ico"; ?>" type="image/x-icon" />
		
<!-- 		SITE & LINKPATH SETTING FOR GLOBAL JAVASCRIPT -->		
		<script type="text/javascript">
			var site = '<?php echo $site; ?>';
		  	var linkpath = '<?php echo $imagePath; ?>';
		</script>
		
		<?php
			// ================================================== >>>
			// load our CSS script...
			// ================================================== >>>
			echo $this->Html->css('bootstrap');
			echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom');
			echo $this->Html->css('colorbox');
			echo $this->Html->css('jquery.fileupload-ui');
			echo $this->Html->css('admin/style');
						
			// ================================================== >>>
			// load our JS script...
			// ================================================== >>>
            echo $this->Html->script('jquery-1.7.2.min');
            echo $this->Html->script('jquery-ui-1.8.18.custom.min');
            echo $this->Html->script('jquery-ui-touch-punch');
            echo $this->Html->script('jquery-ui-timepicker-addon');
			echo $this->Html->script('bootstrap.min');
			
			echo $this->Html->script('admin');
            echo $this->Html->script('ajax');
            echo $scripts_for_layout;
		?>
		
		<!-- Le javascript
	    ================================================== -->
		<script src="<?php echo $imagePath; ?>js/jquery.imagesloaded.js"></script>
		<script src="<?php echo $imagePath; ?>js/jquery.colorbox.js"></script>
		<script src="<?php echo $imagePath; ?>js/validation.js"></script>
		<script src="<?php echo $imagePath; ?>js/script.js"></script>
		<script src="<?php echo $imagePath; ?>js/media.js"></script>

<!-- 		for CK Editor -->
	    <script type="text/javascript" src="<?php echo $imagePath; ?>js/ckeditor/ckeditor.js"></script>
	    <script type="text/javascript" src="<?php echo $imagePath; ?>js/ckeditor/adapters/jquery.js"></script>
	    
<!-- 		for Cropping Image -->
		<link rel="stylesheet" href="<?php echo $imagePath; ?>css/jquery.jcrop.css" type="text/css" />	
		<script type="text/javascript" src="<?php echo $imagePath; ?>js/jquery.jcrop.js"></script>	    
	    
<!-- 		for jquery uploading file -->
		<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/vendor/jquery.ui.widget.js"></script>
		<!-- The Templates plugin is included to render the upload/download listings -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/tmpl.min.js"></script>
		<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/load-image.min.js"></script>
		<!-- The Canvas to Blob plugin is included for image resizing functionality -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/canvas-to-blob.min.js"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/jquery.iframe-transport.js"></script>
		<!-- The basic File Upload plugin -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/jquery.fileupload.js"></script>
		<!-- The File Upload file processing plugin -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/jquery.fileupload-fp.js"></script>
		<!-- The File Upload user interface plugin -->
		<script src="<?php echo $imagePath; ?>js/uploadfile/jquery.fileupload-ui.js"></script>
	</head>

	<body>
		<div class="container-fluid">
			<div class="layout-body" style="background: none; box-shadow: none;">
				<div class="content" style="margin: 0;">
					<div id="child-content" class="media inner-content" style="margin: 0;">
						<?php echo $this->Session->flash(); ?>
						<?php echo $content_for_layout; ?>
					</div>
				</div>
      		</div>
	    </div>
	</body>
</html>

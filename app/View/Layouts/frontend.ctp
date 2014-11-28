<?php	
	$this->Get->create($data);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?>
		</title>
		<?php
			echo $this->Html->meta('rss' , '/entries/index.rss');
		?>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="<?php echo $mySetting['title']; ?>">		
		<meta name="tagline" content="<?php echo $mySetting['tagline']; ?>">		
		<meta name="description" content="<?php echo $mySetting['description']; ?>">
		<?php
            // ----------------- Open Graph protocol ---------------------
            if(!empty($data['myEntry']))
            {
            	if(!empty($data['myEntry']['Entry']['title']))
	            {
	                echo '<meta property="og:title" content="'.$data['myEntry']['Entry']['title'].'" />';
	            }
	            
	            if(!empty($data['myEntry']['EntryMeta']['teaser']))
	            {
	                echo '<meta property="og:description" content="'.strip_tags($data['myEntry']['EntryMeta']['teaser']).'" />';
	            }
	            else if(!empty($data['myEntry']['Entry']['description']))
	            {
	                echo '<meta property="og:description" content="'.strip_tags($data['myEntry']['Entry']['description']).'" />';
	            }
	            
	            if(!empty($data['myEntry']['Entry']['main_image']))
	            {
	                echo '<meta property="og:image" content="'.$this->Get->host_name().'img/upload/'.$data['myEntry']['Entry']['main_image'].'.'.$data['myImageTypeList'][$data['myEntry']['Entry']['main_image']].'" />';
	            }
            }
        ?>
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
//			echo $this->Html->css('bootstrap-3');

			echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom');
			echo $this->Html->css('colorbox');
			
			// custom style !!
			echo $this->Html->css('admin/style');
			// echo $this->Html->css('style');
			echo $this->Html->css('loading');

			// ================================================== >>>
			// load our JS script...
			// ================================================== >>>
			echo $this->Html->script('jquery-1.11.0.min');
			// echo $this->Html->script('jquery.mobilemenu');

			echo $this->Html->script('jquery-ui-1.8.18.custom.min');
			echo $this->Html->script('jquery.fitvids'); // make video in ckeditor being responsive !!
			echo $this->Html->script('jquery-ui-timepicker-addon');

			echo $this->Html->script('jquery.imagesloaded');			
			echo $this->Html->script('jquery.colorbox');
			// use this script to make colorbox responsive !!
			echo $this->Html->script('colorbox_resize');

			// bootstrap script !!
			echo $this->Html->script('bootstrap.min');
			// echo $this->Html->script('bootstrap.min-3');
			
			// ** Use this script to check browser support HTML-5 certain input types !!
			// ** See http://diveintohtml5.info/detect.html for more details !!
			// echo $this->Html->script('modernizr-2.6.1.min');
		
	    	// custom javascript !!
	    	echo $this->Html->script('smooth_scrolling');
	    	echo $this->Html->script('number_format');
			echo $this->Html->script('admin');
			echo $this->Html->script('ajax');
			echo $scripts_for_layout;
			
	    	// GOOGLE ANALYTICS CODES
	    	if(!empty($mySetting['google_analytics_code']))
			{
				?>
				<script type="text/javascript">
				  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

				  ga('create', '<?php echo $mySetting['google_analytics_code']; ?>', 'auto');
				  // ga('set', '&uid', 'USER_ID'); // Set the user ID using signed-in user_id.
				  ga('send', 'pageview');
				</script>
				<?php
			}
			
			// Dynamic Website Header
			echo $mySetting['header'];
		?>
	</head>

	<body>
		<?php echo $mySetting['top_insert']; ?>
		<div class="container-fluid">
			<div class="header row-fluid">
				<div class="span7">
					<img src="<?php echo $imagePath; ?>images/logo.png" />
				</div>
				
				<div class="username span5">
					Hi, <?php echo (empty($user['User']['firstname'])?'Guest':$user['User']['firstname']." ".$user['User']['lastname']); ?>! <?php echo (empty($user['User']['firstname'])?"":$this->Html->link('Logout',array('controller'=>'accounts','action'=>'logout','admin'=>false), array('class' => 'btn btn-danger'))); ?>
				</div>
			</div>
			<div class="layout-header row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<div class="sidebar-title span2">
							<h4>FRONT END</h4>
						</div>
						
						<div class="body-title span10">
							<div class="breadcrumbs">
								<p><?php echo $this->Html->getCrumbs(' &raquo; ',$mySetting['title']); ?></p>
								<div class="live-time">
									<?php echo date($mySetting['date_format'] , gmt_adjustment()); ?>
									<!-- <i class="icon-time icon-white"></i>
									<span id="clock"></span> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
      		<div class="layout-body row-fluid">
      			<!--HEADER-->
				<div class="sidebar span2">
					<ul class="nav">
                    <?php
                        $this->Get->navigation(array('open_tag'=>'<li>','close_tag'=>'</li>'));
                    ?>
                    </ul>
				</div>
				
				<!--BODY-->
				<div class="content span10">
					<div id="child-content" class="media inner-content">
						<?php echo $this->Session->flash(); ?>
						<?php echo $content_for_layout; ?>
					</div>
				</div>
      		</div><!--/row-->	
	    </div><!--/.fluid-container-->		
		<?php echo $mySetting['bottom_insert']; ?>

		<!-- ADDITIONAL SCRIPT FOR LAYOUT -->
		<script>
			$(document).ready(function(){		
				// Target your .container, .wrapper, .post, etc.
    			$("body").fitVids();
		  	});
		</script>
	</body>
</html>

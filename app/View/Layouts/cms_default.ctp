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
		?>		
		<!-- Le javascript
	    ================================================== -->
		<script src="<?php echo $imagePath; ?>js/jquery.colorbox.js"></script>
		<script src="<?php echo $imagePath; ?>js/validation.js"></script>
		<script src="<?php echo $imagePath; ?>js/script.js"></script>
		<script src="<?php echo $imagePath; ?>js/media.js"></script>
		<script src="<?php echo $imagePath; ?>js/livedate.js"></script>
        <?php
            echo $this->Html->script('number_format');
			echo $this->Html->script('admin');
            echo $this->Html->script('ajax');
            echo $scripts_for_layout;    
        ?>
	</head>

	<body>
		<div class="container-fluid">
      		<div class="header row-fluid">
				<div class="span7">
<!--					<a alt="homepage" href="<?php echo $imagePath; ?>"><img src="<?php echo $imagePath; ?>images/logo.png" /></a>-->
				</div>
				
				<div class="username span5">
					Hi, <?php echo $user['User']['firstname']." ".$user['User']['lastname']; ?>! <?php echo $this->Html->link('Logout',array('controller'=>'accounts','action'=>'logout','admin'=>true), array('class' => 'btn btn-danger')); ?>
				</div>
			</div>
			
			<div class="layout-header row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<div class="sidebar-title span2">
							<h4>DASHBOARD</h4>
						</div>
						
						<div class="body-title span10">
							<div class="breadcrumbs">
								<p><?php echo $this->Html->getCrumbs(' &raquo; ',$mySetting['title']); ?></p>
								<div class="live-time">
									<?php echo date($mySetting['date_format'] , gmt_adjustment()); ?>
									<i class="icon-time icon-white"></i>
									<span id="clock"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="layout-body row-fluid">
				 
				<!--HEADER-->
				<div class="sidebar span2">
					<ul>
						<?php
							if($user['role_id'] <= 1)
							{
								echo "<li>";
								echo $this->Html->link('Master',array('controller'=>'master','action'=>'types'),array('id'=>'master'));
								echo "</li>";
							}
						?>
						<li>
							<?php									
								echo $this->Html->link('Settings',array('controller'=>'settings','action'=>'index'),array('id'=>'settings'));
							?>
						</li>						
						<li>
							<?php 
								echo $this->Html->link('Users',array('controller'=>'users','action'=>'index'),array('id'=>'users')); 
							?>
						</li>
						<li>
							<?php 
								echo $this->Html->link('Accounts',array('controller'=>'accounts','action'=>'index'),array('id'=>'accounts'));
							?>
						</li>
						<li class="hide">
							<?php 
								echo $this->Html->link('Media Library',array('controller'=>'entries','action'=>'media'),array('id'=>'media')); 
							?>
						</li>						
						<li class="<?php echo ($user['role_id'] > 1?'hide':''); ?>">
							<?php 
								echo $this->Html->link('Pages',array('controller'=>'entries','action'=>'pages'),array('id'=>'pages')); 
							?>
						</li>												
												
						<li class='separator hide'><?php echo $this->Html->link('Databases','#'); ?></li>
                        <?php
                            // define database sequence !!
                            $dbseq = $this->Get->meta_details('database-sequence' , 'pages');
                            $dbseq = explode(chr(10) , $dbseq['Entry']['description']);
                            $dbslug = '';
                            $urlaction = '';
                            foreach($dbseq as $key => $value)
                            {
                                if(substr($value , 0 , 1) == '#') // separator
                                {
                                    if(!empty($dbslug))
                                    {
                                        echo '</div>';
                                    }
                                    echo '<li class="separator"><a class="sidebar-menu" href="#">'.substr($value , 1).'</a></li>';
                                    echo '<div style="display:none;">';
                                }
                                else
                                {
                                    $dbslug = get_slug($value);                                    
                                    switch($dbslug)
                                    {
                                        case 'barang-masuk':
                                        case 'hutang':
                                            $urlaction = 'purchase-order?action='.$dbslug;
                                            break;
                                        case 'piutang':
                                            $urlaction = 'sales-order?action='.$dbslug;
                                            break;
                                        default:
                                            $urlaction = $dbslug;
                                    }
                                    echo "<li>";
									echo $this->Html->link($value,array('controller'=>'entries','action'=>$urlaction) ,array('id'=>$dbslug));
									echo "</li>";
                                }
                            }
                            echo '</div>';
						?>
						<li class='separator'><?php echo $this->Html->link('Others','#',array('class'=>'sidebar-menu')); ?></li>
                        <div style="display:none;">
                            <?php
                                echo "<li>";
                                echo $this->Html->link('backup data' ,array('controller'=>'entries','action'=>'backup') ,array('id'=>'backup'));
                                echo "</li>";
                            ?>
                        </div>						
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
		
<!-- 		ADDITIONAL SCRIPT FOR LAYOUT -->		
		<script>
			$(document).ready(function(){
                // Sidebar Menu Accordion !!
                $('a.sidebar-menu').attr('title' , 'Click here to expand menu.');
				$('a.sidebar-menu').parent('li').next("div").find('a.active').closest('div').show();
				$('a.sidebar-menu').click(function(e){                    
                    e.preventDefault();
                    $('a.sidebar-menu').not(this).parent('li').next("div:visible").slideUp('fast');
                    $(this).parent('li').next("div").slideToggle('fast');
				});				
                
				// CSS HELPER FUNCTION FOR SIDEBAR POSITION !! * CK Editor height *
				$("div.sidebar.span2 ul").css("padding-bottom" , (122 + parseInt($("div.container-fluid").height()) - parseInt($("div.sidebar.span2 ul").height())) + "px");
		  	});
		</script>
	</body>
</html>

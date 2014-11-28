<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$this->Html->addCrumb($myType['Type']['name'], '/admin/entries/'.$myType['Type']['slug']);
	if(!empty($myEntry))
	{
		$this->Html->addCrumb($myEntry['Entry']['title'], '/admin/entries/'.$myType['Type']['slug'].'/'.$myEntry['Entry']['slug'].($myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:''));
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		var orderFlag = '';
		switch('<?php echo $_SESSION['order_by']; ?>')
		{
            case '': // empty var ...
                orderFlag = 'by_order';
				break;
			case 'title DESC':
				orderFlag = 'z_to_a';
				break;
			case 'title ASC':
				orderFlag = 'a_to_z';
				break;
			case 'created DESC':
				orderFlag = 'latest_first';
				break;
			case 'created ASC':
				orderFlag = 'oldest_first';
				break;
		}
		if(orderFlag.length > 0)
        {
            $('a[alt='+orderFlag+'].order_by').html(string_unslug(orderFlag)+' <i class="icon-ok"></i>');
        }
		
		if("<?php echo (empty($popup)?'kosong':'berisi'); ?>"=="kosong")
		{
			$("a#<?php echo $myType['Type']['slug']; ?>").addClass("active");
		}
		$('input#searchMe').change(function(){
			$('a.searchMeLink').click();
		});

		// create margin-bottom !!
		$('div.inner-header.row-fluid > div:last > *').css('margin-bottom' , '10px');

		$('#attach-checked-data').click(function(e){
			if(!$(this).hasClass('disabled'))
			{
				var counter_stream = $('input#query-stream').val();
				var targetID = "<?php echo (empty($myEntry)?$myType['Type']['slug']:$myChildType['Type']['slug']); ?>";
				
				$('input.check-record').each(function(i,el){
					if($(this).attr('checked'))
					{
						var newTargetID = targetID + counter_stream;

						// add new browse ...
						if($('input#'+newTargetID).length == 0)
						{
							$('div.'+targetID+'-group').closest('div.control-group').find('a.add-raw').click();
						}

						var mytr = $('table#myTableList tr[alt='+$(this).val()+']');
						if(mytr.find("td.form-name").length > 0)
						{
						    $("input#"+newTargetID).val( mytr.find("td.form-name").text()+' ('+mytr.find("h5.title-code").text()+')');
						}
						else
						{
						    $("input#"+newTargetID).val( mytr.find("h5.title-code").text() );
						}

						$("input#"+newTargetID).nextAll("input[type=hidden]").val( mytr.find("input[type=hidden].slug-code").val() );
						$("input#"+newTargetID).change();

						counter_stream++;
					}
				});

				$.colorbox.close();
			}
			else
			{
				alert('Please check at least one item to attach first !');
				$('input#check-all').focus();
			}
		});
	});
</script>

<div class="inner-header <?php echo (empty($popup)?'':'layout-header-popup'); ?> row-fluid">
	<div class="span5">
		<div class="title">
			<h2><?php echo strtoupper(empty($myEntry)?$myType['Type']['name']:$myEntry['Entry']['title'].' - '.$myChildType['Type']['name']); ?></h2>
			<?php
				echo '<p class="title-description">'.(empty($myChildType)?$myType['Type']['description']:$myChildType['Type']['description']).'</p>';
				if($totalList > 0)
				{
					?>
					<p id="id-title-description" class="title-description">Last updated by <a href="javascript:void(0)"><?php echo (empty($lastModified['AccountModifiedBy']['username'])?$lastModified['AccountModifiedBy']['email']:$lastModified['AccountModifiedBy']['username']).'</a> at '.date_converter($lastModified['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?></p>
					<?php
				}
			?>
		</div>
	</div>
	<div class="span7">
		<?php
			if(!($myType['Type']['slug'] == 'pages' && $user['role_id'] >= 2 || !empty($popup)))
			{
				echo $this->Form->Html->link('Add '.(empty($myEntry)?$myType['Type']['name']:$myChildType['Type']['name']),array('action'=>$myType['Type']['slug'],(empty($myEntry)?'':$myEntry['Entry']['slug'].'/').'add','?'=>$extensionPaging ),array('class'=>'btn btn-primary fr right-btn get-started'));
			}
		?>
		<div class="btn-group">
			<button class="btn"><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i></button>
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span>&nbsp;</a>
			<ul class="dropdown-menu">
				<?php
					echo '<li>';
					echo $this->Form->Html->link("By Order",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage order_by" , "alt"=>"by_order"));
					echo '</li>';
					
					// sort by Title !!
					echo '<li>';
					echo $this->Form->Html->link("A To Z",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage order_by" , "alt"=>"a_to_z"));
					echo '</li>';
					echo '<li>';
					echo $this->Form->Html->link("Z To A",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage order_by" , "alt"=>"z_to_a"));
					echo '</li>';
					
					// sort by Date !!
					echo '<li>';
					echo $this->Form->Html->link("Latest First",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage order_by" , "alt"=>"latest_first"));
					echo '</li>';
					echo '<li>';
					echo $this->Form->Html->link("Oldest First",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$paging,'?'=>$extensionPaging) , array("class"=>"ajax_mypage order_by" , "alt"=>"oldest_first"));
					echo '</li>';
				?>
			</ul>
		</div>
		<?php
			if(count($mySetting['language']) > 1)
			{
				?>
		<div class="btn-group lang-selector" style="margin-right: 10px;">
			<a id="lang_identifier" class="btn" href="#"><?php echo (empty($this->request->query['lang'])?substr($mySetting['language'][0], 0,2):strtoupper($this->request->query['lang'])); ?></a>
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span>&nbsp;</a>
			<ul class="dropdown-menu">
			<?php
				$extensionLang = $extensionPaging;
				foreach ($mySetting['language'] as $key => $value) 
				{
					$this_lang = strtolower(substr($value, 0,2));
					if(empty($myEntry) || !empty($myEntry) && !empty($parent_language[$this_lang]))
					{
						$extensionLang['lang'] = $this_lang;
						echo '<li>';
						echo $this->Form->Html->link($value,array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$parent_language[$this_lang]),'index','1','?'=>$extensionLang) , array("class"=>"ajax_mypage langLink"));
						echo '</li>';
					}
				}					
			?>
			</ul>
		</div>		
				<?php
			}
		?>
		<div class="input-prepend" style="margin-right: 5px;">
			<span class="add-on" style="margin-right: 3px; margin-top : 9px;">
				<?php
					echo $this->Form->Html->link("<i class='icon-search'></i>",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index','1','?'=>$extensionPaging) , array("class"=>"ajax_mypage searchMeLink","escape"=>false));
				?>
			</span>
			<input style="width: 160px;" id="searchMe" class="span2" type="text" placeholder="search item here...">
		</div>

		<?php if(!empty($popup) && !empty($stream)): ?>
		<div style="margin:0 !important" class="clear"></div>
		<button id="attach-checked-data" class="btn btn-primary right-btn fr disabled">Attach checked data</button>
		<input type="hidden" id="query-stream" value="<?php echo $stream; ?>">
		<?php endif; ?>
	</div>
</div>
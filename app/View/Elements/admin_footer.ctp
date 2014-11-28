<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	if($totalList > 0)
	{
		?>
			<div class="pagination fr">
				<ul>
					<?php
						$extensionPaging = array();
						if(!empty($myEntry) && $myType['Type']['slug'] != $myChildType['Type']['slug'])
						{
							$extensionPaging['type'] = $myChildType['Type']['slug'];
						}

						if(!empty($popup))
						{
							$extensionPaging['popup'] = 'ajax';
						}
						
						echo '<li id="myPagingFirst" class="'.($paging<=1?"disabled":"").'">';
						echo $this->Form->Html->link("First",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index','1','?'=>$extensionPaging) , array("class"=>"ajax_mypage"));
						echo '</li>';
						
						echo '<li id="myPagingPrev" class="'.($paging<=1?"disabled":"").'">';
						echo str_replace('amp;', '', $this->Form->Html->link("&laquo;",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',($paging-1),'?'=>$extensionPaging), array("class"=>"ajax_mypage")));
						echo '</li>';
						
						for ($i = $left_limit , $index = 1; $i <= $right_limit; $i++ , $index++)
						{
							echo '<li id="myPagingNum'.$index.'" class="'.($i==$paging?"active":"").'">';
							echo $this->Form->Html->link($i,array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$i,'?'=>$extensionPaging) , array("class"=>"ajax_mypage"));				
							echo '</li>';
						}
					
						echo '<li id="myPagingNext" class="'.($paging>=$countPage?"disabled":"").'">';
						echo str_replace('amp;', '', $this->Form->Html->link("&raquo;",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',($paging+1),'?'=>$extensionPaging) , array("class"=>"ajax_mypage")));
						echo '</li>';
						
						echo '<li id="myPagingLast" class="'.($paging>=$countPage?"disabled":"").'">';
						echo $this->Form->Html->link("Last",array("action"=>$myType['Type']['slug'].(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']),'index',$countPage,'?'=>$extensionPaging), array("class"=>"ajax_mypage"));
						echo '</li>';
					?>
				</ul>
			</div>
		<?php
	}
?>
<?php
	$this->set('documentData', array(
    'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'));
	
	$this->set('channelData', array(
	    'title' => __("Most Recent Posts"),
	    'link' => $this->Html->url(array(
			'controller' => 'entries',
			'action' => 'index',
			'ext' => 'rss'
		)),
	    'description' => __("Latest updates"),
	    'language' => 'en-us'));
		
	// You should import Sanitize
	App::import('Sanitize');
	
	foreach ($myList as $key => $post) 
	{
		// This is the part where we clean the body text for output as the description
	    // of the rss item, this needs to have only text to make sure the feed validates
	    $bodyText = (!empty($post['EntryMeta']['teaser'])?$post['EntryMeta']['teaser']:$post['Entry']['description']);
	    $bodyText = preg_replace('=\(.*?\)=is', '', $bodyText);
	    $bodyText = $this->Text->stripLinks($bodyText);
	    $bodyText = Sanitize::stripAll($bodyText);
	    $bodyText = $this->Text->truncate($bodyText, 400, array(
	        'ending' => '...',
	        'exact'  => true,
	        'html'   => true,
	    ));
		
		// prepare rss options !!
		$options = array();
		$options['title'] = $post['Entry']['title'];
		$options['link'] = '/'.$post['Entry']['entry_type'].'/'.$post['Entry']['slug'];
		$options['guid'] = array('url' => $options['link'], 'isPermaLink' => 'true');
		$options['description'] = $bodyText;
		
		if(!empty($post['EntryMeta']['poster']))
		{
			$options['dc:creator'] = $post['EntryMeta']['poster'];
		}
		$options['pubDate'] = $post['Entry']['created'];
		
	    echo $this->Rss->item(array(), $options);
	}
?>
<?php
class GetHelper extends AppHelper 
{
	var $helpers = array('Form', 'Html', 'Js', 'Time');
	var $data = NULL;
	var $countListPerPage = 1000;
	
	// DATABASE MODEL...
	var $Entry = NULL;	
	var $Type = NULL;
	var $Setting = NULL;
	var $EntryMeta = NULL;
	var $Account = NULL;
	
	public function __construct(View $View, $settings = array())
	{
	    // set needed database model ...
		$this->Entry = ClassRegistry::init('Entry');
		$this->Type = ClassRegistry::init('Type');
		$this->TypeMeta = ClassRegistry::init('TypeMeta');
		$this->Setting = ClassRegistry::init('Setting');
		$this->EntryMeta = ClassRegistry::init('EntryMeta');
		$this->Account = ClassRegistry::init('Account');
	    
	    parent::__construct($View);
	}
	
	/**
	* as a constructor for this helper class
	* @param array $data contains source data from the controller
	* @return true
	* @public
	**/
	function create($data = array())
	{
		$this->data = $data;
		$this->data['mySetting'] = $this->Setting->get_settings();
	}
	
	function getData()
	{
		return $this->data;
	}
	
	function getType($slug)
	{
		return $this->Type->findBySlug($slug);
	}

	function getRefererUrl($imagePath , $url_lang = NULL , $queryURL = array())
	{
		if($_SESSION['allowRefererURL']) { return $_SERVER['HTTP_REFERER']; }
		
		extract($this->data , EXTR_SKIP);
        // check using pagination or not ...
        $pagination = '';
        foreach ((empty($myChildType)?$myType['TypeMeta']:$myChildType['TypeMeta']) as $key => $value) 
		{
			if($value['TypeMeta']['key'] == 'pagination')
			{
				$pagination = '1';
				break;
			}
		}
		
        $parseQueryURL = get_more_extension($queryURL);
		if(empty($myChildType))
		{
			$backlink = $imagePath.$url_lang.$myType['Type']['slug'].'/'.$pagination.$parseQueryURL;
		}
		else
		{
			$backlink = $imagePath.$url_lang.$myType['Type']['slug'].'/'.$myParentEntry['Entry']['slug'].'/'.$pagination.(empty($parseQueryURL)?'?type='.$myChildType['Type']['slug']:$parseQueryURL.'&type='.$myChildType['Type']['slug']);
		}

		return $backlink;
	}

	/**
	* generate url transition to other language , but still in same topic
	* i.e. (domain.com/about <=> domain.com/id/tentang-kita)
	* @param array $url contains source url from the controller ($this->request->url)
	* @param array $slugs contains any kind of slug that want to be converted based on their language
	* @return array $result contains url transition for all language available in website
	* @public
	**/
	function changeLangUrl($url , $slugs = array())
	{
		$pecahurl = array();
		$langslugs = array();
		
		if($url != '/')
		{
			$pecahurl = explode('/', $url);
			$temp_lang = $this->Entry->get_lang_url();
			if(strtolower($temp_lang['language']) == strtolower($pecahurl[0])) // link contain language !!
			{
				// terminate language in url !!
				array_shift($pecahurl);
			}
			
			foreach ($slugs as $key => $value) 
			{
				$langslugs[$value] = $this->Entry->findBySlug($value);
			}
		}
		
		$result = array();
		foreach ($this->data['mySetting']['language'] as $key => $value) 
		{
			$newlang = implode("/", $pecahurl);
			$mylang = strtolower(substr($value, 0,2));
			$result[$mylang] = "";
			
			if($key > 0) // NOT default language !!
			{
				$result[$mylang] = $mylang.'/';
			}
			
			// CHANGE OLD SLUG WITH NEW SLUG, BASED ON THE LANGUAGE !!
			foreach ($langslugs as $subkey => $subvalue) 
			{
				if(!empty($subvalue['Entry']['lang_code']))
				{
					$temp_entry = $this->Entry->findByLangCode( $mylang.'-'.substr($subvalue['Entry']['lang_code'], 3) );
					if(!empty($temp_entry))
					{
						$newlang = strrev( preg_replace('/'.strrev($subkey).'/', strrev($temp_entry['Entry']['slug']) , strrev($newlang) , 1 ) );
					}
				}
			}
			
			$result[$mylang] .= $newlang;
		}
		
		return $result;
	}
	
	function meta_details($slug = NULL , $entry_type = NULL , $parentId = NULL , $id = NULL , $ordering = NULL , $lang = NULL , $title = NULL)
	{	
		if(!is_null($slug))
		{
			$options['conditions']['Entry.slug'] = $slug;
		}		
		if(!is_null($entry_type))
		{
			$options['conditions']['Entry.entry_type'] = $entry_type;
		}
		if(!is_null($parentId))
		{
			$options['conditions']['Entry.parent_id'] = $parentId;
		}
		if(!is_null($id))
		{
			$options['conditions']['Entry.id'] = $id;
		}
        if(!is_null($ordering))
        {
            $options['order'] = array('Entry.sort_order '.$ordering);
        }
		if(!is_null($lang))
		{
			$options['conditions']['Entry.lang_code LIKE'] = $lang.'-%';
		}
		if(!is_null($title))
		{
			$options['conditions']['Entry.title'] = $title;
		}

		return (empty($options)?false:breakEntryMetas($this->Entry->find('first',$options)));
	}
	
	function account_name($username = NULL, $id = NULL)
	{
	    if(!empty($username))
        {
            $value = $this->Account->findByUsername($username);
            $result = $value['User']['firstname']." ".$value['User']['lastname'];
        }
        else if(!empty($id))
        {
            $value = $this->Account->findById($id);
            $result = $value['User']['firstname']." ".$value['User']['lastname'];
        }
        else // SELECT ALL !!
        {
            $temp = $this->Account->find('all');
            foreach ($temp as $key => $value) 
            {
                $result[$value['Account']['id']] = $value['User']['firstname']." ".$value['User']['lastname'];
            }
        }
		return $result;
	}
	
	/**
	* wants to find out which parent entry that each of child entry have !!
	* @param array $myTypeSlug parent entry type
	* @return result
	* @public
	**/
	function parent_entry($myTypeSlug)
	{
		$temp = $this->Entry->find('all' , array(
			"conditions" => array(
				"Entry.entry_type" => $myTypeSlug
			)
		));
		foreach ($temp as $key => $value) 
		{
			foreach ($value['ChildEntry'] as $key10 => $value10) 
			{
				$result[$value10['title']] = $value['Entry'];
			}
		}
		return $result;
	}
	
	/**
	* for echoing our data to the view with specific html tag
	* @param array $result contains the data want to be echoed
	* @param string $open_tag the beginning html tag
 	* @param string $close_tag the ending html tag
	* @return true
	* @public
	**/
	private function _echo_list($result = array() , $open_tag = NULL , $close_tag = NULL)
	{
		if(!empty($open_tag))
		{
			foreach ($result as $key => $value) 
			{
				echo $open_tag.$value.$close_tag;
			}
		}
	}
	
	function host_name()
	{
		return parent::get_host_name();
	}
	
	function isAjax()
	{
		return ($this->data['isAjax'] == 0?'no':'yes');
	}
	
	/**
	* get our specific site settings
	* @param string $key contains type of settings you want to retrieve
	* @param string $open_tag[optional] the beginning html tag
 	* @param string $close_tag[optional] the ending html tag
	* @return string your request specific settings
	* @public
	**/
	function sites($passData)
	{
		extract($passData , EXTR_SKIP);
		$result = '';
		foreach ($this->data['mySetting'] as $key10 => $value10) 
		{
			if(!empty($value10[strtolower($key)]))
			{
				$result = $value10[strtolower($key)];
				break;
			}
		}
		echo (empty($open_tag)?'':$open_tag.$result.$close_tag);
		return $result;
	}

	/**
	* get specific field value from the certain entry
	* @param string $key contains certain field label you want to retrieve its value
	* @param string $open_tag[optional] the beginning html tag
 	* @param string $close_tag[optional] the ending html tag
	* @param string $order_num[optional] the offset
	* @return string $result value you'd requested
	* @public
	**/
    function entry($passData)
	{
		extract($passData , EXTR_SKIP);
		$myEntry = (empty($order_num)?$this->data['myEntry']:$this->data['myList'][$order_num]);
		$key = strtolower($key);
		$result = $myEntry['Entry'][$key];
		if(empty($result))
		{
			foreach ($myEntry['EntryMeta'] as $key10 => $value10) 
			{
				if($value10['key'] == 'form-'.$key)
				{
					$result = $value10['value'];
					break;
				}
			}
		}		
		echo (empty($open_tag)?'':$open_tag.$result.$close_tag);
		return $result;
	}
	
	/**
	* get specific field value from the last entry list
	* @param string $key contains certain field label you want to retrieve its value
	* @param string $open_tag[optional] the beginning html tag
 	* @param string $close_tag[optional] the ending html tag
	* @return string $result value you'd requested
	* @public
	**/
	function last_entry($passData)
	{
		$passData['order_num'] = count($this->data['myList'])-1;
		return $this->entry($passData);
	}
	
	/* $myEntryId */
	function entry_detail($passData)
	{
		extract($passData , EXTR_SKIP);
		if($this->data['myEntry']['Entry']['id'] == $myEntryId)
		{
			$data = $this->data;
		}
		else
		{	
			$data = $this->_get_detail_entry($myEntryId);
		}
		return $data;
	}
	
	/**
	* get a bunch of entries(link) based on parameter given
	* @param string $type[optional] contains slug database type
	* @param string $passKey[optional] contains specific key that entries must have
	* @param string $passValue[optional] contains specific value from certain key that entries must have
	* @param string $open_tag[optional] the beginning html tag
 	* @param string $close_tag[optional] the ending html tag
	* @param string $entry[optional] contains slug of the parent Entry (used if want to search certain child Entry)
	* @param string $childType[optional] contains slug of child type database (used if want to search certain child Entry)
	* @param string $orderField[optional] order field
	* @param string $orderDirection[optional] contains 'ASC' or 'DESC'
	* @param string $language[optional] contains language of the entries that want to be retrieved
	* @param integer $raw[optional] result given in raw or print mode(ignore if print mode)
	* @return array $result certain bunch of entries(link) you'd requested
	* @public
	**/
	function list_entry($passData = array())
	{	
		extract($passData , EXTR_SKIP);
		if( (empty($orderField) || empty($orderDirection)) && ($this->data['myType']['Type']['slug'] == strtolower($type) || empty($type)) && $this->data['language'] == strtolower($language))
		{
			$data = $this->data;
		}
		else
		{	
			if(empty($entry))
			{
				$data = $this->_get_list_entry($type , NULL , NULL , $orderField , $orderDirection , $language);
			}
			else 
			{
				$data = $this->_get_list_entry($type , $entry , (empty($childType)?$type:$childType) , $orderField , $orderDirection , $language);
			}
		}		
		if(empty($raw))
		{
			$passKey = strtolower($passKey);
			$result = array();
			foreach ($data['myList'] as $key => $value) 
			{
				$counter = 0;
				$totalCount = count($value['EntryMeta']);
				do
				{
					$temp = $value['EntryMeta'][$counter++];
					if(empty($passKey) && empty($passValue) || $temp['key'] == 'form-'.$passKey && $temp['value'] == $passValue)
					{
						if(empty($entry))
						{
							$result[] = $this->Html->link($value['Entry']['title'],
							array(
							'controller'=>$data['myType']['Type']['slug'],
							'action'=>$value['Entry']['slug']
							));
						}
						else
						{
							$result[] = $this->Html->link($value['Entry']['title'],
							array(
							'controller'=>$data['myType']['Type']['slug'],
							'action'=>$value['ParentEntry']['slug'],
							$value['Entry']['slug'].(empty($childType)?'':'?type='.$childType)
							));
						}
						break;
					}
				}
				while($counter < $totalCount);
			}
			$this->_echo_list($result , $open_tag , $close_tag);
		}
		else
		{
			$result = $data;
		}
		return $result;
	}

	/**
	* retrieve link lists of certain key category owned by entries based on certain type database
	* @param string $type[optional] contains slug database type
	* @param string $passKey contains specific key that entries must have
	* @param string $language[optional] contains language of the entries that want to be retrieved
	* @param string $open_tag[optional] the beginning html tag
 	* @param string $close_tag[optional] the ending html tag
	* @param integer $raw[optional] result given in raw or print mode(ignore if print mode)
	* @return array $result certain bunch of category lists you'd requested
	* @public
	**/
	function list_meta($passData)
	{
		extract($passData , EXTR_SKIP);
		if(($this->data['myType']['Type']['slug'] == strtolower($type) || empty($type)) && $this->data['language'] == strtolower($language))
		{
			$data = $this->data;
		}
		else
		{	
			$data = $this->_get_list_entry($type,NULL,NULL,NULL,NULL,$language);
		}
		
		if(empty($raw))
		{
			$temps = array();
			$result = array();
			$passKey = strtolower($passKey);
			foreach ($data['myList'] as $key => $value) 
			{							
				foreach ($value['EntryMeta'] as $key10 => $value10) 
				{				
					if($value10['key'] == 'form-'.$passKey && !in_array($value10['value'], $temps))
					{
						$temps[] = $value10['value'];
						$result[] = $this->Html->link($value10['value'],
						array(
						'controller'=>$data['myType']['Type']['slug'],
						'action'=>'?key='.$passKey.'&value='.parent::get_slug($value10['value'])
						));
					}
				}
			}
			$this->_echo_list($result , $open_tag , $close_tag);
		}
		else
		{
			$result = $data;
		}
		return $result;
	}
	
	/**
	* retrieve list of pages
	* @param string $language[optional] contains language of the entries that want to be retrieved
	* @param string $open_tag[optional] the beginning html tag
 	* @param string $close_tag[optional] the ending html tag
	* @param integer $raw[optional] result given in raw or print mode(ignore if print mode)
	* @return array $result list of pages
	* @public
	**/
	function navigation($passData = array())
	{
		extract($passData , EXTR_SKIP);
		$data = $this->_get_list_entry('pages',NULL,NULL,NULL,NULL,$language);
		if(empty($raw))
		{
			$result = array();
			foreach ($data['myList'] as $key => $value)
			{			
				$result[] = $this->Html->link($value['Entry']['title'],
				array(
				'controller'=>$value['Entry']['slug']
				));			
			}
			$this->_echo_list($result , $open_tag , $close_tag);
		}
		else
		{
			$result = $data;
		}
		return $result;
	}
	
	/**
	* get images link based on image ID (original, display, and thumbnail)
	* @param integer $id contains image id
	* @param integer $raw[optional] result given in raw or print mode(ignore if print mode)
	* @return array $result contains all images link from selected id 
	* @public
	**/
	function image_link($passData)
    {
        extract($passData , EXTR_SKIP);
        
        $name = '';
        $ext = '';
        $data = '';
        if(empty($id))
        {
            $name = '0';
            $ext = 'jpg';
        }
        else
        {
            $data = $this->_get_detail_entry($id);
            $name = $data['myEntry']['Entry']['id'];
            $ext = $data['myImageTypeList'][$data['myEntry']['Entry']['id']];
        }
        
        if(empty($raw))
        {
            $result['display'] = parent::get_linkpath().'img/upload/'.$name.'.'.$ext;
            $result['thumbnail'] = parent::get_linkpath().'img/upload/thumb/'.$name.'.'.$ext;
        }
        else
        {
            $result = $data;
        }
        return $result;
    }
	
	/**
	* get specific entry(link) from entry lists based on entry id 
	* @param integer $id contains id of the entry
	* @param integer $raw[optional] result given in raw or print mode(ignore if print mode)
	* @return mixed $result a selected entry you'd requested
	* @public
	**/
	function entry_link($passData)
	{	
		extract($passData , EXTR_SKIP);
		if($this->data['myEntry']['Entry']['id'] == $id)
		{
			$data = $this->data;
		}
		else
		{	
			$data = $this->_get_detail_entry($id);
		}
		if(empty($raw))
		{
			// IF THIS IS A CHILD ENTRY...
			if($data['myEntry']['Entry']['parent_id'] > 0)
			{
				$result = parent::get_host_name().$data['myEntry']['ParentEntry']['entry_type'].'/'.$data['myEntry']['ParentEntry']['slug'].'/'.$data['myEntry']['Entry']['slug'];
			}
			else // IF THIS IS A PARENT ENTRY...
			{
				$result = parent::get_host_name().$data['myEntry']['Entry']['entry_type'].'/'.$data['myEntry']['Entry']['slug'];
			}
		}
		else
		{
			$result = $data;
		}
		return $result;
	}
	
	/**
	* retrieve meta values from selected entry based on specific meta key 
	* @param integer $id contains id of the entry
	* @param string $passKey contains specific key that entries must have
	* @param integer $raw[optional] result given in raw or print mode(ignore if print mode)
	* @return array $result contains meta values you'd requested
	* @public
	**/
	function meta_value($passData)
	{
		extract($passData , EXTR_SKIP);
		$passKey = strtolower($passKey);
		if($this->data['myEntry']['Entry']['id'] == $id)
		{
			$data = $this->data;
		}
		else
		{	
			$data = $this->_get_detail_entry($id);
		}
		
		if(empty($raw))
		{
			$result = array();
			foreach ($data['myEntry']['EntryMeta'] as $key => $value) 
			{
				if($value['key'] == 'form-'.$passKey)
				{
					$result[] = $value['value'];
				}
			}
		}
		else
		{
			$result = $data;
		}
		return $result;
	}
	
	function outputConverter($inputType , $value , $myImageTypeList = NULL)
	{
		$maxLength = 100;
		$maxLineBreak = 5;
		$result = '';
		switch ($inputType) 
		{
			case 'file':
				$path_parts = pathinfo($value);
				$result = "<a href='".$this->get_linkpath()."files/".$value."'>".string_unslug($path_parts['filename'])."</a>";
				break;
			case 'ckeditor':
			case 'textarea':
				if(stripos($value, '</iframe>') !== FALSE) // skip if this is iframe.
				{
					$result = $value;
					break;
				}

				if($inputType == 'ckeditor')
				{
					$value = strip_tags($value , '<br><br/>');
					$value = str_replace( array('<br>','<br/>') , chr(10) , $value );
				}

				// set $maxLineBreak !!
				$lineBreak = array_filter(explode(chr(10), $value));
				array_splice($lineBreak, $maxLineBreak );
				$result = implode(chr(10), $lineBreak);

				// set $maxLength !!
				if(strlen($result) > $maxLength)
				{
					$result = substr($result,0,$maxLength)."...";
				}
				$result = str_replace(chr(10), '<br/>' , $result);
				break;
			case 'datetimepicker':
				$result = date_converter($value, $this->data['mySetting']['date_format'],$this->data['mySetting']['time_format']);
				break;	
			case 'datepicker':
				$result = date_converter($value, $this->data['mySetting']['date_format']);
				break;
			case 'checkbox':
				
				$pecah = explode('|', $value);
				foreach ($pecah as $subkey => $subvalue) 
				{
					$pecah[$subkey] = string_unslug($subvalue);
				}

				$result = implode('<br>', $pecah);
				
				break;
			case 'image':
				$result = '<div class="thumbs">'.(empty($popup)?$this->Html->link($this->Html->image('upload/thumb/'.$value.'.'.$myImageTypeList[$value]),'/img/upload/'.$value.'.'.$myImageTypeList[$value],array("escape"=>false,"class"=>"get-from-library")):$this->Html->image('upload/thumb/'.$value.'.'.$myImageTypeList[$value])).'</div>';
				break;
			case 'radio':
			case 'dropdown':
				$result = string_unslug($value);
				break;
			default:
				$result = $value;
				break;
		}
		return $result;
	}
	
	function staggingAdd($class = NULL)
	{
		extract($this->data , EXTR_SKIP);		
		$requestUri = $_SERVER['REQUEST_URI'];
		
		$startMark = strpos($requestUri, '/',(isLocalhost()?1:0));
		$qMark = strpos($requestUri, '?');
		
		$frontUrl = ($qMark===false?substr($requestUri, $startMark):substr($requestUri, $startMark , $qMark-$startMark));		
		if(substr($frontUrl, -1) != '/')
		{
			$frontUrl .= '/';
		}
		$rearUrl = ($qMark===false?'':substr($requestUri, $qMark));
		
		return $this->Html->link('Add '.(empty($myEntry)?$myType['Type']['name']:$myChildType['Type']['name']),$frontUrl.'add'.$rearUrl,array('class'=>(empty($class)?'btn btn-primary':$class)));
	}

	function staggingEdit($class = NULL)
	{
		extract($this->data , EXTR_SKIP);
		$requestUri = $_SERVER['REQUEST_URI'];
		
		$startMark = strpos($requestUri, '/',(isLocalhost()?1:0));		
		$endMark = strrpos($requestUri , '/' , (substr($this->request->url, -1) == '/'?-2:0));
		
		$frontUrl = substr($requestUri, $startMark , $endMark - $startMark);
		$rearUrl = substr($requestUri, $endMark);
		
		return $this->Html->link('Edit '.$myEntry['Entry']['title'],$frontUrl.'/edit'.$rearUrl.(!empty($myParentEntry)?'?type='.$myEntry['Entry']['entry_type']:''),array('class'=>(empty($class)?'btn btn-primary':$class)));
	}
	// -------------------------------------------------------------------------------- //
	// --------------------------- DATABASE FUNCTION ---------------------------------- //
	// -------------------------------------------------------------------------------- //
	/**
	* get a bunch of entries based on parameter given
	* @param string $myTypeSlug contains slug database type
	* @param string $myEntrySlug[optional] contains slug of the parent Entry (used if want to search certain child Entry)
	* @param string $myChildTypeSlug[optional] contains slug of child type database (used if want to search certain child Entry)
	* @param string $orderField[optional] order field
	* @param string $orderDirection[optional] contains 'ASC' or 'DESC'
	* @param string $lang[optional] contains language of the entries that want to be retrieved
	* @return array $result certain bunch of entries you'd requested
	* @public
	**/	
	function _get_list_entry($myTypeSlug , $myEntrySlug = NULL , $myChildTypeSlug = NULL , $orderField = NULL , $orderDirection = NULL , $lang = NULL)
	{				
		if($myTypeSlug == 'pages')
		{
			// manually set pages data !!
			$myType['Type']['name'] = 'Pages';
			
			$myType['Type']['slug'] = 'pages';
			$myType['Type']['parent_id'] = 0;
		}
		else
		{
			$myType = $this->Type->findBySlug($myTypeSlug);
		}
		$myEntry = (empty($myEntrySlug)?NULL:$this->meta_details($myEntrySlug));
		return $this->_admin_default($myType , 0 , $myEntry , NULL , NULL , $myChildTypeSlug , $orderField , $orderDirection , $lang);
	}
	
	/**
	* get specific entry from entry lists based on entry id 
	* @param integer $myEntryId contains id of the entry
	* @return array $result a selected entry you'd requested
	* @public
	**/
	function _get_detail_entry($myEntryId)
	{
		$myEntry = $this->Entry->findById($myEntryId);
		
		// if this is a child Entry...
		if($myEntry['Entry']['parent_id'] > 0)
		{
			$myParentEntry = $this->Entry->findById($myEntry['Entry']['parent_id']);
			$myType = $this->Type->findBySlug($myParentEntry['Entry']['entry_type']); // PARENT TYPE...
			
			$myChildTypeSlug = $myEntry['Entry']['entry_type'];
		}
		else // if this is a parent Entry ...
		{
			$myType = $this->Type->findBySlug($myEntry['Entry']['entry_type']);
		}		
		return $this->_admin_default_edit($myType , $myEntry , $myParentEntry , $myChildTypeSlug);
	}
	
	/**
	* querying to get a bunch of entries based on parameter given (core function)
	* @param array $myType contains record query result of database type
	* @param integer $paging[optional] contains selected page of lists you want to retrieve
	* @param array $myEntry[optional] contains record query result of the parent Entry (used if want to search certain child Entry)
	* @param string $myMetaKey[optional] contains specific key that entries must have
	* @param string $myMetaValue[optional] contains specific value from certain key that entries must have
	* @param string $myChildTypeSlug[optional] contains slug of child type database (used if want to search certain child Entry)
	* @param string $orderField[optional] order field
	* @param string $orderDirection[optional] contains 'ASC' or 'DESC'
	* @param string $lang[optional] contains language of the entries that want to be retrieved
	* @return array $data certain bunch of entries you'd requested
	* @public
	**/
	function _admin_default($myType = array(),$paging = NULL , $myEntry = array() , $myMetaKey = NULL , $myMetaValue = NULL , $myChildTypeSlug = NULL , $orderField = NULL , $orderDirection = NULL , $lang = NULL)
	{
		if(is_null($paging))
		{
			$paging = 1;
		}
		$data['mySetting'] = $this->data['mySetting'];
		$data['myType'] = $myType;
		$data['paging'] = $paging;
		$data['popup'] = $popup;
		if(!empty($myEntry))
		{			
			$data['myEntry'] = $myEntry;
			$myChildType = $this->Type->findBySlug($myChildTypeSlug);
			$data['myChildType'] = $myChildType;
		}

		// GENERATE TYPEMETA AGAIN WITH SORT ORDER !!
        $metaOrder = $this->TypeMeta->find('all' , array(
            'conditions' => array(
                'TypeMeta.type_id' => (empty($myEntry)?$myType['Type']['id']:$myChildType['Type']['id'])
            ),
            'order' => array('TypeMeta.id ASC')
        ));
        if(empty($myEntry))
        {
            $data['myType']['TypeMeta'] = $metaOrder;
        }
        else
        {
            $data['myChildType']['TypeMeta'] = $metaOrder;
        }

		// set paging session...		
		$countPage = $this->countListPerPage;
		
		// our list conditions... ----------------------------------------------------------------------------------////
		if(empty($myEntry))
		{
			$options['conditions'] = array(
				'Entry.entry_type' => $myType['Type']['slug'],
				'Entry.status' => 1,
				'Entry.parent_id' => 0
			);
		}
		else
		{
			$options['conditions'] = array(
				'Entry.parent_id' => $myEntry['Entry']['id'],				
				'Entry.entry_type' => $myChildTypeSlug,
				'Entry.status' => 1
			);
		}
		if($myType['Type']['slug'] != 'media')
		{
			$options['conditions']['Entry.lang_code LIKE'] = (empty($lang)?substr($data['mySetting']['language'][0], 0,2):$lang).'-%';
		}
		// find last modified... ----------------------------------------------------------------------------------////
		$options['order'] = array('Entry.modified DESC');
		$lastModified = $this->Entry->find('first' , $options);
		$data['lastModified'] = $lastModified;
		// end of last modified...
		
		$options['order'] = (empty($orderField)||empty($orderDirection)?array('Entry.sort_order ASC'):array('Entry.'.strtolower($orderField).' '.$orderDirection));		
		$mysql = $this->Entry->find('all' ,$options);
		
		// MODIFY OUR ENTRYMETA FIRST !!
		$mysqlTemp = array();
		foreach ($mysql as $key => $value) 
		{
			array_push($mysqlTemp, breakEntryMetas($value));
		}
		// SECOND FILTER GO NOW !!!
		$offset = ($paging==0? 0 : ($paging-1) * $countPage);
		$endset = $offset + $countPage;
		$resultTotalList = count($mysqlTemp);
		$data['totalList'] = $resultTotalList;
		for($key = $offset ; !empty($mysqlTemp[$key]) ; ++$key)
		{
			if(!($key < $endset || $paging==0))
			{
				break;
			}
			$myList[] = $mysqlTemp[$key];
		}
		$data['myList'] = $myList;
		
		// set New countPage
		$newCountPage = ceil($resultTotalList * 1.0 / $countPage);
		$data['countPage'] = $newCountPage;
		
		// for image input type reason...
		$data['myImageTypeList'] = $this->EntryMeta->embedded_img_meta('type');
		
		// FINAL TOUCH !!
		if(!empty($myMetaKey) && !empty($myMetaValue))
		{
			$data = $this->_admin_meta_options($data , $myMetaKey , $myMetaValue);
		}		
		return $data;
	}

	function _admin_meta_options($data , $myMetaKey , $myMetaValue)
	{
		$lastModified = 0;
		$data['totalList'] = 0;
		foreach ($data['myList'] as $key => $value) 
		{
			$state = 0;
			foreach ($value['EntryMeta'] as $key10 => $value10) 
			{
				if(substr($value10['key'], 5) == $myMetaKey && parent::get_slug($value10['value']) == $myMetaValue)
				{
					$state = 1;
					break;
				}
			}
			if($state == 0)
			{
				unset($data['myList'][$key]);
			}
			else // if it is a valid list, ...
			{
				$data['totalList']++;
				// get our last Modified !!
				if($value['Entry']['modified'] > $lastModified)
				{
					$data['lastModified'] = $value;
					$lastModified = $value['Entry']['modified'];
				}
			}
		}
		$data['countPage'] = ceil($data['totalList'] * 1.0 / $this->countListPerPage);
		return $data;
	}
	
	/**
	* querying to get specific entry from entry lists based on parameter given (core function) 
	* @param array $myType contains record query result of database type
	* @param array $myEntry contains record query result of the selected Entry
	* @param array $myParentEntry[optional] contains record query result of the parent Entry (used if want to search certain child Entry) 
	* @param string $myChildTypeSlug[optional] contains slug of child type database (used if want to search certain child Entry)
	* @return array $result a selected entry with all of its attributes you'd requested
	* @public
	**/
	function _admin_default_edit($myType = array() , $myEntry = array() , $myParentEntry = array() , $myChildTypeSlug = NULL)
	{					
		$data['myType'] = $myType;
		$data['myEntry'] = $myEntry;
		if(!empty($myParentEntry))
		{			
			$data['myParentEntry'] = $myParentEntry;
			$myChildType = $this->Type->findBySlug($myChildTypeSlug);
			$data['myChildType'] = $myChildType;
		}
		// for image input type reason...
		$data['myImageTypeList'] = $this->EntryMeta->embedded_img_meta('type');	
		
		// FINAL TOUCH !!
		$data['mySetting'] = $this->data['mySetting'];
		return $data;
	}
	// -------------------------------------------------------------------------------------------- //
	// ----------------------------- END OF DATABASE FUNCTION ------------------------------------- //
	// -------------------------------------------------------------------------------------------- //
}
?>

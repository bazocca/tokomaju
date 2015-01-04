<?php
class Entry extends AppModel {
	var $name = 'Entry';
	private $Resize=null;
	
	// DATABASE MODEL...
	var $Type = NULL;
	var $TypeMeta = NULL;
	var $Setting = NULL;
	var $EntryMeta = NULL;
	var $Account = NULL;
	
	var $validate = array(
		'entry_type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'slug' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			// 'isUnique' => array(
				// 'rule' => array('isUnique'),
				// 'message' => 'Invalid slug defined. Please contact your administrator for further solution.',
				// //'allowEmpty' => false,
				// //'required' => false,
				// //'last' => false, // Stop validation after this rule
				// //'on' => 'create', // Limit validation to 'create' or 'update' operations
			// ),
		),
		'main_image' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'parent_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'count' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'created_by' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'modified_by' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'sort_order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please complete all required fields.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ParentEntry' => array(
			'className' => 'Entry',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ParentImageEntry' => array(
			'className' => 'Entry',
			'foreignKey' => 'main_image',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AccountCreatedBy' => array(
			'className' => 'Account',
			'foreignKey' => 'created_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AccountModifiedBy' => array(
			'className' => 'Account',
			'foreignKey' => 'modified_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'ChildEntry' => array(
			'className' => 'Entry',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ChildMainEntry' => array(
			'className' => 'Entry',
			'foreignKey' => 'main_image',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'EntryMeta' => array(
			'className' => 'EntryMeta',
			'foreignKey' => 'entry_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'EntryMeta.id ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function __construct( $id = false, $table = NULL, $ds = NULL )
	{
		parent::__construct($id, $table, $ds);
		
		App::uses('JqImgcropComponent' , 'Controller/Component');
		$this->Resize = new JqImgcropComponent(new ComponentCollection()); //make instance
		
		// set needed database model ...
		$this->Type = ClassRegistry::init('Type');
		$this->TypeMeta = ClassRegistry::init('TypeMeta');
		$this->Setting = ClassRegistry::init('Setting');
		$this->EntryMeta = ClassRegistry::init('EntryMeta');
		$this->Account = ClassRegistry::init('Account');
	}
	
	/**
	 * to get a valid slug entry process
	 * @param string $slug contains source slug want to be processed
	 * @param integer $id contains id of the entry
	 * @return string $mySlug contains new generated slug.
	 * @public
	 **/
	function get_valid_slug($slug , $id = NULL)
	{	
		$counter = 0;
		$mySlug = $slug;
		if(!empty($id))
		{
			$options['conditions']['Entry.id <>'] = $id;
		}
		while(TRUE)
		{
			$options['conditions']['Entry.slug'] = $mySlug;
			$findSlug = $this->find('first' , $options);
			if(empty($findSlug))
			{
				break;
			}
			else
			{
				$mySlug = $slug.'-'.(++$counter);
			}
		}
		return $mySlug;
	}
	
	/**
	 * function that be executed before save an entry (automated by cakephp)
	 * @return boolean
	 * @public
	 **/
	function beforeSave()
	{
		if(!empty($this->data['Entry']['slug']))
		{
			$this->data['Entry']['slug'] = $this->get_valid_slug($this->data['Entry']['slug']);
		}
		return true;
	}
	
	function updateCountField($parent_id , $myChildTypeSlug , $deletemode = false)
	{
		if($parent_id > 0)
		{
			//--------------------------------- firstly create count-type in EntryMeta... ----------------------------- /////
			$totalchild = $this->find('count', array(
				'conditions' => array(
					'Entry.entry_type' => $myChildTypeSlug,
					'Entry.parent_id' => $parent_id
				)
			));
			if($deletemode)
			{
				$totalchild--;
			}
			
			$updateCountType = $this->EntryMeta->find('first' , array(
				'conditions' => array(
					'EntryMeta.entry_id' => $parent_id,
					'EntryMeta.key' => 'count-'.$myChildTypeSlug
				)
			));
			if(!empty($updateCountType))
			{
				$this->EntryMeta->id = $updateCountType['EntryMeta']['id'];
				$this->EntryMeta->saveField('value' , $totalchild);
			}
			else
			{
				if($totalchild > 0)
				{
					$input = array();
					$input['EntryMeta']['entry_id'] = $parent_id;
					$input['EntryMeta']['key'] = 'count-'.$myChildTypeSlug;
					$input['EntryMeta']['value'] = $totalchild;
					$this->EntryMeta->create();
					$this->EntryMeta->save($input);
				}
			}
			
			//------------------------------------ add COUNT to parent Entry -------------------------- /////
			$totalchild = $this->find('count', array(
				'conditions' => array(
					'Entry.parent_id' => $parent_id
				)
			));
			if($deletemode)
			{
				$totalchild--;
			}
			
			$tempid = $this->id;
			
			$this->id = $parent_id;
			$this->saveField('count' , $totalchild);
			
			$this->id = $tempid;
		}
	}
	
	/**
     * function that be executed after save an entry (automated by cakephp)
     * @return boolean
     * @public
     **/
    function afterSave()
    {   
        $temp = $this->field('sort_order');
        if($temp == 0)
        {
            $this->saveField('sort_order' , $this->id);
        }
        // lang_code update
        $temp = $this->field('lang_code');
        if(empty($temp))
        {
            $this->saveField('lang_code' , 'en-'.$this->id);
        }
        else if(strlen($temp) == 2)
        {
            $this->saveField('lang_code' , $temp.'-'.$this->id);
        }
		
		$this->updateCountField($this->field('parent_id') , $this->field('entry_type'));
    }
	
	/**
     * function that be executed before delete an entry (automated by cakephp)
     * @return boolean
     * @public
     **/
	function beforeDelete()
	{
		$this->updateCountField($this->field('parent_id') , $this->field('entry_type') , true);
		return true;
	}
	
	/**
	* delete selected media from database and dir img/upload
	* @param integer $id get media id
	* @return boolean
	* @public
	**/
	public function deleteMedia($id = NULL)
	{	
		if($id!=NULL)
		{
			$row=$this->findById($id);
			foreach ($row['EntryMeta'] as $key => $value) 
			{
				if($value['key'] == 'image_type')
				{
					$imageType = $value['value'];
					break;
				}
			}
			
			$file=sprintf('%s.%s',$row['Entry']['id'],$imageType);
			
			// Delete File from directory first
			$destDisplay=sprintf('%simg'.DS.'upload'.DS.'%s',WWW_ROOT,$file);
			$destThumb=sprintf('%simg'.DS.'upload'.DS.'thumb'.DS.'%s',WWW_ROOT,$file);
			$destThumbnails=sprintf('%simg'.DS.'upload'.DS.'thumbnails'.DS.'%s.%s',WWW_ROOT,$row['Entry']['title'],$imageType);
			
			// Delete file
			unlink($destThumb);
			unlink($destDisplay);
			unlink($destThumbnails);
			
			// special case deleter !!
			if(strtolower($imageType) == 'jpg' || strtolower($imageType) == 'jpeg')
			{
				unlink(sprintf('%simg'.DS.'upload'.DS.'thumbnails'.DS.'%s.jpg',WWW_ROOT,$row['Entry']['title']));
				unlink(sprintf('%simg'.DS.'upload'.DS.'thumbnails'.DS.'%s.jpeg',WWW_ROOT,$row['Entry']['title']));
			}
			
			$this->delete($id);
			$this->EntryMeta->deleteAll(array('EntryMeta.entry_id' => $id));
			return true;
		}		
		return false;
	}

	public function makeChildImageEntry($data = array() , $myType = array())
	{
		$parentImage = $this->findById($data['value']);
		foreach ($parentImage['EntryMeta'] as $key => $value) 
		{
			$parentImage['EntryMeta'][$value['key']] = $value['value'];
		}
		
		// set the type of this entry...
		$input['Entry']['entry_type'] = 'media';
		$input['Entry']['title'] = $parentImage['Entry']['title'];
		// generate slug from title...
		$input['Entry']['slug'] = $this->get_slug($input['Entry']['title']);
		// write my creator...
		$myCreator = $this->getCurrentUser();
		$input['Entry']['modified_by'] = $myCreator['id'];
		$input['Entry']['parent_id'] = $parentImage['Entry']['id'];
		if(empty($data['childImageId']))
		{
			$input['Entry']['created_by'] = $myCreator['id'];
			$this->create();
		}
		else
		{
			$this->id = $data['childImageId'];
			$this->EntryMeta->deleteAll(array('EntryMeta.entry_id' => $this->id));
		}
		$this->save($input);
		
		// save the image type...
		$myid = $this->id;
		$input['EntryMeta']['entry_id'] = $myid;
		$input['EntryMeta']['key'] = 'image_type';
		$input['EntryMeta']['value'] = $parentImage['EntryMeta']['image_type'];
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
		// save the image size...
		$input['EntryMeta']['key'] = 'image_size';
		$input['EntryMeta']['value'] = $this->createChildDisplay($parentImage['Entry']['id'] , $parentImage['EntryMeta']['image_type'] , $myid , $data['w'] , $data['h'] , $data['x1'] , $data['y1']);
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
		$myMediaSettings = $this->getMediaSettings($myType);
		$this->createChildThumb($myid , $parentImage['EntryMeta']['image_type'] , $myMediaSettings);
		
		// SAVE OTHER ATTRIBUTE OF NEW CROPPED IMAGES !!
		if(is_numeric($data['x1']) && is_numeric($data['y1']) && is_numeric($data['w']) && is_numeric($data['h']))
		{
			$input['EntryMeta']['key'] = 'image_x';
			$input['EntryMeta']['value'] = $data['x1'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			$input['EntryMeta']['key'] = 'image_y';
			$input['EntryMeta']['value'] = $data['y1'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			$input['EntryMeta']['key'] = 'image_width';
			$input['EntryMeta']['value'] = $data['w'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			$input['EntryMeta']['key'] = 'image_height';
			$input['EntryMeta']['value'] = $data['h'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
		}
		return $myid;
	}

	/**
	 * create a thumbnail image
	 * @param integer $myid contains id of the image entry
	 * @param string $mytype contains extension type of the image (like .jpg, .png, etc)
	 * @param string $myMediaSettings contains array of media settings want to be used
	 * @return true
	 * @public
	 **/
	public function createThumb($myid , $mytype , $myMediaSettings)
	{		
		$src = WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$myid.'.'.$mytype;
		$dest = WWW_ROOT.'img'.DS.'upload'.DS.'thumb'.DS.$myid.'.'.$mytype;
		return $this->Resize->thumb_resize($src, $dest, $myMediaSettings['thumb_width'], $myMediaSettings['thumb_height'] , $myMediaSettings['thumb_crop']);
	}

	public function createChildThumb($myid , $mytype , $myMediaSettings)
	{		
		$src = WWW_ROOT.'img'.DS.'upload'.DS.$myid.'.'.$mytype;
		$dest = WWW_ROOT.'img'.DS.'upload'.DS.'thumb'.DS.$myid.'.'.$mytype;
		return $this->Resize->thumb_resize($src, $dest, $myMediaSettings['thumb_width'], $myMediaSettings['thumb_height'] , $myMediaSettings['thumb_crop']);
	}

	/**
	 * create a display image
	 * @param integer $myid contains id of the image entry
	 * @param string $mytype contains extension type of the image (like .jpg, .png, etc)
	 * @param string $myMediaSettings contains array of media settings want to be used
	 * @return true
	 * @public
	 **/
	public function createDisplay($myid , $mytype , $myMediaSettings)
	{	
		$src = WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$myid.'.'.$mytype;
		$dest = WWW_ROOT.'img'.DS.'upload'.DS.$myid.'.'.$mytype;
		return $this->Resize->image_resize($src, $dest, $myMediaSettings['display_width'], $myMediaSettings['display_height'] , $myMediaSettings['display_crop']);
	}
	
	public function createChildDisplay($myid , $mytype , $myChildId , $width , $height , $x , $y)
	{	
		$src = WWW_ROOT.'img'.DS.'upload'.DS.$myid.'.'.$mytype;
		$dest = WWW_ROOT.'img'.DS.'upload'.DS.$myChildId.'.'.$mytype;
		return $this->Resize->image_resize($src, $dest, $width, $height , 2 , $x , $y);
	}
	
	/**
	 * retrieve media settings from certain type meta, if doesn't exist, then retrieve from global settings.
	 * @param array $myType contains query data from selected entry type
	 * @return array $result contains all the media settings will be used
	 * @public
	 **/
	public function getMediaSettings($myType = array())
	{
		// choose from settings first...
		$result = $this->Setting->get_settings();
		// if in type meta exist, use that :D
		$myTypeMeta = $this->TypeMeta->findAllByTypeId($myType['Type']['id']);
		foreach ($myTypeMeta as $key => $value) 
		{
			if($value['TypeMeta']['key'] == 'display_width')
			{
				$result['display_width'] = $value['TypeMeta']['value'];
			}
			else if($value['TypeMeta']['key'] == 'display_height')
			{
				$result['display_height'] = $value['TypeMeta']['value'];
			}
			else if($value['TypeMeta']['key'] == 'display_crop')
			{
				$result['display_crop'] = ($value['TypeMeta']['value']==2?0:$value['TypeMeta']['value']);
			}
			else if($value['TypeMeta']['key'] == 'thumb_width')
			{
				$result['thumb_width'] = $value['TypeMeta']['value'];
			}
			else if($value['TypeMeta']['key'] == 'thumb_height')
			{
				$result['thumb_height'] = $value['TypeMeta']['value'];
			}
			else if($value['TypeMeta']['key'] == 'thumb_crop')
			{
				$result['thumb_crop'] = $value['TypeMeta']['value'];
			}
		}
		return $result;
	}

	function get_lang_url()
	{
		// -------------- LANGUAGE URL POSITION ----------------------------- //
		$lang_pos = '';
		$indentation = '';
		if(isLocalhost())
		{
			$lang_pos = 2;
			$indentation = $lang_pos - 1;
		}
		else
		{
			$lang_pos = 1;
			$indentation = $lang_pos;
		}		
		// ----------- END OF LANGUAGE URL POSITION ------------------------- //
		$domain_lang = strtolower(substr($_SERVER['SERVER_NAME'], 0,2));
		$mySetting = $this->Setting->get_settings();
		
		foreach ($mySetting['language'] as $key => $value) 
		{
			if($domain_lang == strtolower(substr($value, 0,2)))
			{
				$result['language'] = $domain_lang;
				$result['indent'] = 0;
				return $result;
			}
		}
		// NOW FOR SECOND CHECK !!
		$url_set = explode('/', strtolower($_SERVER['REQUEST_URI']));
		foreach ($mySetting['language'] as $key => $value) 
		{
			if($url_set[$lang_pos] == strtolower(substr($value, 0,2)))
			{	
				$result['language'] = $url_set[$lang_pos];
				$result['indent'] = $indentation;
				return $result;
			}
		}
		$result['language'] = strtolower(substr($mySetting['language'][0], 0,2));
		$result['indent'] = 0;
		return $result;
	}

	// imported from GET Helpers !!
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

		return (empty($options)?false:breakEntryMetas($this->find('first',$options)));
	}
	
	// ---------------------------------------------- >>
	// for batch upload image in media library !!
	// ---------------------------------------------- >>
	public function autoUploadImage($filename , $myTypeSlug = 'products')
	{
		$default_path = WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$filename;
		
		$path_parts = pathinfo($filename);
		
		if(!file_exists($default_path))
		{
			$search_image = $this->find('first', array(
				'conditions' => array(
					'Entry.entry_type' => 'media',
					'Entry.title' => $path_parts['filename']
				)
			));
			
			if(empty($search_image))
			{
				return 0;
			}
			else
			{
				return $search_image['Entry']['id'];
			}
		}
		
		$input = array();
		$input['Entry']['entry_type'] = 'media';
		$input['Entry']['title'] = $path_parts['filename'];
		// generate slug from title...
		$input['Entry']['slug'] = get_slug($input['Entry']['title']);
		// write my creator...
		$input['Entry']['created_by'] = 1;
		$input['Entry']['modified_by'] = 1;
		$this->create();
		$this->save($input);
		
		$myid = $this->id;
		$mytype = $path_parts['extension'];
		// rename the filename...
		rename( $default_path , WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$myid.'.'.$mytype);
		
		// now generate for display and thumb image according to the media settings...
		$myType = $this->Type->findBySlug($myTypeSlug);
		$myMediaSettings = $this->getMediaSettings($myType);
		
		// save the image type...
		$input = array();		
		$input['EntryMeta']['entry_id'] = $myid;
		$input['EntryMeta']['key'] = 'image_type';
		$input['EntryMeta']['value'] = $mytype;
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		// save the image size...
		$input['EntryMeta']['key'] = 'image_size';
		$input['EntryMeta']['value'] = $this->createDisplay($myid , $mytype , $myMediaSettings);
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
		//Resize original file for thumb...
		$this->createThumb($myid , $mytype , $myMediaSettings);
		
		// REMOVE ORIGINAL IMAGE FILE !!
		unlink(WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$myid.'.'.$mytype);
		
		return $myid;
	}
	
	public function billFormatNumber($entry_type , $limit = 4)
    {
        // for trans ID, minimum of x digits...
        $bill_sample = date('ym' , gmt_adjustment()).'.';
		
        $trans = $this->find('first' , array(
            'conditions' => array(
                'Entry.entry_type' => $entry_type,
                'Entry.title LIKE' => $bill_sample.'%'
            ),
            'order' => array('Entry.id DESC')
        ));
        
        $result = '';
        $index = 1;
        if(!empty($trans))
        {
            $pecah = explode('.', $trans['Entry']['title']);
            $index = $pecah[count($pecah) - 1] + 1;
        }
        
        $result = $bill_sample.sprintf('%0'.$limit.'d' , $index);
        return $result;
    }

	/*
	Check if this entry data has already have all of language version or not.
	- return true if there is still exist language that has not been translated yet, otherwise false.
	*/
	function checkRemainingLang($id , $mySetting)
	{
		$entry = $this->findById($id);
		$langcode = explode('-', $entry['Entry']['lang_code']);

		if(!empty($langcode[1]) && is_numeric($langcode[1]))
		{
			$temp = $this->find('count' , array(
				'conditions' => array(
					'Entry.lang_code LIKE' => '%-'.$langcode[1]
				)
			));

			$countLang = count($mySetting['language']);

			if($temp < $countLang)
			{
				return $entry['Entry']['slug'];
			}
		}
		
		return false;
	}
    
    // ------------------------- >>
    // Inventory Function !!
    // ------------------------- >>
    function ajax_title_counter($myTypeSlug , $frontTitle)
	{	
		$options['conditions']['Entry.entry_type'] = $myTypeSlug;		
		$options['conditions']['Entry.title LIKE'] = $frontTitle.'%';		
		$options['order'] = 'Entry.id DESC';
		
		$temp = $this->find('first',$options);
		if(empty($temp))
		{
			return '001';
		}

		$code = '1'.substr($temp['Entry']['title'], (is_numeric(substr($temp['Entry']['title'], 3))?9:6));
		$code++;
		
		$temp2 = substr($code, 0,1);
		$temp2--;
		
		return ($temp2 == 0?substr($code, 1): '1'.substr($code, 1) );
	}
    
    public function addSaleDetails($data = array())
	{		
		$diskon_nota = (empty($data['EntryMeta']['diskon_nota'])?0:$data['EntryMeta']['diskon_nota']);
        $uang_muka = (empty($data['EntryMeta']['uang_muka']) || $data['EntryMeta']['status_bayar'] == "Lunas" ?0:$data['EntryMeta']['uang_muka']);
        $ongkos_tambahan = (empty($data['EntryMeta']['ongkos_tambahan'])?0:$data['EntryMeta']['ongkos_tambahan']);
        
		$grandprofit = 0;
		$grandtotal = -$diskon_nota;
		$myCreator = $this->getCurrentUser();
		
        $nowDate = getdate();
        $year = $nowDate['year'];
        $month = sprintf("%02d",$nowDate['mon']);
        $day = sprintf("%02d",$nowDate['mday']);
        
        $frontTitle = getFrontCodeId('piutang').substr($year, 2).$month.$day;
		
		foreach ($data['barang']['id'] as $key => $value)
		{
            $detailbarang = $this->meta_details(NULL , 'barang-dagang' , NULL , NULL , NULL , NULL , $value); // using title instead !!
			$input = array();
			$input['Entry']['title'] = $detailbarang['Entry']['slug']; // save slug as title !!
			$input['Entry']['entry_type'] = "sales-detail";
			$input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['parent_id'] = $data['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
			$input['EntryMeta']['key'] = "form-jumlah";
			$input['EntryMeta']['value'] = $data['barang']['jumlah'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-harga";
			$input['EntryMeta']['value'] = $data['barang']['harga'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-diskon";
			$input['EntryMeta']['value'] = $data['barang']['diskon'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
            
            $input['EntryMeta']['key'] = "form-profit";
			$input['EntryMeta']['value'] = $data['barang']['profit'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-terkirim";
			$input['EntryMeta']['value'] = 0;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-retur";
			$input['EntryMeta']['value'] = 0;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			// proses another entry, related to this ^^
			$subtotal = $data['barang']['jumlah'][$key] * $data['barang']['harga'][$key] - $data['barang']['diskon'][$key];
			$grandtotal += $subtotal;
			$grandprofit += $data['barang']['profit'][$key];
			// --------------------------------------------------------- //
			// AUTO ADD FOR piutang !!
			// --------------------------------------------------------- //
			$input = array();			
            $input['Entry']['entry_type'] = "piutang";
			$input['Entry']['title'] = $frontTitle.$this->ajax_title_counter($input['Entry']['entry_type'], $frontTitle);
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$input['Entry']['description'] = "Jual <strong>".$detailbarang['Entry']['title']."</strong> sebanyak ".$data['barang']['jumlah'][$key]." ".$detailbarang['EntryMeta']['satuan']." @Rp.".str_replace(',', '.', toMoney( $data['barang']['harga'][$key] , true , true) ).",-".(empty($data['barang']['diskon'][$key])?"":" dengan total diskon Rp.".str_replace(',', '.', toMoney( $data['barang']['diskon'][$key] , true , true) ).",-");			
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['parent_id'] = $data['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $month."/".$day."/".$year;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-mutasi_debet";
			$input['EntryMeta']['value'] = $subtotal;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
		}
        
		// UPDATE BALANCE !!
		$balance = $grandtotal - $uang_muka;
		$input = array();
		$input['Entry']['entry_type'] = "piutang";
		$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
		$input['Entry']['parent_id'] = $data['Entry']['id'];
		if(!empty($diskon_nota))
		{			
			$input['Entry']['title'] = $frontTitle.$this->ajax_title_counter($input['Entry']['entry_type'] , $frontTitle);
			$input['Entry']['description'] = "Mendapat potongan diskon nota secara keseluruhan.";
			$input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $month."/".$day."/".$year;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-mutasi_kredit";
			$input['EntryMeta']['value'] = $diskon_nota;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
		}		
		// cek status pembayaran apakah sudah langsung lunas ato belum :D
		if($data['EntryMeta']['status_bayar'] == "Lunas")
		{			
			$input['Entry']['title'] = $frontTitle.$this->ajax_title_counter($input['Entry']['entry_type'], $frontTitle);
			$input['Entry']['description'] = "Pembayaran sudah lunas.";
			$input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $month."/".$day."/".$year;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-mutasi_kredit";
			$input['EntryMeta']['value'] = $grandtotal;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			// update balance !!
			$balance = 0;
		}
		else if(!empty($uang_muka)) // jika bayar msh tunggak, maka uang muka dapat diperhitungkan!
		{
			$input['Entry']['title'] = $frontTitle.$this->ajax_title_counter($input['Entry']['entry_type'] , $frontTitle);
			$input['Entry']['description'] = "Pembayaran Uang Muka / Uang DP.";
			$input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $month."/".$day."/".$year;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-mutasi_kredit";
			$input['EntryMeta']['value'] = $uang_muka;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
		}
		
		$input = array();
		$input['EntryMeta']['entry_id'] = $data['Entry']['id'];
		$input['EntryMeta']['key'] = "form-balance"; // tidak terdaftar di TypeMeta !!
		$input['EntryMeta']['value'] = $balance; // asumsinya sama dengan "sisa pembayaran"
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
		$input['EntryMeta']['key'] = "form-total_harga";
		$input['EntryMeta']['value'] = $grandtotal;
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
		$input['EntryMeta']['key'] = "form-laba_bersih";
		$input['EntryMeta']['value'] = $grandprofit - $diskon_nota - $ongkos_tambahan;
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
	}
    
    public function addPurchaseDetails($data = array())
	{
        $balance = 0;
        $myCreator = $this->getCurrentUser();
        $nowDate = getdate();
        $year = $nowDate['year'];
        $month = sprintf("%02d",$nowDate['mon']);
        $day = sprintf("%02d",$nowDate['mday']);
		
		foreach ($data['barang']['id'] as $key => $value) 
		{
			$detailbarang = $this->meta_details(NULL , 'barang-dagang' , NULL , NULL , NULL , NULL , $value); // using title instead !!
			$input = array();
			$input['Entry']['title'] = $detailbarang['Entry']['slug']; // save slug as title !!
			$input['Entry']['entry_type'] = "purchase-detail";
			$input['Entry']['slug'] = get_slug($input['Entry']['title']);
            $input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['parent_id'] = $data['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
            
			$input['EntryMeta']['key'] = "form-jumlah";
			$input['EntryMeta']['value'] = $data['barang']['jumlah'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-harga";
			$input['EntryMeta']['value'] = $data['barang']['harga'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-terkirim";
			$input['EntryMeta']['value'] = 0;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-retur";
			$input['EntryMeta']['value'] = 0;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			// proses another entry, related to this ^^
			$subtotal = $data['barang']['jumlah'][$key] * $data['barang']['harga'][$key];
			$balance += $subtotal;
			
			// ------------------------------------------------------ //
			// AUTO ADD FOR hutang !!
			// ------------------------------------------------------ //
			$input = array();
            $input['Entry']['entry_type'] = "hutang";
            
			$frontTitle = getFrontCodeId($input['Entry']['entry_type']).substr($year, 2).$month.$day;
			$input['Entry']['title'] = $frontTitle.$this->ajax_title_counter($input['Entry']['entry_type'], $frontTitle);
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);
            
			$input['Entry']['description'] = "Beli <strong>".$detailbarang['Entry']['title']."</strong> sebanyak ".$data['barang']['jumlah'][$key]." ".$detailbarang['EntryMeta']['satuan']." @Rp.".str_replace(',', '.', toMoney( $data['barang']['harga'][$key] , true , true) ).",-";			
			
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['parent_id'] = $data['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $month."/".$day."/".$year;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-mutasi_kredit";
			$input['EntryMeta']['value'] = $subtotal;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
		}

		$grandtotal = $balance;
		// cek status pembayaran apakah sudah langsung lunas ato belum :D        
		if($data['EntryMeta']['status_bayar'] == "Lunas")
		{
			$input = array();
            $input['Entry']['entry_type'] = "hutang";
            
			$frontTitle = getFrontCodeId($input['Entry']['entry_type']).substr($year, 2).$month.$day;
			$input['Entry']['title'] = $frontTitle.$this->ajax_title_counter($input['Entry']['entry_type'] , $frontTitle);
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);            
			$input['Entry']['description'] = "Pembayaran ".$data['Entry']['title']." telah lunas.";
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['parent_id'] = $data['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$input['EntryMeta']['entry_id'] = $this->id;
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $month."/".$day."/".$year;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-mutasi_debet";
			$input['EntryMeta']['value'] = $balance;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			// update balance !!
			$balance = 0;
		}
		
		$input = array();
		$input['EntryMeta']['entry_id'] = $data['Entry']['id'];
		$input['EntryMeta']['key'] = "form-balance"; // tidak terdaftar di TypeMeta !!
		$input['EntryMeta']['value'] = $balance;
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
		$input['EntryMeta']['key'] = "form-total_harga";
		$input['EntryMeta']['value'] = $grandtotal;
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
	}	

	public function addSuratJalan($data = array())
	{
		$myCreator = $this->getCurrentUser();
		$explodeDate = explode("/", $data['EntryMeta']['tanggal']);
        $nota = $this->meta_details(!empty($data['EntryMeta']['sales_order'])?$data['EntryMeta']['sales_order']:$data['EntryMeta']['purchase_order']);
        
		// execute the goods !!
		foreach ($data['barang']['slug-barang'] as $key => $value) 
		{
			$input = array();
            $input['Entry']['entry_type'] = "barang-surat-jalan";
			$input['Entry']['title'] = $value;
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);            
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];            
			$input['Entry']['parent_id'] = $data['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$sjDetailsId = $this->id;
			$input['EntryMeta']['entry_id'] = $sjDetailsId;
			$input['EntryMeta']['key'] = "form-jumlah";
			$input['EntryMeta']['value'] = $data['barang']['jumlah'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-gudang";
			$input['EntryMeta']['value'] = $data['barang']['slug-gudang'][$key];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			// proses another entry, related to this ^^
			// -------------------------------------------------------------------------------- //
			// UPDATE STOCK GUDANGGGGGG !!!!!!!!!!!!!!!
			// -------------------------------------------------------------------------------- //
			$gudangku = $this->meta_details($data['barang']['slug-gudang'][$key] , "gudang");
			$cari_barang = $this->find('first' , array(
				"conditions" => array(
					"Entry.parent_id" => $gudangku['Entry']['id'],
					"Entry.title" => $value,
					"Entry.entry_type" => "barang-gudang" 
				)
			));
			$cari_stok = $this->EntryMeta->find('first' , array(
				"conditions" => array(
					"EntryMeta.entry_id" => $cari_barang['Entry']['id'],
					"EntryMeta.key" => "form-stock"
				)
			));
			$this->EntryMeta->id = $cari_stok['EntryMeta']['id'];
			$this->EntryMeta->saveField('value' , $cari_stok['EntryMeta']['value'] - $data['barang']['jumlah'][$key]);
			
			// --------------------------------------------------------------------------------------- //
			// 				PINDAH KELUAR !!!!
			$input = array();
			$input['Entry']['entry_type'] = "pindah-keluar";
            $input['Entry']['title'] = (!empty($data['EntryMeta']['customer'])?$data['EntryMeta']['customer']:$data['EntryMeta']['supplier']).'_'.$value;
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$input['Entry']['description'] = "Pengiriman Surat Jalan <a target='_blank' href='".$data['imagePath']."admin/entries/".$data['Entry']['entry_type']."/edit/".$data['Entry']['slug']."'>".$data['Entry']['title']."</a>";
            
            // catat jumlah barang yg dikirim di field main_image (custom case)...
			$input['Entry']['main_image'] = $data['barang']['jumlah'][$key];
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['created'] = $input['Entry']['modified'] = $explodeDate[2]."-".$explodeDate[0]."-".$explodeDate[1];
			$input['Entry']['parent_id'] = $gudangku['Entry']['id'];
			$this->create();
			$this->save($input);
			// minus stock di master barang...
			$this->EntryMeta->add_stock_master_barang($value , -$data['barang']['jumlah'][$key]);
			
			// update barang yg sudah terkirim brp biji (di sale / purchase details)!!
			if(!empty($data['EntryMeta']['sales_order']))
			{				
				$updateSalDetails = $this->find('first' , array(
					"conditions" => array(
						"Entry.parent_id" => $nota['Entry']['id'],
						"Entry.title" => $value,
						"Entry.entry_type" => "sales-detail"
					)
				)); 
				$delivered = $this->EntryMeta->find("first", array(
					"conditions" => array(
						"EntryMeta.entry_id" => $updateSalDetails['Entry']['id'],
						"EntryMeta.key" => "form-terkirim"
					)
				));				
				$this->EntryMeta->id = $delivered['EntryMeta']['id'];
				$this->EntryMeta->saveField("value" , $delivered['EntryMeta']['value'] + $data['barang']['jumlah'][$key]);		
			}
			else if(!empty($data['EntryMeta']['purchase_order']))
			{
				$updatePurDetails = $this->find('first' , array(
					"conditions" => array(
						"Entry.parent_id" => $nota['Entry']['id'],
						"Entry.title" => $value,
						"Entry.entry_type" => "purchase-detail"
					)
				));
				$retur = $this->EntryMeta->find("first", array(
					"conditions" => array(
						"EntryMeta.entry_id" => $updatePurDetails['Entry']['id'],
						"EntryMeta.key" => "form-retur"
					)
				));	
				$this->EntryMeta->id = $retur['EntryMeta']['id'];
				$this->EntryMeta->saveField("value" , $retur['EntryMeta']['value'] + $data['barang']['jumlah'][$key]);
			}
		}

		// untuk tahap terakhir, CHECK apakah pengiriman barangnya sudah lunas semua ato belum !!
		if(!empty($data['EntryMeta']['sales_order']))
		{	
			$myList = $this->find("all" , array(
				"conditions" => array(
					"Entry.parent_id" => $nota['Entry']['id'],
					"Entry.entry_type" => "sales-detail"
				)
			));
			$fullDelivered = 1;
			foreach ($myList as $key => $value) 
			{
				$salDetails = $this->meta_details($value['Entry']['slug'] , "sales-detail" , $nota['Entry']['id']);
				if($salDetails['EntryMeta']['terkirim'] < $salDetails['EntryMeta']['jumlah'])
				{
					$fullDelivered = 0;
					break;
				}
			}
			if($fullDelivered == 1)
			{
				$updateDeliveryStatus = $this->EntryMeta->find("first", array(
					"conditions" => array(
						"EntryMeta.entry_id" => $nota['Entry']['id'],
						"EntryMeta.key" => "form-status_kirim"
					)
				));
				$this->EntryMeta->id = $updateDeliveryStatus['EntryMeta']['id'];
				$this->EntryMeta->saveField("value" , "Terkirim");
			}
		}
	}

	public function addReturJual($data = array() , $myEntry = array())
	{
        $myCreator = $this->getCurrentUser();
        $data = breakEntryMetas($data);
		$explodeDate = explode("/", $data['EntryMeta']['tanggal']);
		$saleHeader = $this->meta_details($myEntry['Entry']['slug'],"sales-order");
		$gudangku = $this->meta_details($data['EntryMeta']['gudang'] , "gudang");
		
		foreach ($data['barang'] as $key => $value) 
		{
			if(empty($value['jumlah']))
			{
				continue;
			}
            
			$input = array();
            $input['Entry']['entry_type'] = "retur-jual";
			$input['Entry']['title'] = $key;
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$input['Entry']['description'] = $value['keterangan'];
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['parent_id'] = $myEntry['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$barangMasukBaruId = $this->id;
			$input['EntryMeta']['entry_id'] = $barangMasukBaruId;
			$input['EntryMeta']['key'] = "form-jumlah";
			$input['EntryMeta']['value'] = $value['jumlah'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $data['EntryMeta']['tanggal'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-gudang";
			$input['EntryMeta']['value'] = $data['EntryMeta']['gudang'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			// proses another entry, related to this ^^
			$updateSaleDetails = $this->find('first' , array(
				"conditions" => array(
					"Entry.parent_id" => $myEntry['Entry']['id'],
					"Entry.title" => $key,
					"Entry.entry_type" => "sales-detail"
				)
			)); 
            
			$retur = $this->EntryMeta->find("first", array(
				"conditions" => array(
					"EntryMeta.entry_id" => $updateSaleDetails['Entry']['id'],
					"EntryMeta.key" => "form-retur"
				)
			));
			$this->EntryMeta->id = $retur['EntryMeta']['id'];
			$this->EntryMeta->saveField("value" , $retur['EntryMeta']['value'] + $value['jumlah']);
			// -------------------------------------------------------------------------------- //
			// UPDATE STOCK GUDANGGGGGG !!!!!!!!!!!!!!!
			// -------------------------------------------------------------------------------- //
			$cari_barang = $this->find('first' , array(
				"conditions" => array(
					"Entry.parent_id" => $gudangku['Entry']['id'],
					"Entry.title" => $key,
					"Entry.entry_type" => "barang-gudang" 
				)
			));
			
			if(empty($cari_barang)) // BUAT BARU !!
			{
				$input = array();
				$input['Entry']['title'] = $key;
				$input['Entry']['description'] = "Retur dari customer.";
				$input['Entry']['entry_type'] = "barang-gudang";
				$input['Entry']['slug'] = get_slug($input['Entry']['title']);
				$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
				$input['Entry']['parent_id'] = $gudangku['Entry']['id'];
				$this->create();
				$this->save($input);
				
				// add jumlah stok
				$this->data['EntryMeta']['entry_id'] = $this->id;
				$this->data['EntryMeta']['key'] = 'form-stock';
				$this->data['EntryMeta']['value'] = $value['jumlah'];
				$this->EntryMeta->create();
				$this->EntryMeta->save($this->data);
			}
			else // UPDATE TAMBAH STOCK BARANG !!
			{
                $this->EntryMeta->id = $cari_barang['EntryMeta'][0]['id'];
				$this->EntryMeta->saveField('value' , $cari_barang['EntryMeta'][0]['value'] + $value['jumlah']);
			}
			// --------------------------------------------------------------------------------------- //
			// 				PINDAH MASUK !!!!
            $input = array();
            $input['Entry']['entry_type'] = "pindah-masuk";
			$input['Entry']['title'] = $saleHeader['EntryMeta']['customer'].'_'.$key;
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);
            $input['Entry']['description'] = "Retur Penjualan INVOICE kode <a target='_blank' href='".$data['imagePath']."admin/entries/".$myEntry['Entry']['entry_type']."/edit/".$myEntry['Entry']['slug']."'>".$myEntry['Entry']['title']."</a>";
			$input['Entry']['main_image'] = $value['jumlah'];
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['created'] = $input['Entry']['modified'] = $explodeDate[2]."-".$explodeDate[0]."-".$explodeDate[1];            
			$input['Entry']['parent_id'] = $gudangku['Entry']['id'];
			$this->create();
			$this->save($input);
			// tambah stock di master barang...
			$this->EntryMeta->add_stock_master_barang($key , +$value['jumlah']);
		}
        
		return $gudangku['Entry']['title'];
        // END OF FUNCTION !!        
	}

	public function addBarangMasuk($data = array() , $myEntry = array())
	{	
		$myCreator = $this->getCurrentUser();
        $data = breakEntryMetas($data);
		$explodeDate = explode("/", $data['EntryMeta']['tanggal']);
		$purHeader = $this->meta_details($myEntry['Entry']['slug'],"purchase-order");
		$gudangku = $this->meta_details($data['EntryMeta']['gudang'] , "gudang");
		
		foreach ($data['barang'] as $key => $value) 
		{
			if(empty($value['jumlah']))
			{
				continue;
			}
			$input = array();
            $input['Entry']['entry_type'] = "barang-masuk";
			$input['Entry']['title'] = $key;
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);
			$input['Entry']['description'] = $value['keterangan'];
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['parent_id'] = $myEntry['Entry']['id'];
			$this->create();
			$this->save($input);
			
			$barangMasukBaruId = $this->id;
			$input['EntryMeta']['entry_id'] = $barangMasukBaruId;
			$input['EntryMeta']['key'] = "form-jumlah_datang";
			$input['EntryMeta']['value'] = $value['jumlah'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-tanggal";
			$input['EntryMeta']['value'] = $data['EntryMeta']['tanggal'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			$input['EntryMeta']['key'] = "form-gudang";
			$input['EntryMeta']['value'] = $data['EntryMeta']['gudang'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
			// proses another entry, related to this ^^
			// update barang yg sudah terkirim brp biji (di purchase details)!!
			$updatePurDetails = $this->find('first' , array(
				"conditions" => array(
					"Entry.parent_id" => $myEntry['Entry']['id'],
					"Entry.title" => $key,
					"Entry.entry_type" => "purchase-detail"
				)
			)); 
			$delivered = $this->EntryMeta->find("first", array(
				"conditions" => array(
					"EntryMeta.entry_id" => $updatePurDetails['Entry']['id'],
					"EntryMeta.key" => "form-terkirim"
				)
			));
			$terkirim = $delivered['EntryMeta']['value'] + $value['jumlah'];
			$this->EntryMeta->id = $delivered['EntryMeta']['id'];
			$this->EntryMeta->saveField("value" , $terkirim);		
				
			// buat EntryMeta "sisa" di barang-masuk entry
			$pesanan = $this->EntryMeta->find("first", array(
				"conditions" => array(
					"EntryMeta.entry_id" => $updatePurDetails['Entry']['id'],
					"EntryMeta.key" => "form-jumlah"
				)
			));
			$input['EntryMeta']['entry_id'] = $barangMasukBaruId;
			$input['EntryMeta']['key'] = "form-sisa";
			$input['EntryMeta']['value'] = $pesanan['EntryMeta']['value'] - $terkirim;
			$this->EntryMeta->create();
			$this->EntryMeta->save($input);
			
            // -------------------------------------------------------------------------------- //
			// UPDATE STOCK GUDANGGGGGG !!!!!!!!!!!!!!!
			// -------------------------------------------------------------------------------- //
			$cari_barang = $this->find('first' , array(
				"conditions" => array(
					"Entry.parent_id" => $gudangku['Entry']['id'],
					"Entry.title" => $key,
					"Entry.entry_type" => "barang-gudang" 
				)
			));
			
			if(empty($cari_barang)) // BUAT BARU !!
			{
				$input = array();
				$input['Entry']['title'] = $key;
				$input['Entry']['description'] = "Kiriman dari supplier.";
				$input['Entry']['entry_type'] = "barang-gudang";
				$input['Entry']['slug'] = get_slug($input['Entry']['title']);
				$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
				$input['Entry']['parent_id'] = $gudangku['Entry']['id'];
				$this->create();
				$this->save($input);
				
				// add jumlah stok
				$this->data['EntryMeta']['entry_id'] = $this->id;
				$this->data['EntryMeta']['key'] = 'form-stock';
				$this->data['EntryMeta']['value'] = $value['jumlah'];
				$this->EntryMeta->create();
				$this->EntryMeta->save($this->data);
			}
			else // UPDATE TAMBAH STOCK BARANG !!
			{
                $this->EntryMeta->id = $cari_barang['EntryMeta'][0]['id'];
				$this->EntryMeta->saveField('value' , $cari_barang['EntryMeta'][0]['value'] + $value['jumlah']);
			}
			// --------------------------------------------------------------------------------------- //
			// 				PINDAH MASUK !!!!
			$input = array();
            $input['Entry']['entry_type'] = "pindah-masuk";
			$input['Entry']['title'] = $purHeader['EntryMeta']['supplier'].'_'.$key;
            $input['Entry']['slug'] = get_slug($input['Entry']['title']);
            $input['Entry']['description'] = "Pembelian INVOICE kode <a target='_blank' href='".$data['imagePath']."admin/entries/".$myEntry['Entry']['entry_type']."/edit/".$myEntry['Entry']['slug']."'>".$myEntry['Entry']['title']."</a>";
			$input['Entry']['main_image'] = $value['jumlah'];
			$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $myCreator['id'];
			$input['Entry']['created'] = $input['Entry']['modified'] = $explodeDate[2]."-".$explodeDate[0]."-".$explodeDate[1];            
			$input['Entry']['parent_id'] = $gudangku['Entry']['id'];
			$this->create();
			$this->save($input);
			// tambah stock di master barang...
			$this->EntryMeta->add_stock_master_barang($key , +$value['jumlah']);
		}

		// untuk tahap terakhir, CHECK apakah pengiriman barangnya sudah selesai semua ato belum !!
		$myList = $this->find("all" , array(
			"conditions" => array(
				"Entry.parent_id" => $myEntry['Entry']['id'],
				"Entry.entry_type" => "purchase-detail"
			)
		));
		$fullDelivered = 1;
		foreach ($myList as $key => $value) 
		{
			$purDetails = $this->meta_details($value['Entry']['slug'] , "purchase-detail" , $myEntry['Entry']['id']);
			if($purDetails['EntryMeta']['terkirim'] < $purDetails['EntryMeta']['jumlah'])
			{
				$fullDelivered = 0;
				break;
			}
		}
		if($fullDelivered == 1)
		{
			$updateDeliveryStatus = $this->EntryMeta->find("first", array(
				"conditions" => array(
					"EntryMeta.entry_id" => $myEntry['Entry']['id'],
					"EntryMeta.key" => "form-status_kirim"
				)
			));
			$this->EntryMeta->id = $updateDeliveryStatus['EntryMeta']['id'];
			$this->EntryMeta->saveField("value" , "Terkirim");
		}
        
        return $gudangku['Entry']['title'];
        // END OF FUNCTION !!        
	}
}

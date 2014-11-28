<?php
class EntryMeta extends AppModel {
	var $name = 'EntryMeta';
	var $validate = array(
		'entry_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Entry' => array(
			'className' => 'Entry',
			'foreignKey' => 'entry_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	// DATABASE MODEL...
	var $Type = NULL;
	var $TypeMeta = NULL;
	var $Entry = NULL;
	
	public function __construct( $id = false, $table = NULL, $ds = NULL )
	{
		parent::__construct($id, $table, $ds);
		
		// set needed database model ...
		$this->Type = ClassRegistry::init('Type');
		$this->TypeMeta = ClassRegistry::init('TypeMeta');
		$this->Entry = ClassRegistry::init('Entry');
	}
	
	/**
	 * retrieve all image types in one indexing array based on that image id as selector
	 * @param string $type contain type attribute of the image (default is image type)
	 * @return array $imgTypeList contains array of image type lists
	 * @public
	 **/
	function embedded_img_meta($type)
	{
		$imgReason = $this->find('all', array(
			'conditions' => array(
				'EntryMeta.key' => 'image_'.$type
			)
		));
		$imgTypeList[0] = 'jpg';
		foreach ($imgReason as $key20 => $value20)
		{
			$imgTypeList[$value20['EntryMeta']['entry_id']] = $value20['EntryMeta']['value'];			
		}
		return $imgTypeList;
	}
	
	// Delete files in EntryMeta when a data is to be deleted !!
	function remove_files($myType , $myEntry)
	{
		$haystack = array();
		foreach ($myType['TypeMeta'] as $key => $value) 
		{
			if($value['input_type'] == 'file')
			{
				array_push($haystack , $value['key']);
			}
		}
		
		if(!empty($haystack))
		{
			foreach ($myEntry['EntryMeta'] as $key => $value) 
			{
				if(in_array($value['key'], $haystack))
				{
					deleteFile($value['value']);
				}
			}
		}
	}
    
    public function add_stock_master_barang($autoId , $jumlah)
	{	
		$caribarang = $this->Entry->meta_details($autoId , "barang-dagang");
		$caristock = $this->find("first" , array(
			"conditions" => array(
				"EntryMeta.entry_id" => $caribarang['Entry']['id'],
				"EntryMeta.key" => "form-stock"
			)
		));
		if(empty($caristock))
		{
			$stock['EntryMeta']['entry_id'] = $caribarang['Entry']['id'];
			$stock['EntryMeta']['key'] = "form-stock";
			$stock['EntryMeta']['value'] = $jumlah;
			$this->create();
			$this->save($stock);
		}
		else
		{
			$this->id = $caristock['EntryMeta']['id'];
			$this->saveField("value" , $caristock['EntryMeta']['value'] + $jumlah);
		}
	}
}

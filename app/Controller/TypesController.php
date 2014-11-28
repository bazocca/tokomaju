<?php
class TypesController extends AppController {
	public $name = 'Types';
	public $components = array('RequestHandler','Session','Validation','Auth');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Get');
	
	public function beforeFilter()
	{
        parent::beforeFilter();		
		if($this->user['role_id'] > 1)
		{
			throw new NotFoundException('Error 404 - Not Found'); 
			return;
		}
    }

	/**
	 * fork our target routes for Type(database) CRUD
	 * @return void
	 * @public
	 **/
	function admin_master()
	{
		// Tree of division beginsss !!
		$myRenderFile = '';
		if(empty($this->request->params['pass']))
		{
			// IF THIS WANT TO LIST ALL TYPES...
			$this->_admin_default();
			$myRenderFile = 'admin_default';
		}
		else if(empty($this->request->params['pass'][1]))
		{
			// IF THIS WANT TO ADD NEW PARENT TYPES...
			if($this->request->params['pass'][0] == 'add')
			{
				$this->_admin_default_add();
				$myRenderFile = 'admin_default_add';
			}
			else // IF THIS WANT TO LIST ALL CHILD TYPES...
			{
				$tesType = $this->Type->findBySlug($this->request->params['pass'][0]);
				if(!empty($tesType))
				{
					$this->_admin_default(1 , $tesType);
					$myRenderFile = 'admin_default';
				}
			}
		}
		else if(empty($this->request->params['pass'][2]))
		{
			// IF THIS WANT TO LIST ALL TYPES WITH PAGINATION ...
			if($this->request->params['pass'][0] == 'index' && is_numeric($this->request->params['pass'][1]))
			{
				$this->_admin_default($this->request->params['pass'][1]);
				$myRenderFile = 'admin_default';
			}
			// IF THIS WANT TO EDIT PARENT TYPES...
			else if($this->request->params['pass'][0] == 'edit')
			{
				$tesType = $this->Type->findBySlug($this->request->params['pass'][1]);
				if(!empty($tesType))
				{
					$this->_admin_default_edit($tesType);
					$myRenderFile = 'admin_default_add';
				}
			}
			// IF THIS WANT TO ADD NEW CHILD TYPES...
			else if($this->request->params['pass'][1] == 'add')
			{
				$tesType = $this->Type->findBySlug($this->request->params['pass'][0]);
				if(!empty($tesType))
				{
					$this->_admin_default_add($tesType);
					$myRenderFile = 'admin_default_add';
				}
			}
		}
		else  // MAX LEVEL...
		{
			$tesType = $this->Type->findBySlug($this->request->params['pass'][0]);
			if(!empty($tesType))
			{
				// IF THIS WANT TO LIST ALL CHILD TYPES WITH PAGINATION ...
				if($this->request->params['pass'][1] == 'index' && is_numeric($this->request->params['pass'][2]))
				{
					$this->_admin_default( $this->request->params['pass'][2] , $tesType );
					$myRenderFile = 'admin_default';
				}
				// IF THIS WANT TO EDIT CHILD TYPES...
				else if($this->request->params['pass'][1] == 'edit')
				{
					$childType = $this->Type->findBySlug($this->request->params['pass'][2]);
					if(!empty($childType))
					{
						$this->_admin_default_edit($childType , $tesType);
						$myRenderFile = 'admin_default_add';
					}
				}
			}
		}
		$this->render($myRenderFile);
	}
	
	/**
	* querying to get list of available database types.
	* @param integer $paging[optional] contains selected page of lists you want to retrieve
	* @param array $myParentType[optional] contains record query result of parent database type(used if want to search that child database types)
	* @return void
	* @public
	**/
	function _admin_default($paging = 1 , $myParentType = array())
	{				
		if ($this->request->is('ajax')) 
		{	
			$this->layout = 'ajax';
			$data['isAjax'] = 1;
		} 
		else 
		{
			$data['isAjax'] = 0;
		}		
		$data['paging'] = $paging;
		$data['myParentType'] = $myParentType;
		$countPage = $this->countListPerPage;
		
		// set page title
		$this->setTitle(empty($myParentType)?'Database Master':$myParentType['Type']['name']);
		
		// our list conditions... ----------------------------------------------------------------------------------////
		$options['conditions'] = array(
			'Type.parent_id' => (empty($myParentType)?0:$myParentType['Type']['id'])
		);
		// find last modified... ----------------------------------------------------------------------------------////
		$options['order'] = array('Type.modified DESC');
		$lastModified = $this->Type->find('first' , $options);
		$data['lastModified'] = $lastModified;
		// end of last modified...
		
		$resultTotalList = $this->Type->find('count' , $options);
		$data['totalList'] = $resultTotalList;
		
		$options['order'] = array('Type.created DESC');
		$options['offset'] = ($paging-1) * $countPage;
		$options['limit'] = $countPage;
			
		$mysql = $this->Type->find('all' ,$options);
		$data['myList'] = $mysql;
		
		// set New countPage
		$newCountPage = ceil($resultTotalList * 1.0 / $countPage);
		$data['countPage'] = $newCountPage;
		
		// set the paging limitation...
		$left_limit = 1;
		$right_limit = 5;
		if($newCountPage <= 5)
		{
			$right_limit = $newCountPage;
		}
		else
		{
			$left_limit = $paging-2;
			$right_limit = $paging+2;
			if($left_limit < 1)
			{
				$left_limit = 1;
				$right_limit = 5;
			}
			else if($right_limit > $newCountPage)
			{
				$right_limit = $newCountPage;
				$left_limit = $newCountPage - 4;
			}			
		}
		$data['left_limit'] = $left_limit;
		$data['right_limit'] = $right_limit;
		$this->set('data' , $data);
	}

	/**
	* add new database type
	* @param array $myParentType[optional] contains record query result of parent database type(used if want to add new database child for that type) 
	* @return void
	* @public
	**/
	function _admin_default_add($myParentType = array())
	{	
		$this->setTitle('Add New Database');
		$data['myParentType'] = $myParentType;
		$this->set('data' , $data);
		
		// if form submit is taken...
		if (!empty($this->request->data)) 
		{
			$this->request->data['Type']['name'] = $this->request->data['Type'][0]['value'];
			$this->request->data['Type']['slug'] = $this->get_slug($this->request->data['Type']['name']);
			$this->request->data['Type']['description'] = $this->request->data['Type'][1]['value'];
			// write my creator...
			
			$this->request->data['Type']['created_by'] = $this->user['id'];
			$this->request->data['Type']['modified_by'] = $this->user['id'];
			// write time created manually !!
			$nowDate = $this->getNowDate();
			$this->request->data['Type']['created'] = $nowDate;
			$this->request->data['Type']['modified'] = $nowDate;
			// set parent_id
			$this->request->data['Type']['parent_id'] = (empty($myParentType)?0:$myParentType['Type']['id']);
			
			// now for validation !!
			$this->Type->set($this->request->data);
			if($this->Type->validates())
			{
				// SPECIAL CASE FOR CHECKING MEDIA SETTINGS ...
				$mediaCounter = 100;
				if(!empty($this->request->data['TypeMeta'][$mediaCounter+1]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+1]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+2]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+2]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+4]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+4]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+5]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+5]['value']) || empty($this->request->data['TypeMeta'][$mediaCounter+1]['value']) && !empty($this->request->data['TypeMeta'][$mediaCounter+2]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+1]['value']) && empty($this->request->data['TypeMeta'][$mediaCounter+2]['value']) || empty($this->request->data['TypeMeta'][$mediaCounter+4]['value']) && !empty($this->request->data['TypeMeta'][$mediaCounter+5]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+4]['value']) && empty($this->request->data['TypeMeta'][$mediaCounter+5]['value']))
				{
					$this->Session->setFlash('Please add media settings correctly.','failed');
					$this->redirect (array('controller'=>'master','action' => 'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'add'));
				}
				$this->Type->create();
				$this->Type->save($this->request->data);
				$typeId = $this->Type->id;
				if(!empty($myParentType))
				{
					// add COUNT to parent Type...
					$this->Type->id = $myParentType['Type']['id'];
					$this->Type->saveField('count' , $myParentType['Type']['count'] + 1);
				}
				
				// NOW ADD TYPE METAS !!
				$this->request->data['TypeMeta']['type_id'] = $typeId;
				
				// SAVE OTHER TYPE METAS (WITH LIMITED COUNTER) !!
				for($i=2 ; $i <= 100 ; ++$i)
				{
					if(!empty($this->request->data['TypeMeta'][$i]))
					{
						if(!empty($this->request->data['TypeMeta'][$i]['value']))
						{
							$this->request->data['TypeMeta']['key'] = strtolower(substr($this->request->data['TypeMeta'][$i]['key'], 5));
							$this->request->data['TypeMeta']['value'] = ($this->request->data['TypeMeta'][$i]['input_type'] == 'checkbox'?implode("|",$this->request->data['TypeMeta'][$i]['value']):$this->request->data['TypeMeta'][$i]['value']);
							$this->TypeMeta->create();
							$this->TypeMeta->save($this->request->data);
						}
					}
					else
					{
						break;
					}
				}
				
				// save our MEDIA SETTINGS !!
				for($i=1 ; $i <= 6 ; ++$i )
				{
					$this->request->data['TypeMeta']['key'] = strtolower(substr($this->request->data['TypeMeta'][$i+$mediaCounter]['key'], 5));
					$this->request->data['TypeMeta']['value'] = $this->request->data['TypeMeta'][$i+$mediaCounter]['value'];
					
			// IF USE NO CROP / AUTOMATIC CROP, BUT SIZE NO DEFINED, IGNORE THAT CROP SETTING ! (EXCEPT MANUAL CROP)
					$endKeyCode = substr($this->request->data['TypeMeta']['key'], -4);
					if(!($endKeyCode=='crop' && $this->request->data['TypeMeta']['value'] < 2 && empty($this->request->data['TypeMeta'][$i+$mediaCounter-1]['value']) || $endKeyCode!='crop' && empty($this->request->data['TypeMeta']['value'])))
					{	
						$this->TypeMeta->create();
						$this->TypeMeta->save($this->request->data);
					}
				}
				
				// NOW SAVE OTHER INPUT TYPES...
				$i = $mediaCounter+7;
				while(!empty($this->request->data['TypeMeta'][$i]))
				{
					$this->request->data['TypeMeta']['key'] = $this->request->data['TypeMeta'][$i++]['key'];
					
					// $this->request->data['TypeMeta']['value'] = $this->slug_option_value($this->request->data['TypeMeta'][$i++]['value']);
					$this->request->data['TypeMeta']['value'] = $this->request->data['TypeMeta'][$i++]['value'];
					
					$this->request->data['TypeMeta']['input_type'] = $this->request->data['TypeMeta'][$i++]['input_type'];
					$this->request->data['TypeMeta']['validation'] = $this->request->data['TypeMeta'][$i++]['validation'];
					$this->request->data['TypeMeta']['instruction'] = $this->request->data['TypeMeta'][$i++]['instruction'];
					$this->TypeMeta->create();
					$this->TypeMeta->save($this->request->data);
				}
				// NOW finally setFlash ^^
				$this->Session->setFlash($this->request->data['Type']['name'].' has been added.','success');
				$this->redirect (array('controller'=>'master','action' => 'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug'])));
			}
			else 
			{
				$this->Session->setFlash('Please complete all required fields.','failed');
				$this->redirect (array('controller'=>'master','action' => 'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'add'));
			}
		}
	}

	/**
	* update certain database type
	* @param array $myType contains record query result of database type which is want to be edited
	* @param array $myParentType[optional] contains record query result of parent database type(used if want to update its certain database child) 
	* @return void
	* @public
	**/
	function _admin_default_edit($myType = array() , $myParentType = array())
	{	
		$this->setTitle('Edit '.$myType['Type']['name']);
		// GENERATE TYPEMETA AGAIN WITH SORT ORDER !!
		$metaOrder = $this->TypeMeta->find('all' , array(
			'conditions' => array(
				'TypeMeta.type_id' => $myType['Type']['id']
			),
			'order' => array('TypeMeta.id ASC')
		));
		$myType['TypeMeta'] = $metaOrder;
		foreach ($metaOrder as $key => $value) 
		{
			$myType['TypeMeta'][$value['TypeMeta']['key']][0] = $value['TypeMeta']['value'];
		}
		$data['myType'] = $myType;
		
		$data['myParentType'] = $myParentType;
		$this->set('data' , $data);
		
		// if form submit is taken...
		if (!empty($this->request->data))
		{
			$this->request->data['Type']['name'] = $this->request->data['Type'][0]['value'];
			$this->request->data['Type']['description'] = $this->request->data['Type'][1]['value'];
			// write my creator...
			
			$this->request->data['Type']['modified_by'] = $this->user['id'];
			// write time created manually !!
			$nowDate = $this->getNowDate();			
			$this->request->data['Type']['modified'] = $nowDate;
			
			// now for validation !!
			$this->Type->set($this->request->data);
			if($this->Type->validates())
			{
				// SPECIAL CASE FOR CHECKING MEDIA SETTINGS ...
				$mediaCounter = 100;
				
				if(!empty($this->request->data['TypeMeta'][$mediaCounter+1]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+1]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+2]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+2]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+4]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+4]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+5]['value']) && !is_numeric($this->request->data['TypeMeta'][$mediaCounter+5]['value']) || empty($this->request->data['TypeMeta'][$mediaCounter+1]['value']) && !empty($this->request->data['TypeMeta'][$mediaCounter+2]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+1]['value']) && empty($this->request->data['TypeMeta'][$mediaCounter+2]['value']) || empty($this->request->data['TypeMeta'][$mediaCounter+4]['value']) && !empty($this->request->data['TypeMeta'][$mediaCounter+5]['value']) || !empty($this->request->data['TypeMeta'][$mediaCounter+4]['value']) && empty($this->request->data['TypeMeta'][$mediaCounter+5]['value']))
				{
					$this->Session->setFlash('Please edit media settings correctly.','failed');
					$this->redirect (array('controller'=>'master','action' => 'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'edit',$myType['Type']['slug']));
				}
				$this->Type->id = $myType['Type']['id'];
				$this->Type->save($this->request->data);
				
				// delete all the attributes, and then add again !!
				$this->TypeMeta->deleteAll(array('TypeMeta.type_id' => $this->Type->id , 
					'OR' => array(
						array('TypeMeta.key LIKE' => 'form%'),
						array('TypeMeta.key LIKE' => 'thumb%'),
						array('TypeMeta.key LIKE' => 'display%')
					)
				));
				
				// NOW ADD TYPE METAS !!
				$this->request->data['TypeMeta']['type_id'] = $this->Type->id;
				
				// SAVE OTHER TYPE METAS (WITH LIMITED COUNTER) !!
				for($i=2 ; $i <= 100 ; ++$i)
				{
					if(!empty($this->request->data['TypeMeta'][$i]))
					{
						$this->request->data['TypeMeta']['key'] = strtolower(substr($this->request->data['TypeMeta'][$i]['key'], 5)); // omit the form_ prefix !!
						
						// check if data field is still exist ?
						$checkTypeMetaField = $this->TypeMeta->find('first' , array(
							'conditions' => array(
								'TypeMeta.type_id' => $this->request->data['TypeMeta']['type_id'],
								'TypeMeta.key' => $this->request->data['TypeMeta']['key']
							)
						));
						
						if(!empty($this->request->data['TypeMeta'][$i]['value']))
						{	
							$this->request->data['TypeMeta']['value'] = ($this->request->data['TypeMeta'][$i]['input_type'] == 'checkbox'?implode("|",$this->request->data['TypeMeta'][$i]['value']):$this->request->data['TypeMeta'][$i]['value']);
							
							if(empty($checkTypeMetaField)) // ADD NEW !!
							{
								$this->TypeMeta->create();
								$this->TypeMeta->save($this->request->data);
							}
							else // UPDATE FIELD !!
							{
								$this->TypeMeta->id = $checkTypeMetaField['TypeMeta']['id'];
								$this->TypeMeta->saveField('value' , $this->request->data['TypeMeta']['value']);
							}
						}
						else // delete field if existed !!
						{
							if(!empty($checkTypeMetaField))
							{
								$this->TypeMeta->delete($checkTypeMetaField['TypeMeta']['id']);
							}
						}
					}
					else
					{
						break;
					}
				}
				
				// save our MEDIA SETTINGS !!
				for($i=1 ; $i <= 6 ; ++$i )
				{
					$this->request->data['TypeMeta']['key'] = strtolower(substr($this->request->data['TypeMeta'][$i+$mediaCounter]['key'], 5));
					$this->request->data['TypeMeta']['value'] = $this->request->data['TypeMeta'][$i+$mediaCounter]['value'];
					
			// IF USE NO CROP / AUTOMATIC CROP, BUT SIZE NO DEFINED, IGNORE THAT CROP SETTING ! (EXCEPT MANUAL CROP)
					$endKeyCode = substr($this->request->data['TypeMeta']['key'], -4);
					if(!($endKeyCode=='crop' && $this->request->data['TypeMeta']['value'] < 2 && empty($this->request->data['TypeMeta'][$i+$mediaCounter-1]['value']) || $endKeyCode!='crop' && empty($this->request->data['TypeMeta']['value'])))
					{	
						$this->TypeMeta->create();
						$this->TypeMeta->save($this->request->data);
					}
				}

				// NOW SAVE OTHER INPUT TYPES...
				$i = $mediaCounter+7;
				while(!empty($this->request->data['TypeMeta'][$i]))
				{
					$this->request->data['TypeMeta']['key'] = $this->request->data['TypeMeta'][$i++]['key'];
					
					// $this->request->data['TypeMeta']['value'] = $this->slug_option_value($this->request->data['TypeMeta'][$i++]['value']);
					$this->request->data['TypeMeta']['value'] = $this->request->data['TypeMeta'][$i++]['value'];
					
					$this->request->data['TypeMeta']['input_type'] = $this->request->data['TypeMeta'][$i++]['input_type'];
					$this->request->data['TypeMeta']['validation'] = $this->request->data['TypeMeta'][$i++]['validation'];
					$this->request->data['TypeMeta']['instruction'] = $this->request->data['TypeMeta'][$i++]['instruction'];
					$this->TypeMeta->create();
					$this->TypeMeta->save($this->request->data);
				}
				// NOW finally setFlash ^^
				$this->Session->setFlash($this->request->data['Type']['name'].' has been updated.','success');
				$this->redirect (array('controller'=>'master','action' => 'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug'])));
			}
			else 
			{
				$this->Session->setFlash('Update failed. Please try again','failed');
				$this->redirect (array('controller'=>'master','action' => 'types'.(empty($myParentType)?'':'/'.$myParentType['Type']['slug']),'edit',$myType['Type']['slug']));
			}
		}
	}

	function get_input_types()
	{
		// get bunch of input types...
		$src = $this->get_view_dir().DS.'Elements';
		$src = scandir($src);
		foreach ($src as $key => $value) 
		{			
			if(substr($value, 0 , 6) == 'input_')
			{
				//$temp = strrpos($value, '.ctp');
				$inputType[] = substr($value, 6 , strlen($value) - 10);
			}
		}
		return $inputType;
	}

	function form_popup($state)
	{
		$this->layout='ajax';
		$this->set('inputType' , $this->get_input_types());
		$this->set('state' , $state);
		// if form submit is taken...
		if (!empty($this->request->data))
		{			
			$result = array();
			$this->autoRender = FALSE;
			$src = $this->request->data['TypeMeta'];
			
			$src['value']['option'] = trim($src['value']['option']); // SPECIAL CASE !!!
			
			// VALIDATE FIRST !!
			if(empty($src['key']) || $src['value']['state'] == 'exist' && empty($src['value']['option']) && empty($src['validation']['browse_module']) || $src['validation']['min_length']['state'] == 'yes' && (empty($src['validation']['min_length']['value']) || !is_numeric($src['validation']['min_length']['value']) || $src['validation']['min_length']['value'] == 0) || $src['validation']['max_length']['state'] == 'yes' && (empty($src['validation']['max_length']['value']) || !is_numeric($src['validation']['max_length']['value']) || $src['validation']['max_length']['value'] == 0))
			{
				$result['state'] = 'failed';
			}
			else if($src['validation']['max_length']['value'] < $src['validation']['min_length']['value'] && !empty($src['validation']['max_length']['value']) && !empty($src['validation']['min_length']['value']))
			{
				$result['state'] = 'minmax';
			}
			else
			{	
				$src['key'] = strtolower(Inflector::slug($src['key']));
				$result['key'] = 'form-'.$src['key'];
				$result['frontKey'] = string_unslug($src['key']);
				
				$result['value'] = ($src['value']['state']=='exist' && empty($src['validation']['browse_module'])?$src['value']['option']:'');
				$result['input_type'] = $src['input_type'];
				$result['instruction'] = $src['instruction'];
				$result['validation'] = '';
				foreach ($src['validation'] as $key => $value) 
				{	
					if($key == 'min_length' || $key == 'max_length')
					{
						$result['validation'] .= ( $value['state'] == 'yes' ?$key.'_'.$value['value'].'|':'');
					}
					else 
					{
						$result['validation'] .= ( $value == 'yes' ?$key.'|':'');
					}
				}
			}
			echo json_encode($result);
		}
	}

	function delete($id = null) 
	{	
		$this->autoRender = FALSE;
		if (!$id) {
			$this->Session->setFlash('Invalid id for type', 'failed');
			header("Location: ".$_SESSION['now']);
			exit;
		}		
		$title = $this->Type->findById($id);
		
		$test = $this->Entry->findByEntryType($title['Type']['slug']);
		if(!empty($test))
		{
			$this->Session->setFlash('This type is in used by certain entry. Please remove them first!', 'failed');
			header("Location: ".$_SESSION['now']);
			exit;
		}
		$this->TypeMeta->deleteAll(array('TypeMeta.type_id' => $id));
		$this->Type->delete($id);		
		$myChildren = $this->Type->findAllByParentId($id);
		foreach ($myChildren as $key => $value) 
		{
			$this->TypeMeta->deleteAll(array('TypeMeta.type_id' => $value['Type']['id']));
		}
		$this->Type->deleteAll(array('Type.parent_id' => $id));
		
		// minus the count of parent Type...
		if($title['Type']['parent_id'] > 0)
		{
			$myParent = $this->Type->findById($title['Type']['parent_id']);
			$this->Type->id = $myParent['Type']['id'];
			$this->Type->saveField('count' , $myParent['Type']['count'] - 1);
		}
		$this->Session->setFlash($title['Type']['name'].' has been deleted', 'success');
		header("Location: ".$_SESSION['now']);
		exit;
	}

	function slug_option_value($src)
	{
		$temp = explode(chr(13).chr(10), $src);
		foreach ($temp as $key => $value) 
		{
			$temp[$key] = Inflector::slug($value);
		}
		$result = implode(chr(13).chr(10), $temp);
		return $result;
	}
}

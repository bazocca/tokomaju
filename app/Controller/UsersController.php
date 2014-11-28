<?php
class UsersController extends AppController {
	public $name='Users';    
    public $components = array('RequestHandler');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Get');
	
	/**
	 * Before render callback. beforeRender is called before the view file is rendered.
	 * Overridden in subclasses.
	 * @param string $activePage contains the name of active page
	 * @return void
	 * @public
	 **/
    public function beforeRender($activePage='User')
    {   
		parent::beforeRender($activePage);
    }
	
	/**
	* querying to get list of users.
	* @param integer $paging[optional] contains selected page of lists you want to retrieve
	* @return void
	* @public
	**/
	function admin_index($paging = 1) 
	{
		$popup = $this->request->query['popup'];		
		if(!empty($popup) || $this->request->is('ajax'))
		{
			$this->layout = 'ajax';
		}
		if ($this->request->is('ajax') && empty($popup) || $popup == "ajax")
		{	
			$this->set("isAjax",1);
			if($this->request->data['search_by'] != NULL)
			{
				$searchMe = trim($this->request->data['search_by']);
				$this->set("search" , "yes");
				if(empty($searchMe))
				{
					unset($_SESSION['searchMe']);
				}
				else
				{
					$_SESSION['searchMe'] = $searchMe;
				}
			}
		} 
		else 
		{
			$this->set("isAjax",0);
			unset($_SESSION['searchMe']);
		}
		$this->set('paging' , $paging);
		$this->set('popup' , $popup);
		// set page title
		$this->setTitle('Users');
		// set paging session...		
		$countPage = $this->countListPerPage;
		
		// our list conditions...
		$options = array();
		
		if($this->user['role_id'] > 1)
		{
			$options['conditions'] = array(
				'User.id >' => 1
			);
		}
		// find last modified...		
		$options['order'] = array('User.modified DESC');
		$lastModified = $this->User->find('first' , $options);	
		$this->set('lastModified' , $lastModified);
		// end of last modified...
		
		$options['order'] = array('User.firstname' , 'User.lastname');
		$mysql = $this->User->find('all' ,$options);
		// MODIFY OUR USERMETA FIRST !!
		foreach ($mysql as $key => $value) 
		{
			$state = 0;
			if(!empty($_SESSION['searchMe']))
			{
				if(stripos($value['User']['firstname'], $_SESSION['searchMe']) !== FALSE || stripos($value['User']['lastname'], $_SESSION['searchMe']) !== FALSE)
				{
					$state = 1;
				}
			}
			foreach ($value['UserMeta'] as $key10 => $value10) 
			{
				$mysql[$key]['UserMeta'][$value10['key']] = $value10['value'];
				if(!empty($_SESSION['searchMe']) && stripos($value10['value'], $_SESSION['searchMe']) !== FALSE)
				{
					$state = 1;
				}
			}
			if(empty($_SESSION['searchMe']) || $state == 1)
			{
				$mysqlTemp[] = $mysql[$key];
			}
		}
		// SECOND FILTER GO NOW !!!
		$offset = ($paging-1) * $countPage;
		$endset = $offset + $countPage;
		$resultTotalList = count($mysqlTemp);
		$this->set('totalList' , $resultTotalList);
		for($key = $offset ; $key < $endset && !empty($mysqlTemp[$key]) ; ++$key)
		{	
			$myList[] = $mysqlTemp[$key];
		}
		$this->set('myList' , $myList);
		
		// set New countPage
		$newCountPage = ceil($resultTotalList * 1.0 / $countPage);
		$this->set('countPage' , $newCountPage);
		
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
		
		$this->set('left_limit' , $left_limit);
		$this->set('right_limit' , $right_limit);
	}
	
	/**
	 * change user status (active or disabled)
	 * @param integer $id contains id of the user
	 * @return void
	 * @public
	 **/
	function change_status($id=null)
	{
		if ($id != null)
		{
			$this->autoRender = false;
			$data = $this->User->findById($id);
			$this->User->id = $id;
			$this->User->saveField('status', $data['User']['status']==0?1:0);
			header("Location: ".$_SESSION['now']);
			exit;
		}
	}

	/**
	* add users(exclude account actually)
	* @return void
	* @public
	**/
	function admin_add()
	{	
		$this->setTitle('Add New User');	
		if (!empty($this->request->data)) 
		{	
			
			$this->request->data['User']['created_by'] = $this->user['id'];
			$this->request->data['User']['modified_by'] = $this->user['id'];
			
			$this->User->set($this->request->data);
			if($this->User->validates() && !(empty($this->request->data['UserMeta']['address']) || empty($this->request->data['UserMeta']['city']) || empty($this->request->data['UserMeta']['mobile_phone']) || empty($this->request->data['UserMeta']['gender'])  ) )
			{
				$myDetails = $this->request->data['UserMeta'];
				$findDuplicate = $this->User->find('first' , array(
					'conditions' => array(
						'User.firstname' => $this->request->data['User']['firstname'],
						'User.lastname' => $this->request->data['User']['lastname'],
					)
				));				
				if(!empty($findDuplicate))
				{
					$this->Session->setFlash('This user has already existed.','failed');
            		return;
				}
				$this->User->create();
				$this->User->save($this->request->data);
				
				$this->request->data['UserMeta']['user_id'] = $this->User->id;
				foreach ($myDetails as $key => $value) 
				{
					if(!empty($value))
					{
						$this->request->data['UserMeta']['key'] = $key;
						$this->request->data['UserMeta']['value'] = $value;
						$this->UserMeta->create();
						$this->UserMeta->save($this->request->data);
					}
				}
				
				$this->Session->setFlash($this->request->data['User']['firstname'].' '.$this->request->data['User']['lastname'].' has been created.','success');
				$this->redirect (array('action' => 'index'));
			}
			else 
			{
				$this->Session->setFlash('Please complete all required fields.','failed');
			}
		}
	}

	/**
	 * update user
 	 * @param integer $id contains id of the user
	 * @return void
	 * @public
	 **/
	function admin_edit($id = null) 
	{
		$this->setTitle('Edit User');
		$this->set('id',$id);
		$result = $this->User->findById($id);
		$myDetails = $result['UserMeta'];
		foreach ($myDetails as $key => $value) 
		{
			$result['UserMeta'][$value['key']] = $value['value'];
		}
		$this->set('myData' , $result);
		
		if (!empty($this->request->data)) 
		{
			
			$this->request->data['User']['modified_by'] = $this->user['id'];
			
			$this->User->set($this->request->data);
			if($this->User->validates() && !(empty($this->request->data['UserMeta']['address']) || empty($this->request->data['UserMeta']['city']) || empty($this->request->data['UserMeta']['mobile_phone']) || empty($this->request->data['UserMeta']['gender'])  ) )
			{	
				$myDetails = $this->request->data['UserMeta'];
				$findDuplicate = $this->User->find('first' , array(
					'conditions' => array(
						'User.firstname' => $this->request->data['User']['firstname'],
						'User.lastname' => $this->request->data['User']['lastname'],
						'User.id <>' => $id
					)
				));				
				if(!empty($findDuplicate))
				{
					$this->Session->setFlash('This user has already existed.','failed');
            		return;
				}
				
				$this->User->id = $id;
				$this->User->save($this->request->data);
				
				// delete first, and then add again...
				$this->UserMeta->deleteAll(array('UserMeta.user_id' => $id));
				
				$this->request->data['UserMeta']['user_id'] = $this->User->id;
				foreach ($myDetails as $key => $value) 
				{
					if(!empty($value))
					{
						$this->request->data['UserMeta']['key'] = $key;
						$this->request->data['UserMeta']['value'] = $value;
						$this->UserMeta->create();
						$this->UserMeta->save($this->request->data);
					}
				}
				
				$this->Session->setFlash($this->request->data['User']['firstname'].' '.$this->request->data['User']['lastname'].' has been updated.','success');
				$this->redirect (array('action' => 'index'));
			}
			else 
			{
				$this->Session->setFlash('The User could not be saved. Please try again','failed');
			}
		}
	}

	/**
	 * delete user
	 * @param integer $id contains id of the user
	 * @return void
	 * @public
	 **/
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for user', 'failed');
			$this->redirect(array('action'=>'index','admin'=>true));
		}		
		$this->User->delete($id);
		$this->UserMeta->deleteAll(array('UserMeta.user_id' => $id));
		$this->Account->deleteAll(array('Account.user_id' => $id));
		
		$this->Session->setFlash('User has been deleted', 'success');
		$this->redirect(array('action'=>'index','admin'=>true));
	}	
}

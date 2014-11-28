<?php
class RolesController extends AppController {	
	public $name = 'Roles';
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

	function index() {
		$this->Role->recursive = 0;
		$this->set('roles', $this->paginate());
	}
	
	/**
	 * fork our target routes for Role CRUD
	 * @return void
	 * @public
	 **/
	function admin_master()
	{
		// Tree of division beginsss !!
		$myRenderFile = '';
		if(empty($this->request->params['pass']))
		{
			// IF THIS WANT TO LIST ALL ROLES...
			$this->_admin_default();
			$myRenderFile = 'admin_default';
		}
		else if(empty($this->request->params['pass'][1]))
		{
			// IF THIS WANT TO ADD NEW ROLES...
			if($this->request->params['pass'][0] == 'add')
			{
				$this->_admin_default_add();
				$myRenderFile = 'admin_default_add';
			}
		}
		else // MAX LEVEL ...
		{			
			// IF THIS WANT TO EDIT ROLES...
			if($this->request->params['pass'][0] == 'edit')
			{	
				$myRole = $this->Role->findById($this->request->params['pass'][1]);
				if(!empty($myRole))
				{
					$this->_admin_default_edit($myRole);
					$myRenderFile = 'admin_default_add';
				}
			}			
		}
		$this->render($myRenderFile);
	}

	/**
	* querying to get list of available roles.
	* @return void
	* @public
	**/
	function _admin_default()
	{	
		// set page title
		$this->setTitle('Role Master');
		
		$resultTotalList = $this->Role->find('count');
		$data['totalList'] = $resultTotalList;
			
		$mysql = $this->Role->find('all' , array(
			"order" => array("Role.id")
		));
		$data['myList'] = $mysql;
		$this->set('data' , $data);
	}

	/**
	* add new roles
	* @return void
	* @public
	**/
	function _admin_default_add()
	{	
		$this->setTitle('Add New Role');
		$this->set('data' , $data);
		
		// if form submit is taken...
		if (!empty($this->request->data)) 
		{
			$this->request->data['Role']['name'] = $this->request->data['Role'][0]['value'];
			$this->request->data['Role']['description'] = $this->request->data['Role'][1]['value'];
			
			// now for validation !!
			$this->Role->set($this->request->data);
			if($this->Role->validates())
			{
				$this->Role->create();
				$this->Role->save($this->request->data);
				
				// NOW finally setFlash ^^
				$this->Session->setFlash($this->request->data['Role']['name'].' has been added.','success');
				$this->redirect (array('controller'=>'master','action' => 'roles'));
			}
			else 
			{
				$this->Session->setFlash('Please complete all required fields.','failed');
				$this->redirect (array('controller'=>'master','action' => 'roles','add'));
			}
		}
	}

	/**
	* update roles
	* @return void
	* @public
	**/
	function _admin_default_edit($myRole = array())
	{	
		$this->setTitle('Edit '.$myRole['Role']['name']);
		$data['myRole'] = $myRole;
		$this->set('data' , $data);
		
		// if form submit is taken...
		if (!empty($this->request->data))
		{
			$this->request->data['Role']['name'] = $this->request->data['Role'][0]['value'];
			$this->request->data['Role']['description'] = $this->request->data['Role'][1]['value'];
			
			// now for validation !!
			$this->Role->set($this->request->data);
			if($this->Role->validates())
			{
				$this->Role->id = $myRole['Role']['id'];
				$this->Role->save($this->request->data);
				
				// NOW finally setFlash ^^
				$this->Session->setFlash($this->request->data['Role']['name'].' has been updated.','success');
				$this->redirect (array('controller'=>'master','action' => 'roles'));
			}
			else 
			{
				$this->Session->setFlash('Update failed. Please try again','failed');
				$this->redirect (array('controller'=>'master','action' => 'roles','edit',$myRole['Role']['id']));
			}
		}
	}

	/**
	 * delete role
	 * @param integer $id contains id of the role
	 * @return void
	 * @public
	 **/
	function delete($id = null) 
	{
		$this->autoRender = FALSE;
		if (!$id) {
			$this->Session->setFlash('Invalid id for role', 'failed');
			header("Location: ".$_SESSION['now']);
			exit;
		}		
		$title = $this->Role->findById($id);
		
		$test = $this->Account->findByRoleId($id);
		if(!empty($test))
		{
			$this->Session->setFlash('This role is in used by certain user. Please remove them first!', 'failed');
			header("Location: ".$_SESSION['now']);
			exit;
		}
		$this->Role->delete($id);
		$this->Session->setFlash($title['Role']['name'].' has been deleted', 'success');
		header("Location: ".$_SESSION['now']);
		exit;
	}
}

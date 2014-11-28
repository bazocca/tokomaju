<?php
class AccountsController extends AppController {
	public $name = 'Accounts';
    public $components = array('RequestHandler','Session');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Get');
	
	/**
	 * BeforeFilter callback. Called before the controller action.
	 * @return void
	 * @public
	 **/
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('forget','send_email','redirect_login','login');
    }
	
	public function admin_index($paging = 1) 
	{
		$popup = $this->request->query['popup'];
		$popupRole = $this->request->query['role'];
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
		$this->set('popupRole' , $popupRole);
		// set page title
		$this->setTitle('Accounts');		
		// set paging session...
		$countPage = $this->countListPerPage;
		
		// our list conditions...		
		if($this->user['role_id'] > 1)
		{	
			$options['conditions']['Role.name NOT LIKE'] = "super admin";
		}
		if(!empty($popup))
		{
			$options['conditions']['Role.name'] = $popupRole;
		}
		// find last modified...		
		$options['order'] = array('Account.modified DESC');
		$lastModified = $this->Account->find('first' , $options);	
		$this->set('lastModified' , $lastModified);
		// end of last modified...
		
		$options['order'] = array('User.firstname' , 'User.lastname' , 'Account.role_id');
		$mysql = $this->Account->find('all' ,$options);
		// MODIFY OUR USERMETA FIRST !!
		foreach ($mysql as $key => $value) 
		{
			$state = 0;
			if(!empty($_SESSION['searchMe']))
			{
				if(stripos($value['Account']['email'], $_SESSION['searchMe']) !== FALSE || stripos($value['Account']['username'], $_SESSION['searchMe']) !== FALSE || stripos($value['User']['firstname'], $_SESSION['searchMe']) !== FALSE || stripos($value['User']['lastname'], $_SESSION['searchMe']) !== FALSE || stripos($value['Role']['name'], $_SESSION['searchMe']) !== FALSE)
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
	* add user account to database account
	* @return void
	* @public
	**/
	function admin_add() 
	{
		$this->setTitle('Add New Account');
		$listRoles = $this->Role->find('all',array(
			"conditions" => array(
				"Role.id >" => 1
			)
		));
		$this->set('listRoles' , $listRoles);
		
		if (!empty($this->request->data)) 
		{
			$this->request->data['Account']['created_by'] = $this->user['id'];
			$this->request->data['Account']['modified_by'] = $this->user['id'];

			$this->Account->set($this->request->data);
			if($this->Account->validates())
			{
				if($this->request->data['Account']['confirm']==$this->request->data['Account']['password'])
				{
					$this->Account->create();
					$this->Account->save($this->request->data);
					$this->Session->setFlash((empty($this->request->data['Account']['username'])?$this->request->data['Account']['email']:$this->request->data['Account']['username']).' account has been created.','success');
					$this->redirect (array('action' => 'index'));
				}
				else
				{
					$this->Session->setFlash('Add user account failed. Confirmation password must match with password. Please try again','failed');
				}
			}
			else 
			{
				$this->_setFlashInvalidFields($this->Account->invalidFields());
			}
		}
	}

	/**
	 * update user account
 	 * @param integer $id contains id of the user account
	 * @return void
	 * @public
	 **/
	function admin_edit($id) 
	{
		$this->setTitle('Edit Account');
		
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash('Invalid account', 'failed');
			$this->redirect(array('action' => 'index'));
		}

		$this->set('id',$id);
		$result = $this->Account->findById($id);
		$this->set('myData' , $result);
		
		if (!empty($this->request->data)) 
		{	
			$this->request->data['Account']['modified_by'] = $this->user['id'];
			$ignorePassword = (empty($this->request->data['Account']['confirm']));
			if($ignorePassword)
			{
				unset($this->request->data['Account']['password']);
			}

			$this->Account->id = $id;
			$this->Account->set($this->request->data);
			if($this->Account->validates())
			{
				$success = 0;
				if($ignorePassword)
				{
					$success = 1;
				}	
				else
				{
					if($this->request->data['Account']['confirm']==$this->request->data['Account']['password'])
					{
						$success = 1;
					}
					else
					{
						$this->Session->setFlash('Update user account failed. Confirmation password must match with password. Please try again','failed');
					}
				}	
					
				if($success == 1)
				{	
					$this->Account->save($this->request->data);
					$this->Session->setFlash('Update account success.','success');
					$this->redirect (array('action' => 'index'));
				}
			}
			else 
			{
				$this->_setFlashInvalidFields($this->Account->invalidFields());
			}
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for user account', 'failed');
			$this->redirect(array('action'=>'index','admin'=>true));
		}		
		$this->Account->delete($id);
		$this->Session->setFlash('User account has been deleted', 'success');
		$this->redirect(array('action'=>'index','admin'=>true));
	}

	function redirect_login()
	{
		if($this->Auth->login())
		{
			$this->redirect(array('controller'=>'settings','action'=>'index','admin'=>true));
		}
		else 
		{
			$this->redirect('/');
		}
	}

	function login() 
	{
		if($this->Auth->login())
		{
			$myAccount = $this->Auth->user();			
			$this->Account->id = $myAccount['id'];
			$this->Account->saveField('last_login' , $this->getNowDate());
			$this->Account->saveField('modified' , $myAccount['modified']); // revert back modified time...
			if(!empty($this->request->query['resource']))
            {
                $this->redirect('/admin'.$this->request->query['resource']);
            }
            else // default landing page after login !!
            {
                $this->redirect(array('controller'=>'settings','action'=>'index','admin'=>true));
            }
		}
		else
		{
			if($this->request->is('post'))
			{
				unset($this->request->data['Account']['password']);
				$this->Session->setFlash(__('Login failed. Invalid username or password.'),'default',array(),'auth');
			}
		}

		$this->setTitle('Login');
		$this->layout = 'login_default';
        $this->set('is_admin' , 1);
    }
	
	/**
	 * Authorization for logging out from the admin panel
	 * @return void
	 * @public
	 **/
    function logout()
    {
    	$this->Session->destroy();
    	$this->Session->setFlash('You have been logout.','forget_success');
    	$this->redirect($this->Auth->logout());
    }
	
	function admin_logout()
	{
		$this->Session->destroy();
		$this->Session->setFlash('You have been logout.','forget_success');
		$this->redirect('/admin/login');
	}
	
	function forget()
    {
    	if($this->Auth->login())
		{
			$this->redirect(array('controller'=>'settings','action'=>'index','admin'=>true));
		}
		$this->setTitle('Forget Password');
        $this->layout = 'login_default';
        $is_admin = 1;
		$this->set('is_admin' , $is_admin);
		
        if(!empty($this->request->data))
        {
        	$row = $this->Account->findAllByRoleId(array(1,2));
			
			$success = -1;
			foreach ($row as $key => $value) 
			{
				if($value['Account']['email'] == $this->request->data['Account']['email'])
				{
					//Update password and don't forget to hash the password
					$newpassword = $this->Account->createRandomPassword(20);
					
					// prepare the email...
					App::uses('CakeEmail', 'Network/Email');
				    $Email = new CakeEmail();

				    $body = 'Hello, '.$value['User']['firstname'].'!<br/>';
		            $body .= sprintf('Email: %s <br/>Your new password: %s<br/>',$this->request->data['Account']['email'] , $newpassword);
					$body .= 'Thank you for your attention.';

					try{
						if( $Email->from(array('system@'.strtolower($_SERVER['SERVER_NAME'])=>'System'))
					          ->to(array($this->request->data['Account']['email']=>$value['User']['firstname']))
					          ->subject($this->mySetting['title'].' - Sending Recovery Account Password')
					          ->emailFormat('html')
					          ->template('default','default')
					          ->send($body) )
						{
							$this->Account->id = $value['Account']['id'];
							$this->Account->saveField('password' , Security::hash($newpassword,null,true));
							$this->Session->setFlash('New password has been sent to your inbox!','forget_success');
							$this->redirect('/');
						}
						else // Failure, without any exceptions
						{
							$this->Session->setFlash('Email failed to sent. Please try again.','forget_failure');
						}
					} catch(Exception $e){
						// Failure, with exception
						$this->Session->setFlash('Failed to connect to mailserver. Please check your connection.','forget_failure');
					}

					$success = 1;
					break;
				}
			}

			if($success == -1)
			{
				$this->Session->setFlash('Input email is incorrect!','forget_failure');
			}
        }
    }	

	function send_email()
	{
		$this->autoRender = FALSE;
        if(!empty($this->request->data))
        {
        	// prepare the email...
			App::uses('CakeEmail', 'Network/Email');
		    $Email = new CakeEmail();

		    $mybody = "First Name : ".$this->request->data['contact']['fname']."<br/>";
			$mybody .= "Last Name : ".$this->request->data['contact']['lname']."<br/>";
			$mybody .= "Company : ".$this->request->data['contact']['company']."<br/>";
			$mybody .= "Website : ".$this->request->data['contact']['website']."<br/>";
			$mybody .= "Telephone : ".$this->request->data['contact']['telephone']."<br/>";
			$mybody .= "Message :<br/>".$this->request->data['contact']['message']."<br/>";

			try{
				if( $Email->from(array($this->request->data['contact']['email']=>'System'))
			          ->to(array($this->request->data['contact']['target']=>$this->request->data['contact']['fname'].' '.$this->request->data['contact']['lname']))
			          ->subject($this->mySetting['title']." - Our Profile")
			          ->emailFormat('html')
			          ->template('default','default')
			          ->send($mybody) )
				{
					echo "0";
				}
				else // Failure, without any exceptions
				{
					echo "1";
				}
			} catch(Exception $e){
				// Failure, with exception
				echo "1";
			}
        }		
    }
}

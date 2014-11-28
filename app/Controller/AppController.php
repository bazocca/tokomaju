<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller {
	public $active='index';
	public $uses = array('Account','User','UserMeta','Type','TypeMeta','EntryMeta','Entry','Role','Setting','InstantPaymentNotification');
	public $components = array('Auth','Session','RequestHandler');
	
	public $countListPerPage , $mediaPerPage , $mySetting , $user;

	public function __construct($request = null, $response = null) {
	    parent::__construct($request, $response);
	    // Your code here.
	}
	
	function get_db_user()
	{
		$fields = get_class_vars('DATABASE_CONFIG');
		return $fields['default']['login'];
	}
	
	function get_db_password()
	{
		$fields = get_class_vars('DATABASE_CONFIG');
		return $fields['default']['password'];
	}
	
	function get_db_name()
	{
		$fields = get_class_vars('DATABASE_CONFIG');
		return $fields['default']['database'];
	}
	
	function get_db_host()
	{
		$fields = get_class_vars('DATABASE_CONFIG');
		return $fields['default']['host'];
	}
	
	function _setInitLayout()
	{
		if(!$this->RequestHandler->isRss())
		{
			$this->layout = 'cms_default';
		}
	}
	
	function _setErrorLayout() 
	{
		if ($this->name == 'CakeError') 
		{
			$this->layout = 'error';
		}
	}

	function _setFlashInvalidFields( $errMsg = array() )
	{
		$flashMsg = "";
		foreach ($errMsg as $fieldkey => $fieldvalue) 
		{
			foreach ($fieldvalue as $key => $value) 
			{
				if(strpos($flashMsg, $value) === FALSE)
				{
					$flashMsg .= $value."<br>";
				}	
			}
		}
		$this->Session->setFlash($flashMsg,'failed');
	}
	
	/**
	* set layout title
	* @param string $title get title
	* @return boolean
	**/
	
	public function setTitle($title=null)
	{
		$this->set('title_for_layout', $title);
		return false;
	}
	
	/**
	* set title for layout
	* @param string $one title name
	* @param string $two get title
	* @return void
	**/
	
	public function set( $one, $two = NULL )
	{
		if($one=='title_for_layout')
		{
			$findTitle = $this->Setting->findById(1);
            if(empty($two))
            {
                $two = $findTitle['Setting']['value'];
            }
            else
            {
                $two .= ' | '.$findTitle['Setting']['value'];
            }
		}
		parent::set($one,$two);
	}
	
	/**
	* set all variable before render page / view files
	* @param string $activePage get active page
	* @return void
	**/
	
	public function beforeRender($activePage='Index')
	{
		$this->_setErrorLayout();		
		$this->set('activePage',$activePage);
		$this->set('site',$this->get_host_name());
		$this->set('imagePath',$this->get_linkpath());
		$this->set('user',$this->user);
		// -------------------------------------------------------------------- >>>
		// view all the Type, but not Child !!
		$myTypes = $this->Type->find('all',array(
			'conditions' => array(
				'Type.parent_id' => 0
			),
			'order' => array('Type.name')
		));
		$this->set('types',$myTypes);
		
		// get all the pages...
		$myPages = $this->Entry->find('all' , array(
			'conditions' => array(
				'Entry.entry_type' => 'pages'
			),
			'order' => array('Entry.id')
		));
		$this->set('pages' , $myPages);
		$this->set('mySetting' , $this->mySetting);
		parent::beforeRender();
	}
	
	/**
	* set all variable before load page / controller action
	* @return void
	**/	
	function beforeFilter()
	{
		parent::beforeFilter();		
		$this->_setInitLayout();

		// url redirection for login kicked out !!
		$urlext = "";
		$is_admin = false;
		if($this->request->url != '/')
		{
            $myurl = explode('/' , $this->request->url);

			if(strtolower($myurl[0]) == 'admin')
			{
				array_shift($myurl);
				$is_admin = true;
			}

			if(!empty($myurl[0]) && strtolower($myurl[0]) != 'login')
			{
				$urlext = "?resource=%2F".implode("%2F" , $myurl);
                if(!empty($this->request->query))
                {
                    $urlext .= urlencode(get_more_extension($this->request->query));
                }
			}
		}

		/* AUTHENTICATION */
		$this->Auth->authenticate = array(
			'Form' => array(
				'fields' => array('username' => 'email', 'password' => 'password'),
				'userModel' => 'Account',
				'scope' => array('Account.role_id' => array(1,2,3)),
			),
		);
		$this->Auth->authError= 'Authorized access is required.';
		$this->Auth->autoRedirect = false;
		$this->Auth->logoutRedirect = '/';
		$this->Auth->loginAction = '/'.$urlext;

		// update $this->user variable with $this->Auth->user() if existed.
		$this->user = $this->Auth->user();
		if(!empty($this->user))
		{
			$this->user['UserMeta'] = array();
			$myUser = $this->User->findById($this->user['user_id']);
			foreach ($myUser['UserMeta'] as $key => $value) $this->user['UserMeta'][ $value['key'] ] = $value['value'];
		}

		// check role if admin or not...
		if( isset($this->request->params['admin']) && $this->request->params['admin'] == 1)
		{
			if(!empty($this->user))
			{				
				if($this->user['role_id'] > 2)
				{	
					$this->Session->setFlash(__('Authorized access is required.'),'default',array() , 'auth');
					$this->redirect($this->Auth->logout());
				}
			}
		}
		$temp = $this->Setting->findByKey("custom-pagination");
		$this->countListPerPage = (empty($temp['Setting']['value'])?10:$temp['Setting']['value']);
		$this->mediaPerPage = 24;
		
		// get all the settings spec with metakey arranged !!
		$this->mySetting = $this->Setting->get_settings();
	}
	
	public function getNowDate()
	{
		return date('Y-m-d H:i:s' , gmt_adjustment());
	}
	
	public function get_slug($value)
	{
		return get_slug($value);
	}

	public function get_view_dir()
	{
		$str = substr(WWW_ROOT, 0 , strlen(WWW_ROOT)-1); // buang DS trakhir...
		$str = substr($str, 0 , strripos($str, DS)+1); // buang webroot...
		$src = $str.'View';
		return $src;
	}

// ------------------------------------------------------------------------------------------------------------ //
// ----------------------------------- HOST SETTING FUNCTION -------------------------------------------------- //
// ------------------------------------------------------------------------------------------------------------ //
	public function get_http() 
	{
		if(!empty($_SERVER['HTTPS']))
		{
			return 'https://';
		}
		else 
		{
			return 'http://';
		}
    }
	
	public function get_host_name()
	{
		return $this->get_http().$_SERVER['SERVER_NAME'].$this->get_linkpath();
	}
	
	public function get_linkpath()
	{
		$test = getcwd();
        $test2 = explode(DS , $test);
        $result = $test2[count($test2)-3];  // get word before /app/webroot
        
        $imagePath = "";
        if(empty($result) || !isLocalhost())
        {
            $imagePath = '/';
        }
        else
        {
            $imagePath = '/'.$result.'/';
        }
        
        return $imagePath;
	}
// ------------------------------------------------------------------------------------------------------------ //
// ----------------------------------- END OF HOST SETTING FUNCTION ------------------------------------------- //
// ------------------------------------------------------------------------------------------------------------ //
}

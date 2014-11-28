<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your Model
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
 
class AppModel extends Model {
	function getCurrentUser()
	{
		App::uses('SessionComponent' , 'Controller/Component');
		$Session = new SessionComponent();		
		$this_user = $Session->read('Auth.User');
		if(!empty($this_user))
		{
			$User = ClassRegistry::init('User');
			$this_user['UserMeta'] = array();
			$myUser = $User->findById($this_user['user_id']);
			foreach ($myUser['UserMeta'] as $key => $value) $this_user['UserMeta'][ $value['key'] ] = $value['value'];
		}
		return $this_user;
	}
	
	public function get_slug($value)
	{
		$str = Inflector::slug($value);
		return str_replace('_','-', strtolower($str));
	}
	
	/** 
     * checks is the field value is unqiue in the table 
     * note: we are overriding the default cakephp isUnique test as the 
	 * original appears to be broken 
     * 
     * @param string $data Unused ($this->data is used instead) 
     * @param mixed $fields field name (or array of field names) to 
	 * validate 
     * @return boolean true if combination of fields is unique 
     */ 
     function checkUnique($data, $fields) 
     { 
        if (!is_array($fields)) { 
                $fields = array($fields); 
        } 
        foreach($fields as $key) { 
                $tmp[$key] = $this->data[$this->name][$key]; 
        } 
        if (isset($this->data[$this->name][$this->primaryKey])) { 
                $tmp[$this->primaryKey] = "<>".$this->data[$this->name][$this->primaryKey]; 

        } 
        return $this->isUnique($tmp, false); 
     } 
}

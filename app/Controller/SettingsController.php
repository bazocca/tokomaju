<?php
class SettingsController extends AppController {
	public $name = 'Settings';
    public $components = array('RequestHandler','Validation','Session');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Get');

	public function beforeFilter(){
        parent::beforeFilter();
		$this->Auth->allow('error404');
    }

	function index() {
		$this->set('title_for_layout', 'Setting');
	}

	function error404()
	{
		throw new NotFoundException('Error 404 - Not Found'); 
		return;
	}
	
	function admin_index() 
	{
		$this->setTitle('Setting');
		// if form submit is taken...
		if(!empty($this->request->data))
		{
			// SPECIAL CASE FOR CHECKBOX CROPPING IMAGE DATA...
			$this->request->data['Setting'][11]['value'] = (empty($this->request->data['Setting'][11]['value'])?0:$this->request->data['Setting'][11]['value']);
			$this->request->data['Setting'][14]['value'] = (empty($this->request->data['Setting'][14]['value'])?0:$this->request->data['Setting'][14]['value']);
			
			// VALIDATION BEGINSSS...
			$errMsg = "";
			$myDetails = $this->request->data['Setting'];
			foreach ($myDetails as $key => $value) 
			{
				// checking validation from view layout !!!
				if(!empty($value['validation']))
                {
                    $myValid = explode('|', $value['validation']);
                    foreach ($myValid as $key10 => $value10) 
                    {
                        $tempMsg = $this->Validation->blazeValidate($value['value'],$value10 , $value['key']);
                        $errMsg .= ( strpos($errMsg, $tempMsg) === FALSE ?$tempMsg:"");
                    }
                }
			}
            // LAST CHECK ERROR MESSAGE !!
            if(!empty($errMsg))
            {
                $this->Session->setFlash($errMsg,'failed');
                return;
            }
			// UPDATE LANGUAGE SETTING FIRST !!
			if(!empty($myDetails[15]['multilanguage']))
			{
				foreach ($myDetails[15]['optlang'] as $key => $value) 
				{
					$myDetails[15]['value'] .= chr(13).chr(10).$key;
				}
			}
			// NOW SAVE THE SETTINGS ...
			foreach ($myDetails as $key => $value)
			{
				$this->Setting->id = $key+1;
				$this->Setting->saveField('value' , $value['value']);
			}
			
			// DELETE LANGUAGE WHICH IS ALREADY NOT IN USE !!
			$this->del_lang($setting[15]['Setting']['value'], $myDetails[15]['value']);
			
			$this->Session->setFlash('Settings has been updated.','success');
			
			$this->redirect('/admin/settings');
		}
	}

	function del_lang($src , $dst)
	{
		$temp = explode(chr(13).chr(10), $src);
		foreach ($temp as $key => $value) 
		{
			if(strpos($dst, $value) === FALSE)
			{
				$this->Entry->deleteAll(array('Entry.lang_code LIKE' => substr($value, 0,2).'-%'));
			}
		}
	}

	function add() 
	{
		$this->autoRender = FALSE;
		$nameKey = strtolower(Inflector::slug($this->request->data['key']));
		$this->request->data['Setting']['key'] = "custom-".$nameKey;
		$this->request->data['Setting']['value'] = '';
		$this->Setting->create();
		$this->Setting->save($this->request->data);
		
		// prepare data for js callback...
		$this->request->data['Setting']['counter'] = $this->Setting->id - 1;
		$this->request->data['Setting']['slug_key'] = string_unslug($nameKey);
		echo json_encode($this->request->data['Setting']);
	}

	function delete($id) 
	{
		$this->autoRender = FALSE;
		$this->Setting->delete($id);
	}
}

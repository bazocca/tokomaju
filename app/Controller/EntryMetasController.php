<?php
class EntryMetasController extends AppController {
	var $name = 'EntryMetas';
	public function beforeFilter(){
        parent::beforeFilter();
    }

    function deleteTempThumbnails()
    {
    	$this->autoRender = FALSE;
    	$files = glob('img/upload/thumbnails/*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file) && strtolower(basename($file)) != 'empty')
		    unlink($file); // delete file
		}
    }
}

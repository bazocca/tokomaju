<?php
class EntriesController extends AppController {
	public $name = 'Entries';
    public $components = array('RequestHandler','Session','Validation','Auth');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Get','Paypal','Text','Rss');
	
	private $backEndFolder = '/BackEnds/';
	private $onlyActiveEntries = FALSE; // if it's in admin panel, show active/disabled, and if it's on the front, show only active pages !!
	
	public function beforeFilter(){
        parent::beforeFilter();
		$this->Auth->allow('index','get_list_entry','get_detail_entry','ajax_title_counter');
    }
	
	/**
	 * fork our target routes for entry view (pages, entry, list of entries) in Front End web
	 * @return void
	 * @public
	 **/
	function index() // front End view !!
	{
		throw new NotFoundException('Error 404 - Not Found');
	}
	
	function change_status($id, $status = NULL , $localcall = NULL)
	{
		$this->autoRender = false;
		$data = $this->Entry->findById($id);		
		$data_change = ( is_null($status) ? ($data['Entry']['status']==0?1:0)   : $status );
		$this->Entry->id = $id;
		$this->Entry->saveField('status', $data_change);

		if(empty($localcall))
		{
			if ($this->request->is('ajax'))
			{
				echo $data_change;
			}
			else
			{
				header("Location: ".$_SESSION['now']);
				exit;
			}
		}
	}
    
    function _deleteBarangGudang($goods = array())
	{
		$listBarang = $this->Entry->findAllByEntryType("barang-gudang");
		foreach ($listBarang as $key => $value) 
		{
			if($value['Entry']['title'] == $goods['Entry']['slug'])
			{
				$this->delete($value['Entry']['id'] , 'localcall');
			}
		}
	}
	
	/**
	 * delete entry
	 * @param integer $id contains id of the entry
	 * @return void
	 * @public
	 **/
	function delete($id = null, $localcall = NULL) 
	{
		$this->autoRender = FALSE;
		if (!$id) 
		{
			if(empty($localcall))
			{
				$this->Session->setFlash('Invalid id for entry', 'failed');
				header("Location: ".$_SESSION['now']);
				exit;
			}
			else
			{
				return false;
			}
		}
		
		$title = $this->meta_details(NULL , NULL , NULL , $id);        
        $statushapus = true;
        
        // Parent Type !!
		if($title['Entry']['parent_id'] > 0)
		{
			if($title['Entry']['entry_type'] == 'hutang' || $title['Entry']['entry_type'] == 'piutang')
			{
                // adjust the balance in hutang / piutang Module ...
				$state = ($title['Entry']['entry_type']=='hutang'?1:-1);
				$balance = $title;
				$query = $this->EntryMeta->find('first',array(
					"conditions" => array(
						"EntryMeta.entry_id" => $title['Entry']['parent_id'],
						"EntryMeta.key" => "form-balance"
					)
				));
                
                $balance_result = $query['EntryMeta']['value'] + $state * (empty($balance['EntryMeta']['mutasi_debet'])?0:$balance['EntryMeta']['mutasi_debet']) - $state * (empty($balance['EntryMeta']['mutasi_kredit'])?0:$balance['EntryMeta']['mutasi_kredit']);
                
				$this->EntryMeta->id = $query['EntryMeta']['id'];
				$this->EntryMeta->saveField('value' , $balance_result );
                
                // IF BALANCE IS ZERO, AND THEN PAYMENT STATUS IS COMPLETE !!
                $payment = $this->EntryMeta->find('first' , array(
                    "conditions" => array(
                        "EntryMeta.entry_id" => $title['Entry']['parent_id'],
                        "EntryMeta.key" => "form-status_bayar"
                    )
                ));
                $this->EntryMeta->id = $payment['EntryMeta']['id'];
                $this->EntryMeta->saveField('value' , ($balance_result == 0?'Lunas':'Tunggak') );
			}
			// add history of pindah-keluar dalam gudang tersebut !!
			else if($title['Entry']['entry_type'] == "barang-gudang")
			{
                $input['Entry']['entry_type'] = "pindah-keluar";
				$input['Entry']['title'] = 'automatic_'.strtolower($title['Entry']['title']);
                $input['Entry']['slug'] = $this->get_slug($input['Entry']['title']);                
				$input['Entry']['description'] = 'Pengurangan stok barang oleh administrator.';
				$input['Entry']['main_image'] = $title['EntryMeta']['stock']; // jumlah stok...
				$input['Entry']['created_by'] = $this->user['id'];
				$input['Entry']['modified_by'] = $this->user['id'];
				$input['Entry']['parent_id'] = $title['Entry']['parent_id'];
				$this->Entry->create();
				$this->Entry->save($input);
				
				// kurangi stock di master barang...
				$this->EntryMeta->add_stock_master_barang($title['Entry']['title'] , -$title['EntryMeta']['stock']);
			}
		}
		else // if this is a single / parent entry ...
		{
			if($title['Entry']['entry_type'] == "gudang")
			{
				if(!empty($title['EntryMeta']['count-barang-gudang']))
                {
                    $statushapus = false;
                    if(empty($localcall))   $this->Session->setFlash('Hapus gagal! Silahkan kosongkan barang gudang yang bersangkutan terlebih dahulu.','failed');
                }				
			}
            else if($title['Entry']['entry_type'] == "jenis-barang")
            {
                $valid = $this->_admin_default($this->Type->findBySlug('barang-dagang') ,0 , NULL , 'jenis_barang' , $title['Entry']['slug'] , NULL , NULL , NULL , NULL , 'manualset');
                
                if($valid['totalList'] > 0)
                {
                    $statushapus = false;
                    if(empty($localcall))   $this->Session->setFlash('Hapus gagal! Silahkan hapus barang dagang yang memiliki jenis bersangkutan terlebih dahulu.','failed');
                }
            }
			else if($title['Entry']['entry_type'] == "barang-dagang")
			{
				$this->_deleteBarangGudang($title);
			}
			else if($title['Entry']['entry_type'] == "resi")
			{
				$suratjalan = $this->Entry->findBySlug($title['EntryMeta']['surat_jalan'] , 'surat-jalan');
				$this->Entry->id = $suratjalan['Entry']['id'];
				$this->Entry->saveField('status', '0');
			}
		}
		
		if($statushapus)
        {
            // delete all the children !!
            $children = $this->Entry->findAllByParentId($id);
            foreach ($children as $key => $value) 
            {
                $this->EntryMeta->remove_files( $this->Type->findBySlug($value['Entry']['entry_type']) , $value );
                $this->EntryMeta->deleteAll(array('EntryMeta.entry_id' => $value['Entry']['id']));
            }
            $this->Entry->deleteAll(array('Entry.parent_id' => $id));

            // delete the entry !!
            $this->EntryMeta->remove_files( $this->Type->findBySlug($title['Entry']['entry_type']) , $title );
            $this->EntryMeta->deleteAll(array('EntryMeta.entry_id' => $id));
            $this->Entry->delete($id);

            if(empty($localcall))
            {
                $this->Session->setFlash($title['Entry']['title'].' has been deleted', 'success');
            }
        }
        
        if(empty($localcall))
        {
            header("Location: ".$_SESSION['now']);
            exit;
        }
        else
        {
            return $statushapus;
        }
	}

	/**
	* display images info may have been used or not on pop up
	* @param integer $id get media id
	* @return void
	**/	
	public function mediaused($id=NULL)
	{
		$this->autoRender = FALSE;
		if($id!=NULL)
		{	
			// check for direct media_id in Entries...
			$result = $this->Entry->find('all' , array(
				'conditions' => array(
					'Entry.main_image' => $id,
					'Entry.entry_type <>' => 'media'
				),
				'order' => array('Entry.sort_order ASC')
			));
			
			foreach ($result as $key => $value) 
			{
				echo '"' . $value['Entry']['entry_type'] . '" - ' . $value['Entry']['title'] . '
';
			}
			
			// check for image used in EntryMeta too !!
			$temp = $this->TypeMeta->findAllByInputType("image");
			foreach ($temp as $key => $value) 
			{
				$tempDetail = $this->EntryMeta->find("all" , array(
					"conditions" => array(
						"EntryMeta.key" => $value['TypeMeta']['key'],
						"EntryMeta.value" => $id
					)
				));
				foreach ($tempDetail as $key10 => $value10) 
				{
					echo '"' . $value10['Entry']['entry_type'] . '" - ' . $value10['Entry']['title'] . '
';
				}
			}
			
			// CHECK FOR HAVING CHILD IMAGE OR NOT !!
			$temp = $this->Entry->findAllByParentId($id);
			foreach ($temp as $key => $value) 
			{
				$state = 0;
				$searchEntryMeta = $this->EntryMeta->findAllByValue($value['Entry']['id']);
				foreach ($searchEntryMeta as $key10 => $value10) 
				{
					$testImage = $this->TypeMeta->find('first' , array(
						"conditions" => array(
							"TypeMeta.input_type" => "image",
							"TypeMeta.key" => $value10['EntryMeta']['key'],
							"Type.slug" => $value10['Entry']['entry_type']
						)
					));
					if(!empty($testImage))
					{
						$state = 1;
						echo '"' . $value10['Entry']['entry_type'] . '" - ' . $value10['Entry']['title'] . '
';
					}
				}
				if($state == 0)
				{
					// DELETE THIS CHILD IMAGE !!
					$this->Entry->deleteMedia($value['Entry']['id']);
				}
			}
		}
	}
	
	/**
	 * delete image from media library
	 * @param integer $id contains id of the image entry
	 * @return void
	 * @public
	 **/
	function deleteMedia($id = null)
	{
		$this->autoRender = FALSE;
		if ($id==NULL)
		{
			$this->Session->setFlash('Invalid ID Media','failed');
		}
		else 
		{
			//////////// FIND MEDIA NAME BEFORE DELETED ////////////
			$media_name = $this->Entry->findById($id);
			if($this->Entry->deleteMedia($id))
			{				
				$this->Session->setFlash('Media "'.$media_name['Entry']['title'].'" has been deleted','success');
			}
		}
		header("Location: ".$_SESSION['now']);
		exit;
	}
	
	/**
	 * target route for querying to get list of entries.
	 * @return void
	 * @public
	 **/
	function admin_index() 
	{
        // DEFINE THE ORDER...
		if(!empty($this->request->data['order_by']))
		{	
			switch ($this->request->data['order_by']) 
			{
                case 'by_order':
                    unset($_SESSION['order_by']);
                    break;
				case 'z_to_a':
					$_SESSION['order_by'] = 'title DESC';
					break;
				case 'a_to_z':
					$_SESSION['order_by'] = 'title ASC';
					break;
				case 'latest_first':
					$_SESSION['order_by'] = 'created DESC';
					break;
				case 'oldest_first':
					$_SESSION['order_by'] = 'created ASC';
					break;	
				default:
					$_SESSION['order_by'] = $this->request->data['order_by'];
					break;
			}
		}
		// END OF DEFINE THE ORDER...
		
		if($this->request->params['type'] == 'pages')
		{
			// manually set pages data !!
			$myType['Type']['name'] = 'Pages';
			$myType['Type']['slug'] = 'pages';
			$myType['Type']['parent_id'] = 0;
		}
		else
		{
			$myType = $this->Type->findBySlug($this->request->params['type']);
		}
		// if this action is going to view the CHILD list...
		if(!empty($this->request->params['entry']))
		{
			$myEntry = $this->Entry->findBySlug($this->request->params['entry']);
			if(!empty($this->request->query['type']))
			{				
				$myChildTypeSlug = $this->request->query['type'];
			}
			else 
			{
				$myChildTypeSlug = $myType['Type']['slug'];
			}
		}

		// ========== FORM SUBMIT BLUK ACTION ============
		if(!empty($this->request->data['action']))
		{
			$pecah = explode(',', $this->request->data['record']);
			
			if($this->request->data['action'] == 'active')
			{
				foreach ($pecah as $key => $value) 
				{
					$this->change_status($value, 1 , 'localcall');
				}
				$this->Session->setFlash('Your selection data status has been <strong>activated</strong> successfully.','success');
			}
			else if($this->request->data['action'] == 'disable')
			{
				foreach ($pecah as $key => $value) 
				{
					$this->change_status($value, 0 , 'localcall');
				}
				$this->Session->setFlash('Your selection data status has been <strong>disabled</strong> successfully.','success');
			}
			else if($this->request->data['action'] == 'delete')
			{
                $perfectdel = 1;
				foreach ($pecah as $key => $value) 
				{
					if(!$this->delete($value , 'localcall'))
                    {
                        $perfectdel = 0;
                    }
				}
				
                if($perfectdel)
                {
                    $this->Session->setFlash('Your selection data has been <strong>deleted</strong> successfully.','success');
                }
                else
                {
                    $this->Session->setFlash('Some of your selection data <strong>failed</strong> to be deleted.<br>Please delete them individually!','failed');
                }
			}
			else
			{
				$this->Session->setFlash('There\'s no bulk action process to be executed. Please try again.','failed');
			}
		}

		// this general action is one for all...
        if($myChildTypeSlug == 'barang-masuk')
        {
            if(empty($this->request->data['order_by']))
            {
                $_SESSION['order_by'] = 'form-tanggal asc';
            }
        }        
		else if($myChildTypeSlug=="hutang" || $myChildTypeSlug=="piutang")
		{
			$this->request->params['page'] = 0; // must be one full page !!            
            $_SESSION['order_by'] = 'form-tanggal asc';
		}
		$this->_admin_default($myType , $this->request->params['page'] , $myEntry , $this->request->query['key'] , $this->request->query['value'] , $myChildTypeSlug , $this->request->data['search_by'] , $this->request->query['popup'] , strtolower($this->request->query['lang']));
		$myTypeSlug = (empty($myChildTypeSlug)?$myType['Type']['slug']:$myChildTypeSlug);
		
		// send to each appropriate view
		$str = substr(WWW_ROOT, 0 , strlen(WWW_ROOT)-1); // buang DS trakhir...
		$str = substr($str, 0 , strripos($str, DS)+1); // buang webroot...
		$src = $str.'View'.str_replace('/', DS, $this->backEndFolder).$myTypeSlug.'.ctp';
		
		if(file_exists($src))
		{
			$this->render($this->backEndFolder.$myTypeSlug.(!empty($this->request->query['action'])?'_'.$this->request->query['action']:''));
		}
		else
		{
			$this->render('admin_default');
		}
	}

	/**
	* target route for adding new entry (stagging mode)
	* @return void
	* @public
	**/
	function index_add()
	{
		throw new NotFoundException('Error 404 - Not Found');
	}
    
    function _initBarangMasukAdd($myType = array() , $myEntry = array() , $myChildTypeSlug = NULL)
	{
		if($myType['Type']['slug'] == 'purchase-order' && $myChildTypeSlug == "barang-masuk")
		{
			if(empty($this->request->data))
			{
				$this->set('pesanan' , $this->_admin_default($myType , 0 , $myEntry , NULL , NULL , "purchase-detail"));
			}
			else
			{
                $this->Session->setFlash('Pengiriman barang terpilih sukses tiba di gudang '.$this->Entry->addBarangMasuk($this->request->data , $myEntry).'.','success');
				$this->redirect (array('action' => $myEntry['Entry']['entry_type']."/".$myEntry['Entry']['slug']."?type=barang-masuk"));
			}
		}
		else if($myType['Type']['slug'] == 'sales-order' && $myChildTypeSlug == "retur-jual")
		{
			if(empty($this->request->data))
			{
				$this->set('pesanan' , $this->_admin_default($myType , 0 , $myEntry , NULL , NULL , "sales-detail"));
			}
			else
			{
				if($this->Entry->addReturJual($this->request->data , $myEntry))
				{	
					$this->Session->setFlash('Input Success.','success');
					$this->redirect (array('action' => $myEntry['Entry']['entry_type']."/".$myEntry['Entry']['slug']."?type=retur-jual"));
				}
				else
				{
					$this->Session->setFlash('Invalid input! Please fill the required fields.','failed');
					$this->redirect (array('action' => $myEntry['Entry']['entry_type']."/".$myEntry['Entry']['slug']."/add?type=retur-jual"));
				}
			}
		}
	}
	
	/**
	* target route for adding new entry
	* @return void
	* @public
	**/
	function admin_index_add()
	{
		if($this->request->params['type'] == 'pages')
		{
			
			if($this->user['role_id'] > 1)
			{
				throw new NotFoundException('Error 404 - Not Found'); 
				return;
			}
			// manually set pages data !!
			$myType['Type']['name'] = 'Pages';			
			$myType['Type']['slug'] = 'pages';
			$myType['Type']['parent_id'] = 0;
		}
		else
		{
			$myType = $this->Type->findBySlug($this->request->params['type']);
		}
		
		// if this action is going to add CHILD list...
		if(!empty($this->request->params['entry']))
		{
			$myEntry = $this->Entry->findBySlug($this->request->params['entry']);
			if(!empty($this->request->query['type']))
			{				
				$myChildTypeSlug = $this->request->query['type'];
			}
			else 
			{
				$myChildTypeSlug = $myType['Type']['slug'];
			}
		}
		
		// main add function ...
        if($myType['Type']['slug'] == 'purchase-order' && $myChildTypeSlug == "barang-masuk" || $myType['Type']['slug'] == 'sales-order' && $myChildTypeSlug == "retur-jual")
        {				
            $this->_initBarangMasukAdd($myType , $myEntry , $myChildTypeSlug);
        }
		$this->_admin_default_add(($myType['Type']['slug']=='pages'?NULL:$myType) , $myEntry , $myChildTypeSlug);
		
		$myTemplate = ($myType['Type']['slug']=='pages'?$myEntry['Entry']['slug']:(empty($myChildTypeSlug)?$myType['Type']['slug']:$myChildTypeSlug)).'_add';
		
		// send to each appropriate view
		$str = substr(WWW_ROOT, 0 , strlen(WWW_ROOT)-1); // buang DS trakhir...
		$str = substr($str, 0 , strripos($str, DS)+1); // buang webroot...
		$src = $str.'View'.str_replace('/', DS, $this->backEndFolder).$myTemplate.'.ctp';
		
		// add / edit must use the same view .ctp, but with different action !!
		if(file_exists($src))
		{
			$this->render($this->backEndFolder.$myTemplate);
		}
		else
		{
			$this->render('admin_default_add');
		}
	}

	/**
	* target route for editing certain entry based on passed url parameter (stagging mode)
	* @return void
	* @public
	**/
	function index_edit()
	{
		throw new NotFoundException('Error 404 - Not Found');
	}
	
	/**
	* target route for editing certain entry based on passed url parameter
	* @return void
	* @public
	**/
	function admin_index_edit()
	{	
		if($this->request->params['type'] == 'pages')
		{
			// manually set pages data !!
			$myType['Type']['name'] = 'Pages';
			$myType['Type']['slug'] = 'pages';
			$myType['Type']['parent_id'] = 0;
		}
		else
		{
			$myType = $this->Type->findBySlug($this->request->params['type']);
		}		
		$this->Entry->recursive = 2;
		$myEntry = $this->meta_details($this->request->params['entry']);
		$this->Entry->recursive = 1;
		
		// if this action is going to edit CHILD list...
		if(!empty($this->request->params['entry_parent']))
		{	
			$myParentEntry = $this->Entry->findBySlug($this->request->params['entry_parent']);
			if(!empty($this->request->query['type']))
			{				
				$myChildTypeSlug = $this->request->query['type'];
			}
			else 
			{
				$myChildTypeSlug = $myType['Type']['slug'];
			}
		}
		
		// main edit function ...
		$this->_admin_default_edit(($myType['Type']['slug']=='pages'?NULL:$myType) , $myEntry , $myParentEntry , $myChildTypeSlug , strtolower($this->request->query['lang']));
		
		$myTemplate = ($myType['Type']['slug']=='pages'?$myEntry['Entry']['slug']:(empty($myChildTypeSlug)?$myType['Type']['slug']:$myChildTypeSlug)).'_add';		
		// send to each appropriate view
		$str = substr(WWW_ROOT, 0 , strlen(WWW_ROOT)-1); // buang DS trakhir...
		$str = substr($str, 0 , strripos($str, DS)+1); // buang webroot...
		$src = $str.'View'.str_replace('/', DS, $this->backEndFolder).$myTemplate.'.ctp';
		
		// add / edit must use the same view .ctp, but with different action !!
		if(file_exists($src))
		{
			$this->render($this->backEndFolder.$myTemplate);
		}
		else
		{
			$this->render('admin_default_add');
		}
	}
    
    /**
	* target route for view something of certain entry based on passed url parameter
	* @return void
	* @public
	**/
    function admin_index_view()
    {
        $data = array();        
        $data['myType'] = $this->Type->findBySlug($this->request->params['type']);
        $data['myEntry'] = $this->meta_details($this->request->params['entry'] , $this->request->params['type']);
        $search = $this->Entry->find("all" , array(
			"conditions" => array(
				"Entry.entry_type" => "barang-gudang",
				"Entry.title" => $this->request->params['entry']
			),
			"order" => array("ParentEntry.title")
		));
		$total = 0;
		foreach ($search as $key => $value) 
		{
			if($value['EntryMeta'][0]['value'] > 0) // jumlah stock !!
			{
                $search[$key]['ParentEntry'] = $this->meta_details($value['ParentEntry']['slug'] , $value['ParentEntry']['entry_type']);
				$total += $search[$key]['EntryMeta']['stock'] = $value['EntryMeta'][0]['value'];
			}
			else
			{
				unset($search[$key]);
			}
		}		
		$data['myList'] = $search;
		$data['total'] = $total;
		
		// SET ALL PARAMETER TO THE RENDER FILE !!
		$this->setTitle("View ".$data['myEntry']['Entry']['title']);
		$this->set("data" , $data);
        if($this->request->params['type'] == 'barang-dagang')
        {
            $this->render($this->backEndFolder."view-barang");
        }
        else
        {
            $this->render('admin_default_add');
        }
    }

	/**
	* get a bunch of entries based on parameter given
	* @param string $myTypeSlug contains slug database type
	* @param string $myEntrySlug[optional] contains slug of the parent Entry (used if want to search certain child Entry)
	* @param string $myChildTypeSlug[optional] contains slug of child type database (used if want to search certain child Entry)
	* @return void echoing json result
	* @public
	**/
	function get_list_entry($myTypeSlug , $myEntrySlug = NULL , $myChildTypeSlug = NULL)
	{
		$this->autoRender = FALSE;
		if($myTypeSlug == 'pages')
		{
			// manually set pages data !!
			$myType['Type']['name'] = 'Pages';
			
			$myType['Type']['slug'] = 'pages';
			$myType['Type']['parent_id'] = 0;
		}
		else
		{
			$myType = $this->Type->findBySlug($myTypeSlug);
		}
		$myEntry = (empty($myEntrySlug)?NULL:$this->meta_details($myEntrySlug));
		
		$this->onlyActiveEntries = TRUE;
		$json = $this->_admin_default($myType , 0 , $myEntry , NULL , NULL , $myChildTypeSlug);
		$this->onlyActiveEntries = FALSE;
		
		echo json_encode($json);
	}
	
	/**
	* get specific entry from entry lists based on entry id 
	* @param integer $myEntryId contains id of the entry
	* @return void echoing json result
	* @public
	**/
	function get_detail_entry($myEntryId)
	{
		$this->autoRender = FALSE;
		$myEntry = $this->Entry->findById($myEntryId);
		
		// if this is a child Entry...
		if($myEntry['Entry']['parent_id'] > 0)
		{
			$myParentEntry = $this->Entry->findById($myEntry['Entry']['parent_id']);			
			$myType = $this->Type->findBySlug($myParentEntry['Entry']['entry_type']); // PARENT TYPE...
			
			$myChildTypeSlug = $myEntry['Entry']['entry_type'];
		}
		else // if this is a parent Entry ...
		{
			$myType = $this->Type->findBySlug($myEntry['Entry']['entry_type']);
		}
		
		$tempdata = array();
		swap_value($tempdata, $this->request->data);
		$json = $this->_admin_default_edit($myType , $myEntry , $myParentEntry , $myChildTypeSlug);
		swap_value($tempdata, $this->request->data);
		
		echo json_encode($json);
	}
    
    public function _productExisted()
	{
		$subject = (substr($this->request->query['invoice'], 0,3)=="sal"?"sales":"purchase");
		$metaDetails = $this->_admin_default($this->Type->findBySlug($subject."-order") , 0, $this->meta_details($this->request->query['invoice'] , $subject."-order") , NULL , NULL , $subject."-detail",NULL , NULL , NULL , 'manualset');
        
		$pesanan = array();
		foreach ($metaDetails['myList'] as $key => $value) 
		{
			array_push($pesanan , $value['Entry']['title']);
		}
		return $pesanan;
	}
    
    public function _bargudExisted()
    {
        $metaDetails = $this->_admin_default($this->Type->findBySlug("barang-gudang") , 0, NULL , NULL , NULL , NULL ,NULL , NULL , NULL , 'manualset');
        
		$gudstock = array();
		foreach ($metaDetails['myList'] as $key => $value) 
		{
            if($value['Entry']['title'] == $this->request->query['barang-dagang'] && !empty($value['EntryMeta']['stock']) )
            {
                // simpan stock barang terpilih berdasarkan gudang !!
                $gudstock[ $value['ParentEntry']['slug'] ] = $value['EntryMeta']['stock'];
            }
		}
		return $gudstock;
    }
	
	/**
	* querying to get a bunch of entries based on parameter given (core function)
	* @param array $myType contains record query result of database type
	* @param integer $paging[optional] contains selected page of lists you want to retrieve
	* @param array $myEntry[optional] contains record query result of the parent Entry (used if want to search certain child Entry)
	* @param string $myMetaKey[optional] contains specific key that entries must have
	* @param string $myMetaValue[optional] contains specific value from certain key that entries must have
	* @param string $myChildTypeSlug[optional] contains slug of child type database (used if want to search certain child Entry)
	* @param string $searchMe[optional] contains search string that existed in bunch of entries requested
	* @param string $popup[optional] contains how this entry is representated
	* @param string $lang[optional] contains language of the entries that want to be retrieved
	* @param boolean $manualset[optional] set TRUE if you want set data variable to view file OUT OF this function, otherwise set FALSE
	* @return array $data certain bunch of entries you'd requested
	* @public
	**/
	public function _admin_default($myType = array(),$paging = NULL , $myEntry = array() , $myMetaKey = NULL , $myMetaValue = NULL , $myChildTypeSlug = NULL , $searchMe = NULL , $popup = NULL , $lang = NULL , $manualset = NULL)
	{
		if(is_null($paging))
		{
			$paging = 1;
		}
		if(!empty($popup) || $this->request->is('ajax'))
		{
			$this->layout = 'ajax';
			$data['stream'] = (isset($this->request->query['stream'])?$this->request->query['stream']:NULL);
		}	
		if ($this->request->is('ajax') && empty($popup) || $popup == "ajax" || !empty($searchMe)) 
		{	
			$data['isAjax'] = 1;
			if($searchMe != NULL || !empty($lang))
			{
				$data['search'] = "yes";
			}			
			if($searchMe != NULL)
			{
				$searchMe = trim($searchMe);
				if(empty($searchMe))
				{
					unset($_SESSION['searchMe']);
				}
				else
				{
					$_SESSION['searchMe'] = $searchMe;
				}
			}
			$_SESSION['lang'] = strtolower(empty($lang)?(empty($_SESSION['lang'])||empty($this->request->params['admin'])?substr($this->mySetting['language'][0], 0,2):$_SESSION['lang']):$lang);
		} 
		else 
		{
			$data['isAjax'] = 0;
			unset($_SESSION['searchMe']);
			$_SESSION['lang'] = strtolower(empty($lang)?substr($this->mySetting['language'][0], 0,2):$lang);
		}
		$data['myType'] = $myType;
		$data['paging'] = $paging;
		$data['popup'] = $popup;
		if(!empty($myEntry))
		{			
			$data['myEntry'] = $myEntry;
			$myChildType = $this->Type->findBySlug($myChildTypeSlug);
			$data['myChildType'] = $myChildType;
		}
		if($this->mySetting['table_view']=='complex')
		{
			// GENERATE TYPEMETA AGAIN WITH SORT ORDER !!
			$metaOrder = $this->TypeMeta->find('all' , array(
				'conditions' => array(
					'TypeMeta.type_id' => (empty($myEntry)?$myType['Type']['id']:$myChildType['Type']['id'])
				),
				'order' => array('TypeMeta.id ASC')
			));
            if(empty($myEntry))
			{
				$data['myType']['TypeMeta'] = $metaOrder;
			}
			else
			{
				$data['myChildType']['TypeMeta'] = $metaOrder;
			}
            
            // $_SESSION['order_by'] Validation !!
            if(substr($_SESSION['order_by'] , 0 , 5) == 'form-')
            {
                $innerFieldMeta = FALSE;
                foreach($metaOrder as $key => $value)
                {
                    if(stripos($_SESSION['order_by'] , $value['TypeMeta']['key'] ) !== FALSE)
                    {
                        $innerFieldMeta = $value['TypeMeta']['input_type'];
                        break;
                    }                    
                }
                if(!$innerFieldMeta)
                {
                    unset($_SESSION['order_by']);
                }
            }
		}

		// set page title
		$this->setTitle(empty($myEntry)?$myType['Type']['name']:$myEntry['Entry']['title']);
		
		// set paging session...
		$countPage = $this->countListPerPage;
		if(!empty($paging))
		{
			if(empty($this->request->params['admin'])) // front-end
			{
				foreach ((empty($myChildType)?$myType['TypeMeta']:$myChildType['TypeMeta']) as $key => $value) 
				{
					if($value['key'] == 'pagination')
					{
						$countPage = $value['value'];
						break;
					}
				}
			}
			else // back-end
			{
				if($myType['Type']['slug']=='media')
				{
					$countPage = $this->mediaPerPage;
                    unset($_SESSION['order_by']);
				}
			}
		}
		
		// our list conditions... ----------------------------------------------////
		$joinEntryMeta = false;
		if(empty($myEntry))
		{
			$options['conditions'] = array('Entry.entry_type' => $myType['Type']['slug']);
            if($myType['Type']['parent_id'] <= 0)
			{
				$options['conditions']['Entry.parent_id'] = 0;
			}
		}
		else
		{
			$options['conditions'] = array(
				'Entry.entry_type' => $myChildTypeSlug,
				'Entry.parent_id' => $myEntry['Entry']['id']
			);
		}

		if($this->onlyActiveEntries)
		{
			$options['conditions']['Entry.status'] = 1;
		}

		if($myType['Type']['slug'] != 'media')
		{
			$options['conditions']['Entry.lang_code LIKE'] = $_SESSION['lang'].'-%';
			$data['language'] = $_SESSION['lang'];
		}

		if(!empty($myMetaKey) && !empty($myMetaValue))
		{
			$joinEntryMeta = true;
			$options['conditions']['SUBSTR(EntryMeta.key , 6)'] = $myMetaKey;
			$options['conditions']['REPLACE(REPLACE(EntryMeta.value , "-" , "_"),"_"," ") LIKE'] = '%'.string_unslug($myMetaValue).'%';
		}

		// ========================================= >>
		// NEW FUNCTION JOIN !!
		// ========================================= >>
		if(!empty($_SESSION['searchMe']))
		{	
			if(empty($options['conditions']['OR']))
			{
				$options['conditions']['OR'] = array();
			}
			array_push($options['conditions']['OR'] , array('Entry.title LIKE' => '%'.$_SESSION['searchMe'].'%') );
			array_push($options['conditions']['OR'] , array('Entry.description LIKE' => '%'.$_SESSION['searchMe'].'%') );
			array_push($options['conditions']['OR'] , array('ParentEntry.title LIKE' => '%'.$_SESSION['searchMe'].'%') );
			if($this->mySetting['table_view']=='complex')
			{
				$joinEntryMeta = true;
				array_push($options['conditions']['OR'] , array('REPLACE(REPLACE(EntryMeta.value , "-" , "_"),"_"," ") LIKE' => '%'.string_unslug($_SESSION['searchMe']).'%') );
			}
		}

		if($joinEntryMeta)
		{
			$options['joins'] = array(array(
				'table' => 'entry_metas',
	            'alias' => 'EntryMeta',
	            'type' => 'LEFT',
	            'conditions' => array(
	                'Entry.id = EntryMeta.entry_id'
	            )
			));
			$options['group'] = array('Entry.id');
		}

		// ========================================= >>
		// FIND LAST MODIFIED !!
		// ========================================= >>
		$tempOpt = $options;
		$tempOpt['order'] = array('Entry.modified DESC');
		$lastModified = $this->Entry->find('first' , $tempOpt);
		$data['lastModified'] = $lastModified;		
		
		// ================================================================ >>
		// check for description or image is used for this entry or not ??
		// ================================================================ >>
		$tempOpt = $options;
		$tempOpt['conditions']['LENGTH(Entry.description) >'] = 0;
		$checkSQL = $this->Entry->find('first' , $tempOpt);
		$data['descriptionUsed'] = (empty($checkSQL)?0:1);
		
		$tempOpt = $options;
		$tempOpt['conditions']['Entry.main_image >'] = 0;
		$checkSQL = $this->Entry->find('first' , $tempOpt);		
		$data['imageUsed'] = (empty($checkSQL)?0:1);

		// ========================================= >>
		// EXECUTE MAIN QUERY !!
		// ========================================= >>
		$options['order'] = array('Entry.'.(isset($innerFieldMeta)||empty($_SESSION['order_by'])||empty($this->request->params['admin'])?'sort_order ASC':$_SESSION['order_by']));
        
        // untuk filter tanggal (misalnya di history barang masuk dan keluar di module pindah gudang)
		if(!empty($this->request->data['date_by']))
		{
			$temp_date_by = strtotime($this->request->data['date_by']);
			if($temp_date_by !== FALSE)
			{
				$options['conditions']['Entry.modified LIKE'] = date('Y-m-d' , $temp_date_by ).'%';
			}
		}
        
		$mysql = $this->Entry->find('all' ,$options);
        
        // PEMILIHAN BARANG DAGANG DI SURAT JALAN FORM !!
        $pesanan = ($myType['Type']['slug']=="barang-dagang" && !empty($this->request->query['invoice'])?$this->_productExisted():"");
        
        // PEMILIHAN GUDANG YG MASIH MEMPUNYAI STOCK BARANG DAGANG TERTENTU !!
        $gudstock = ($myType['Type']['slug']=="gudang" && !empty($this->request->query['barang-dagang']) ?$this->_bargudExisted():"");
        
        // MODIFY OUR ENTRYMETA FIRST !!		
		foreach ($mysql as $key => $value) 
		{
			$mysql[$key] = $value = breakEntryMetas($value);
			// ----------------------------------------- >>>
            // ADDITIONAL FILTERING METHOD !!
            // ----------------------------------------- >>>
            if(!empty($myMetaKey) && empty($myMetaValue) && !empty($value['EntryMeta'][$myMetaKey]))
            {
                unset($mysql[$key]);
                continue;
            }
            
            // more validation check !!
            if($myType['Type']['slug']=="barang-dagang")
            {
                // PEMILIHAN BARANG DAGANG DI SURAT JALAN FORM !!
                if($this->request->query['caller']=="surat-jalan" && empty($value['EntryMeta']['stock']) || !empty($pesanan) && !in_array($value['Entry']['slug'], $pesanan) )
                {
                    unset($mysql[$key]);
                    continue;
                }
            }
            else if($myType['Type']['slug'] == "gudang")
            {
                if( !empty($this->request->query['barang-dagang']) )
                {
                    if(!empty($gudstock[ $value['Entry']['slug'] ]))
                    {
                        $mysql[$key]['EntryMeta']['jumlah-stok'] = $gudstock[$value['Entry']['slug']];
                    }
                    else
                    {
                        unset($mysql[$key]);
                        continue;
                    }
                }
            }
			// ----------------------------------------- >>>
            // END OF ADDITIONAL FILTERING METHOD !!
            // ----------------------------------------- >>>
		}
		$mysql = array_values($mysql);
		
		// Final Sort based on certain criteria !!
        if($innerFieldMeta)
        {
            $explodeSorting = explode(' ', $_SESSION['order_by']);
            $mysql = orderby_metavalue( $mysql , 'EntryMeta', substr($explodeSorting[0] , 5) , $explodeSorting[1] , $innerFieldMeta );
        }
        
		// SECOND FILTER GO NOW !!!
		$offset = ($paging==0? 0 : ($paging-1) * $countPage);
		$endset = $offset + $countPage;				
		$data['totalList'] = count($mysql);
		$data['myList'] = array();
		for($key = $offset ; !empty($mysql[$key]) ; ++$key)
		{
			if(!($key < $endset || $paging==0))
			{
				break;
			}
			array_push($data['myList'] , $mysql[$key]);
		}

		// set New countPage
		$newCountPage = ceil($data['totalList'] * 1.0 / $countPage);
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
		
		// for image input type reason...
		$data['myImageTypeList'] = $this->EntryMeta->embedded_img_meta('type');
		
		// IS ALLOWING ORDER CHANGE OR NOT ??
		$data['isOrderChange'] = 0;
		
		// --------------------------------------------- LANGUAGE OPTION LINK ------------------------------------------ //
		if(!empty($myEntry))
		{
			$temp100 = $this->Entry->find('all' , array(
				'conditions' => array(
					'Entry.lang_code LIKE' => '%-'.substr($myEntry['Entry']['lang_code'], 3)
				)
			));
			foreach ($temp100 as $key => $value) 
			{
				$parent_language[ substr($value['Entry']['lang_code'], 0,2) ] = $value['Entry']['slug'];
			}
			$data['parent_language'] = $parent_language;
		}
		// ------------------------------------------ END OF LANGUAGE OPTION LINK -------------------------------------- //

		if(empty($manualset))
		{
			$this->set('data' , $data);
		}
		
		return $data;
	}

	/**
	* add new entry 
	* @param array $myType contains record query result of database type
	* @param array $myEntry[optional] contains record query result of the selected Entry
	* @param string $myChildTypeSlug[optional] contains slug of child type database (used if want to search certain child Entry)
	* @return void
	* @public
	**/
	function _admin_default_add($myType = array() , $myEntry = array() , $myChildTypeSlug = NULL , $lang_code = NULL , $prefield_slug = NULL)
	{
		$myChildType = $this->Type->findBySlug($myChildTypeSlug);
		$data['myType'] = $myType;
		$data['myParentEntry'] = $myEntry;
		$data['myChildType'] = $myChildType;
        
        // SEARCH IF GALLERY MODE IS TURN ON / OFF ...
        $myAutomaticValidation = (empty($myEntry)?$myType['TypeMeta']:$myChildType['TypeMeta']);
        $data['gallery'] = false;
        foreach ($myAutomaticValidation as $key => $value) 
        {
            if($value['key'] == 'gallery')
            {
                if($value['value'] == 'enable')
                {
                    $data['gallery'] = true;
                }
                break;
            }
        }
        
		if(!empty($myType))
		{
			// GENERATE TYPEMETA AGAIN WITH SORT ORDER !!
			$metaOrder = $this->TypeMeta->find('all' , array(
				'conditions' => array(
					'TypeMeta.type_id' => (empty($myEntry)?$myType['Type']['id']:$myChildType['Type']['id'])
				),
				'order' => array('TypeMeta.id ASC')
			));
			if(empty($myEntry))
			{
				$data['myType']['TypeMeta'] = $metaOrder;
			}
			else
			{
				$data['myChildType']['TypeMeta'] = $metaOrder;
			}
		}
		// for image input type reason...
		$data['myImageTypeList'] = $this->EntryMeta->embedded_img_meta('type');
		// --------------------------------------------- LANGUAGE OPTION LINK ------------------------------------------ //
		if(!empty($myEntry))
		{
			$temp100 = $this->Entry->find('all' , array(
				'conditions' => array(
					'Entry.lang_code LIKE' => '%-'.substr($myEntry['Entry']['lang_code'], 3)
				)
			));
			foreach ($temp100 as $key => $value) 
			{
				$parent_language[ substr($value['Entry']['lang_code'], 0,2) ] = $value['Entry']['slug'];
			}
			$data['parent_language'] = $parent_language;
		}
		$data['lang'] = strtolower(empty($myEntry)?(empty($_SESSION['lang'])? substr($this->mySetting['language'][0], 0,2):$_SESSION['lang']):substr($myEntry['Entry']['lang_code'], 0,2));
		// ------------------------------------------ END OF LANGUAGE OPTION LINK -------------------------------------- //
		
		if(empty($prefield_slug))
		{
			$this->setTitle('Add New '.(empty($myEntry)?(empty($myType)?'Pages':$myType['Type']['name']):$myEntry['Entry']['title']));
			$this->set('data' , $data);
		}
		
		// if form submit is taken...
		if (!empty($this->request->data)) 
		{
//            dpr($this->request->data);
//            exit;
            
			if(empty($lang_code) && !empty($myEntry) && substr($myEntry['Entry']['lang_code'], 0,2) != $this->request->data['language'])
			{
				$myEntry = $this->Entry->findByLangCode($this->request->data['language'].substr($myEntry['Entry']['lang_code'], 2));
			}	
			// PREPARE DATA !!	
			$this->request->data['Entry']['title'] = $this->request->data['Entry'][0]['value'];
			$this->request->data['Entry']['description'] = $this->request->data['Entry'][1]['value'];
			$this->request->data['Entry']['main_image'] = $this->request->data['Entry'][2]['value'];
			if(isset($this->request->data['Entry'][3]['value']))
			{
				$this->request->data['Entry']['status'] = $this->request->data['Entry'][3]['value'];
			}
			
			// set the type of this entry...
			$this->request->data['Entry']['entry_type'] = (empty($myEntry)?(empty($myType)?'pages':$myType['Type']['slug']):$myChildType['Type']['slug']);
			// generate slug from title...			
			$this->request->data['Entry']['slug'] = $this->get_slug($this->request->data['Entry']['title']);
			// write my creator...			
			$this->request->data['Entry']['created_by'] = $this->user['id'];
			$this->request->data['Entry']['modified_by'] = $this->user['id'];
			// write time created manually !!
			$nowDate = $this->getNowDate();
			$this->request->data['Entry']['created'] = $nowDate;
			$this->request->data['Entry']['modified'] = $nowDate;
			// set parent_id
			$this->request->data['Entry']['parent_id'] = (empty($myEntry)?0:$myEntry['Entry']['id']);
			$this->request->data['Entry']['lang_code'] = strtolower(empty($lang_code)?$this->request->data['language']:$lang_code);
            if($myType['Type']['slug'] == 'surat-jalan')
			{
				$this->request->data['Entry']['status'] = 0; // pending, whereas resi number still not be created.
			}
			
			// PREPARE FOR ADDITIONAL LINK OPTIONS !!
			$myChildTypeLink = (!empty($myEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:'');
			$myTranslation = (empty($myChildTypeLink)?'?':'&').'lang='.substr($this->request->data['Entry']['lang_code'], 0,2);
			
			// now for validation !!
			$this->Entry->set($this->request->data);
			if($this->Entry->validates())
			{
			    // --------------------------------- NOW for add / validate the details of this entry !!!
				$myDetails = $this->request->data['EntryMeta'];
				$errMsg = "";
                
				foreach ($myDetails as $key => $value) 
				{
					if($value['input_type']=='file')				{$value['value'] = $_FILES[$value['key']]['name'];}
					else if($value['input_type']=='multibrowse')	{$value['value'] = array_unique(array_filter($value['value']));}

					// firstly DO checking validation from view layout !!!
					$myValid = explode('|', $value['validation']);
					foreach ($myValid as $key10 => $value10) 
					{
						$tempMsg = $this->Validation->blazeValidate( $value['value'] ,$value10 , $value['key']);
						$errMsg .= ( strpos($errMsg, $tempMsg) === FALSE ?$tempMsg:"");
					}
					// secondly DO checking validation from database !!!
					$state = 0;					
					foreach ($myAutomaticValidation as $key2 => $value2) // check for validation for each attribute key... 
					{
						if($value['key'] == $value2['key']) // if find the same key...
						{
							$state = 1;
							$myValid = explode('|' , $value2['validation']);
							foreach ($myValid as $key3 => $value3) 
							{
								$tempMsg = $this->Validation->blazeValidate($value['value'],$value3 , $value['key']);
								$errMsg .= ( strpos($errMsg, $tempMsg) === FALSE ?$tempMsg:"");
							}
							break;
						}
					}
					
					// if attribute key doesn't exist in type metas, therefore it must be added to type metas respectively...
					if($state == 0 && !empty($value['input_type']) && empty($lang_code))
					{
						$this->request->data['TypeMeta'] = $value;
						$this->request->data['TypeMeta']['type_id'] = (empty($myEntry)?$myType['Type']['id']:$myChildType['Type']['id']);
						$this->request->data['TypeMeta']['value'] = $value['optionlist'];
						$this->TypeMeta->create();
						$this->TypeMeta->save($this->request->data);
					}
				}
				// LAST CHECK ERROR MESSAGE !!
				if(!empty($errMsg))
				{
					$this->Session->setFlash($errMsg,'failed');
					return;
				}
                // -------------------------------- //
				// --------- CUSTOM CHECK --------- //
				// -------------------------------- //
				if($myChildTypeSlug == "barang-gudang")
				{
                    $cekbarang = $this->Entry->find('first',array(
						'conditions' => array(
							'Entry.title' => $this->request->data['Entry']['title'],
							'Entry.parent_id' => $myEntry['Entry']['id'],
                            'Entry.entry_type' => $myChildTypeSlug
						)
					));
					if(!empty($cekbarang))
					{
						$this->Session->setFlash('Barang sudah ada di gudang ini. Silahkan masukan barang lainnya.','failed');
						return;
					}
				}
				else if($myType['Type']['slug'] == "surat-jalan")
				{
					if(empty($this->request->data['Entry']['id-customer']) && empty($this->request->data['caller']))
					{
						$this->Session->setFlash('Please define who is the customer.','failed');
						return;
					}
					if(!$this->cekSuratJalan())
					{
						$this->Session->setFlash('Ditemukan adanya kelebihan pengiriman barang. Silahkan ulangi.','failed');
						return;
					}
				}				
				// ------------------------------------- end of entry details...
				$this->Entry->create();
				$this->Entry->save($this->request->data);
                $newEntryId = $this->Entry->id;
                if($data['gallery'])
                {   
                    foreach ($this->request->data['Entry']['image'] as $key => $value) 
                    {
                        $myImage = $this->Entry->findById($value);
                        
                        $input = array();
                        $input['Entry']['entry_type'] = $this->request->data['Entry']['entry_type'];
                        $input['Entry']['title'] = $myImage['Entry']['title'];
                        $input['Entry']['slug'] = $this->get_slug($myImage['Entry']['title']);
                        $input['Entry']['main_image'] = $value;
                        $input['Entry']['parent_id'] = $newEntryId;
                        $input['Entry']['created_by'] = $this->user['id'];
                        $input['Entry']['modified_by'] = $this->user['id'];
                        $this->Entry->create();
                        $this->Entry->save($input);
                    }
                }

                if(!empty($this->request->data['Entry']['fieldimage']))
                {
                	foreach ($this->request->data['Entry']['fieldimage'] as $fieldkey => $fieldvalue) 
                	{
                		foreach ($fieldvalue as $key => $value) 
                		{
                			$myImage = $this->Entry->findById($value);

                			$input = array();                        
	                        $input['Entry']['entry_type'] = $fieldkey;
	                        $input['Entry']['title'] = $myImage['Entry']['title'];
	                        $input['Entry']['slug'] = $this->get_slug($myImage['Entry']['title']);
	                        $input['Entry']['main_image'] = $value;
	                        $input['Entry']['parent_id'] = $newEntryId;
	                        $input['Entry']['created_by'] = $this->user['id'];
	                        $input['Entry']['modified_by'] = $this->user['id'];
	                        $this->Entry->create();
	                        $this->Entry->save($input);
                		}
                	}
                }

				$this->request->data['EntryMeta']['entry_id'] = $newEntryId;
				foreach ($myDetails as $key => $value)
				{	
					if(!empty($value['value']) && substr($value['key'], 0,5) == 'form-')
					{
						$this->request->data['EntryMeta']['key'] = $value['key'];
						if($value['input_type'] == 'image' && isset($value['w']) && isset($value['h']))
						{
							$this->request->data['EntryMeta']['value'] = $this->Entry->makeChildImageEntry($value,(empty($myEntry)?$myType:$myChildType));
						}
						else if($value['input_type'] == 'multibrowse')
						{
							$this->request->data['EntryMeta']['value'] = implode('|', array_unique(array_filter($value['value'])));
						}
						else
						{
							$this->request->data['EntryMeta']['value'] = ($value['input_type'] == 'checkbox'?implode("|",$value['value']):$value['value']);
						}
						$this->EntryMeta->create();
						$this->EntryMeta->save($this->request->data);
					}
				}
				
				// Upload File !!
				if(isset($_FILES))
				{
					foreach ($_FILES as $key => $value) 
					{
						if(!empty($value['name']))
						{
							$value['name'] = getValidFileName($value['name']);
							uploadFile($value);
							// Save data to EntryMeta !!
							$this->request->data['EntryMeta']['key'] = $key;
							$this->request->data['EntryMeta']['value'] = $value['name'];
							$this->EntryMeta->create();
							$this->EntryMeta->save($this->request->data);
						}
					}
				}
                
                // ---------- ADD SHIPPING ID OR SOMETHING ELSE RELATED !! ------------- //
				$this->_add_update_id_meta($myType['Type']['slug'] , $myChildTypeSlug , $myEntry);

				// NOW finally setFlash ^^
				$this->Session->setFlash($this->request->data['Entry']['title'].' has been added.','success');
				if($this->request->params['admin']==1)
				{
					$newEntrySlug = $this->Entry->checkRemainingLang($newEntryId , $this->mySetting);
					if($newEntrySlug)
					{
						$this->redirect(array('action' => (empty($myType)?'pages':$myType['Type']['slug']) , 'edit' , $newEntrySlug ));
					}
					else
					{
						$this->redirect(array('action' => (empty($myType)?'pages':$myType['Type']['slug']).(empty($myEntry)?'':'/'.$myEntry['Entry']['slug']).$myChildTypeLink.$myTranslation));
					}
				}
				else
				{
					$this->redirect( redirectSessionNow($_SESSION['now']) );
				}
			}
			else 
			{
				$this->_setFlashInvalidFields($this->Entry->invalidFields());
			}
		}
	}
    
    function _add_update_id_meta($myTypeSlug , $myChildTypeSlug = NULL , $myParentEntry = array() , $myEntry = array())
	{
		// $this->request->data['EntryMeta']['entry_id'] => not needed to be set, coz it's already set in parent function !!
        $this->request->data = breakEntryMetas($this->request->data);
        $this->request->data['Entry']['id'] = $this->request->data['EntryMeta']['entry_id'];
        
        if($myTypeSlug == "purchase-order" && empty($myChildTypeSlug) && empty($myEntry)) // ADD ONLY!!
        {
            $this->Entry->addPurchaseDetails($this->request->data);
        }
        else if( $myTypeSlug == "sales-order" && empty($myChildTypeSlug) && empty($myEntry) ) // ADD ONLY!!
        {
            $this->Entry->addSaleDetails($this->request->data);
        }
		else if( empty($myEntry) && ($myChildTypeSlug == "hutang" || $myChildTypeSlug == "piutang") ) // ADD ONLY!!
		{	
			// UPDATE BALANCE !!
			$balance = $this->EntryMeta->find('first',array(
				"conditions" => array(
					"EntryMeta.entry_id" => $myParentEntry['Entry']['id'],
					"EntryMeta.key" => "form-balance"
				)
			));
			$this->EntryMeta->id = $balance['EntryMeta']['id'];
			$this->EntryMeta->saveField('value' , $this->request->data['ParentEntry']['balance']);
            
            // IF BALANCE IS ZERO, AND THEN PAYMENT STATUS IS COMPLETE !!
            $payment = $this->EntryMeta->find('first' , array(
                "conditions" => array(
                    "EntryMeta.entry_id" => $myParentEntry['Entry']['id'],
                    "EntryMeta.key" => "form-status_bayar"
                )
            ));
            $this->EntryMeta->id = $payment['EntryMeta']['id'];
            $this->EntryMeta->saveField('value' , ($this->request->data['ParentEntry']['balance'] == 0?'Lunas':'Tunggak') );
		}
		else if($myChildTypeSlug == "barang-gudang")
		{
            if(empty($myEntry)) // ADD MODE !!
            {
                $input['Entry']['entry_type'] = "pindah-masuk";
                $input['Entry']['title'] = 'automatic_'.strtolower($this->request->data['Entry'][0]['value']);
                $input['Entry']['slug'] = $this->get_slug($input['Entry']['title']);            
                $input['Entry']['description'] = 'Penambahan stok barang oleh administrator.';
                $input['Entry']['main_image'] = $this->request->data['EntryMeta'][3]['value']; // jumlah stok...			
                $input['Entry']['created_by'] = $this->user['id'];
                $input['Entry']['modified_by'] = $this->user['id'];
                $input['Entry']['parent_id'] = $myParentEntry['Entry']['id'];
                $this->Entry->create();
                $this->Entry->save($input);

                // tambah total stock di master barang !!
                $this->EntryMeta->add_stock_master_barang($this->request->data['Entry'][0]['value'] , +$this->request->data['EntryMeta'][3]['value']);
            }
            else // EDIT MODE !!
            {
                // TAMBAH STOCK !!
                if($myEntry['EntryMeta']['stock'] < $this->request->data['EntryMeta'][3]['value'])
                {
                    $diffstock = $this->request->data['EntryMeta'][3]['value'] - $myEntry['EntryMeta']['stock'];
                    $input['Entry']['entry_type'] = "pindah-masuk";
                    $input['Entry']['title'] = 'automatic_'.strtolower($this->request->data['Entry'][0]['value']);
                    $input['Entry']['slug'] = $this->get_slug($input['Entry']['title']);            
                    $input['Entry']['description'] = 'Penambahan stok barang oleh administrator.';
                    $input['Entry']['main_image'] = $diffstock; // jumlah stok...
                    $input['Entry']['created_by'] = $this->user['id'];
                    $input['Entry']['modified_by'] = $this->user['id'];
                    $input['Entry']['parent_id'] = $myParentEntry['Entry']['id'];
                    $this->Entry->create();
                    $this->Entry->save($input);

                    // tambah total stock di master barang !!
                    $this->EntryMeta->add_stock_master_barang($this->request->data['Entry'][0]['value'] , +$diffstock );
                }
                // KURANGI STOCK !!
                else if($myEntry['EntryMeta']['stock'] > $this->request->data['EntryMeta'][3]['value'])
                {
                    $diffstock = $myEntry['EntryMeta']['stock'] - $this->request->data['EntryMeta'][3]['value'];
                    $input['Entry']['entry_type'] = "pindah-keluar";
                    $input['Entry']['title'] = 'automatic_'.strtolower($this->request->data['Entry'][0]['value']);
                    $input['Entry']['slug'] = $this->get_slug($input['Entry']['title']);                
                    $input['Entry']['description'] = 'Pengurangan stok barang oleh administrator.';
                    $input['Entry']['main_image'] = $diffstock; // jumlah stok...
                    $input['Entry']['created_by'] = $this->user['id'];
                    $input['Entry']['modified_by'] = $this->user['id'];
                    $input['Entry']['parent_id'] = $myParentEntry['Entry']['id'];
                    $this->Entry->create();
                    $this->Entry->save($input);

                    // kurangi stock di master barang...
                    $this->EntryMeta->add_stock_master_barang($this->request->data['Entry'][0]['value'] , -$diffstock);
                }
            }            
		}
		else if($myTypeSlug == "surat-jalan" && empty($myEntry))
		{
			$this->Entry->addSuratJalan($this->request->data);
		}
		else if($myTypeSlug == "resi")
		{
			$this->request->data['EntryMeta']['key'] = "id-surat-jalan";
			$this->request->data['EntryMeta']['value'] = $this->request->data['Entry']['id-surat-jalan'];
			$this->EntryMeta->create();
			$this->EntryMeta->save($this->request->data);

			// update resi status di surat jalan tsb...
			$suratjalan = $this->Entry->findBySlug($this->request->data['Entry']['id-surat-jalan']);
			$this->Entry->id = $suratjalan['Entry']['id'];
			$this->Entry->saveField('status', '1');
		}
	}

	/**
	* update certain entry 
	* @param array $myType contains record query result of database type
	* @param array $myEntry contains record query result of the selected Entry
	* @param array $myParentEntry[optional] contains record query result of the parent Entry (used if want to search certain child Entry) 
	* @param string $myChildTypeSlug[optional] contains slug of child type database (used if want to search certain child Entry)
	* @return array $result a selected entry with all of its attributes you'd requested
	* @public
	**/
	function _admin_default_edit($myType = array() , $myEntry = array() , $myParentEntry = array() , $myChildTypeSlug = NULL , $lang = NULL)
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
		$this->setTitle('Edit '.$myEntry['Entry']['title']);
		$myChildType = $this->Type->findBySlug($myChildTypeSlug);
		$data['myType'] = $myType;		
		$data['myEntry'] = $myEntry;
		$data['myParentEntry'] = $myParentEntry;
		$data['myChildType'] = $myChildType;
        
        // SEARCH IF GALLERY MODE IS TURN ON / OFF ...
        $myAutomaticValidation = (empty($myParentEntry)?$myType['TypeMeta']:$myChildType['TypeMeta']);
        $data['gallery'] = false;
        if(is_array($myAutomaticValidation))
        {
        	foreach ($myAutomaticValidation as $key => $value) 
	        {
	            if($value['key'] == 'gallery')
	            {
	                if($value['value'] == 'enable')
	                {
	                    $data['gallery'] = true;
	                }
	                break;
	            }
	        }
        }

        // FIRSTLY, sorting our image children !!
        if(!empty($data['myEntry']['ChildEntry']))
        {
        	$tempChild = $this->Entry->find('all' , array(
	            'conditions' => array(
	                'Entry.parent_id' => $myEntry['Entry']['id']
	            ),
	            'order' => array('Entry.id ASC')
	        ));
	        
	        foreach ($tempChild as $key => $value) 
        	{
        		$tempChild[$key] = breakEntryMetas($value);
        	}

	        $data['myEntry']['ChildEntry'] = $tempChild;
        }
        
		if(!empty($myType))
		{
			// GENERATE TYPEMETA AGAIN WITH SORT ORDER !!
			$metaOrder = $this->TypeMeta->find('all' , array(
				'conditions' => array(
					'TypeMeta.type_id' => (empty($myParentEntry)?$myType['Type']['id']:$myChildType['Type']['id'])
				),
				'order' => array('TypeMeta.id ASC')
			));
			if(empty($myParentEntry))
			{
				$data['myType']['TypeMeta'] = $metaOrder;
			}
			else
			{
				$data['myChildType']['TypeMeta'] = $metaOrder;
			}
		}
		// for image input type reason...
		$data['myImageTypeList'] = $this->EntryMeta->embedded_img_meta('type');
		// --------------------------------------------- LANGUAGE OPTION LINK ------------------------------------------ //
		$lang_opt = $this->Entry->find('all' , array(
			'conditions' => array(
				'Entry.lang_code LIKE' => '%-'.substr($myEntry['Entry']['lang_code'], 3)
			)
		));
		foreach ($lang_opt as $key => $value) 
		{
			$language_link[substr($value['Entry']['lang_code'], 0,2)] = $value['Entry']['slug'];
		}
		$data['language_link'] = $language_link;
		$data['lang'] = $lang;
		if(!empty($myParentEntry))
		{
			$temp100 = $this->Entry->find('all' , array(
				'conditions' => array(
					'Entry.lang_code LIKE' => '%-'.substr($myParentEntry['Entry']['lang_code'], 3)
				)
			));
			foreach ($temp100 as $key => $value) 
			{
				$parent_language[ substr($value['Entry']['lang_code'], 0,2) ] = $value['Entry']['slug'];
			}
			$data['parent_language'] = $parent_language;
		}
		// ------------------------------------------ END OF LANGUAGE OPTION LINK -------------------------------------- //        
        // check if this is a view entity mode or not !!
        $data['view_mode'] = ($myType['Type']['slug']=="surat-jalan" || ($myType['Type']['slug']=="purchase-order"||$myType['Type']['slug']=="sales-order") && empty($myChildType));
        
		$this->set('data' , $data);
		
		// if form submit is taken...
		if (!empty($this->request->data))
		{
			if(empty($lang))
			{
				$this->request->data['Entry']['title'] = $this->request->data['Entry'][0]['value'];
				$this->request->data['Entry']['description'] = $this->request->data['Entry'][1]['value'];
				$this->request->data['Entry']['main_image'] = $this->request->data['Entry'][2]['value'];
				if(isset($this->request->data['Entry'][3]['value']))
				{
					$this->request->data['Entry']['status'] = $this->request->data['Entry'][3]['value'];
				}
				
				// write my modifier ID...
				
				$this->request->data['Entry']['modified_by'] = $this->user['id'];

				// write time modified manually !!
				$nowDate = $this->getNowDate();
				$this->request->data['Entry']['modified'] = $nowDate;
				
				// PREPARE FOR ADDITIONAL LINK OPTIONS !!
				$myChildTypeLink = (!empty($myParentEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:'');
				$myTranslation = (empty($myChildTypeLink)?'?':'&').'lang='.substr($myEntry['Entry']['lang_code'], 0,2);
				
				// now for validation !!
				$this->Entry->set($this->request->data);
				if($this->Entry->validates())
				{		
					// --------------------------------- NOW for validating the details of this entry !!!
					$errMsg = "";
					$myDetails = $this->request->data['EntryMeta'];
					foreach ($myDetails as $key => $value) 
					{
						if($value['input_type']=='file')				{continue;}
						else if($value['input_type']=='multibrowse')	{$value['value'] = array_unique(array_filter($value['value']));}
							
						// firstly DO checking validation from view layout !!!
						$myValid = explode('|', $value['validation']);
						foreach ($myValid as $key10 => $value10) 
						{
							$tempMsg = $this->Validation->blazeValidate($value['value'],$value10 , $value['key']);
							$errMsg .= ( strpos($errMsg, $tempMsg) === FALSE ?$tempMsg:"");
						}
						// secondly DO checking validation from database !!!
						$myAutomaticValidation = (empty($myParentEntry)?$myType['TypeMeta']:$myChildType['TypeMeta']);
						foreach ($myAutomaticValidation as $key2 => $value2) // check for validation for each attribute key... 
						{
							if($value['key'] == $value2['key']) // if find the same key...
							{					
								$myValid = explode('|' , $value2['validation']);
								foreach ($myValid as $key3 => $value3) 
								{
									$tempMsg = $this->Validation->blazeValidate($value['value'],$value3 , $value['key']);
									$errMsg .= ( strpos($errMsg, $tempMsg) === FALSE ?$tempMsg:"");
								}
								break;
							}
						}
					}
					// LAST CHECK ERROR MESSAGE !!
					if(!empty($errMsg))
					{
						$this->Session->setFlash($errMsg,'failed');
						return;
					}
					// ------------------------------------- end of entry details...
					$this->Entry->id = $myEntry['Entry']['id'];
					$this->Entry->save($this->request->data);
					$galleryId = $myEntry['Entry']['id'];
                    if($data['gallery'])
                    {                        
                        // delete all the child image, and then add again !!
                        $this->Entry->deleteAll(array('Entry.parent_id' => $galleryId,'Entry.entry_type' => $myEntry['Entry']['entry_type']));
                        
                        foreach ($this->request->data['Entry']['image'] as $key => $value) 
                        {
                            $myImage = $this->Entry->findById($value);
                            
                            $input = array();
                            $input['Entry']['entry_type'] = $myEntry['Entry']['entry_type'];
                            $input['Entry']['title'] = $myImage['Entry']['title'];
                            $input['Entry']['slug'] = $this->get_slug($myImage['Entry']['title']);
                            $input['Entry']['main_image'] = $value;
                            $input['Entry']['parent_id'] = $galleryId;
                            $input['Entry']['created_by'] = $this->user['id'];
                            $input['Entry']['modified_by'] = $this->user['id'];
                            $this->Entry->create();
                            $this->Entry->save($input);
                        }
                    }

                    // delete all the attributes, and then add again !!
					$this->EntryMeta->deleteAll(array(
						'EntryMeta.entry_id' => $myEntry['Entry']['id'] ,
						'OR' => array(
							array('EntryMeta.key LIKE' => 'form-%'),
							array('EntryMeta.key LIKE' => 'id-%'),
							array('EntryMeta.key LIKE' => 'count-form-%'),
						)
					));

                    // delete all the field child image, and then add again !!
                    $this->Entry->deleteAll(array('Entry.parent_id' => $galleryId,'Entry.entry_type LIKE' => 'form-%'));

                    if(!empty($this->request->data['Entry']['fieldimage']))
	                {
	                	foreach ($this->request->data['Entry']['fieldimage'] as $fieldkey => $fieldvalue) 
	                	{
	                		foreach ($fieldvalue as $key => $value) 
	                		{
	                			$myImage = $this->Entry->findById($value);

	                			$input = array();                        
		                        $input['Entry']['entry_type'] = $fieldkey;
		                        $input['Entry']['title'] = $myImage['Entry']['title'];
		                        $input['Entry']['slug'] = $this->get_slug($myImage['Entry']['title']);
		                        $input['Entry']['main_image'] = $value;
		                        $input['Entry']['parent_id'] = $galleryId;
		                        $input['Entry']['created_by'] = $this->user['id'];
		                        $input['Entry']['modified_by'] = $this->user['id'];
		                        $this->Entry->create();
		                        $this->Entry->save($input);
	                		}
	                	}
	                }

	                // Insert New EntryMeta ...
					$this->request->data['EntryMeta']['entry_id'] = $myEntry['Entry']['id'];
					foreach ($myDetails as $key => $value)
					{	
						if(!empty($value['value']) && substr($value['key'], 0,5) == 'form-')
						{
							if($value['input_type'] == 'file' && !empty($_FILES[$value['key']]['name']))
							{
								$_FILES[$value['key']]['value'] = $value['value'];
							}
							else
							{
								$this->request->data['EntryMeta']['key'] = $value['key'];
								if($value['input_type'] == 'image' && isset($value['w']) && isset($value['h']))
								{
									$this->request->data['EntryMeta']['value'] = $this->Entry->makeChildImageEntry($value,(empty($myParentEntry)?$myType:$myChildType));
								}
								else if($value['input_type'] == 'multibrowse')
								{
									$this->request->data['EntryMeta']['value'] = implode('|', array_unique(array_filter($value['value'])));
								}
								else
								{
									$this->request->data['EntryMeta']['value'] = ($value['input_type'] == 'checkbox'?implode("|",$value['value']):$value['value']);
								}
								$this->EntryMeta->create();
								$this->EntryMeta->save($this->request->data);
							}
						}
					}

					// Upload File !!
					if(isset($_FILES))
					{
						foreach ($_FILES as $key => $value) 
						{
							if(!empty($value['name']))
							{
								if(!empty($value['value']))
								{
									deleteFile($value['value']);
								}
								
								$value['name'] = getValidFileName($value['name']);
								uploadFile($value);
								// Save data to EntryMeta !!
								$this->request->data['EntryMeta']['key'] = $key;
								$this->request->data['EntryMeta']['value'] = $value['name'];
								$this->EntryMeta->create();
								$this->EntryMeta->save($this->request->data);
							}
						}
					}
                    
                    // --------- UPDATE SHIPPING ID OR SOMETHING ELSE RELATED !! ----------- //
				    $this->_add_update_id_meta($myType['Type']['slug'] , $myChildTypeSlug , $myParentEntry , $myEntry);

					$this->Session->setFlash($this->request->data['Entry']['title'].' has been updated.','success');
					if($this->request->params['admin']==1)
					{
						$newEntrySlug = $this->Entry->checkRemainingLang($myEntry['Entry']['id'] , $this->mySetting);
						if($newEntrySlug)
						{
							$this->redirect(array('action' => (empty($myType)?'pages':$myType['Type']['slug']) , 'edit' , $newEntrySlug ));
						}
						else
						{
							$this->redirect(array('action' => (empty($myType)?'pages':$myType['Type']['slug']).(empty($myParentEntry)?'':'/'.$myParentEntry['Entry']['slug']).$myChildTypeLink.$myTranslation));
						}
					}
					else
					{
						$this->redirect( redirectSessionNow($_SESSION['now']) );
					}
				}
				else 
				{	
					$this->_setFlashInvalidFields($this->Entry->invalidFields());
					return;
				}
			}
			else // ADD NEW TRANSLATION LANGUAGE !!
			{	
				$this->_admin_default_add($myType , $myParentEntry , $myChildTypeSlug , $lang.substr( $myEntry['Entry']['lang_code'] , 2) , $myEntry['Entry']['slug']);
			}
		}
		return $data;
	}
	
	/**
	 * blueimp jQuery plugin function for initialize upload media image purpose
	 * @return void
	 * @public
	 **/
	public function UploadHandler()
	{
		$this->autoRender = FALSE;
		App::import('Vendor', 'uploadhandler');
		$upload_handler = new UploadHandler();
		
		$info = $upload_handler->post();
		
		// update database...
		if(isset($info[0]->name) && (!isset($info[0]->error)))
		{
			$path_parts = pathinfo($info[0]->name);
			$filename = $path_parts['filename'];
			$mytype = strtolower($path_parts['extension']);

			// CHECK FILE ALREADY EXISTS OR NOT ?
			$checkmedia = $this->meta_details(NULL , 'media' , NULL , NULL , NULL , NULL , $filename);
			if(!empty($checkmedia) && $checkmedia['EntryMeta']['image_type'] == $mytype)
			{
				$this->request->data['Entry'] = $checkmedia['Entry'];
				$myid = $checkmedia['Entry']['id'];

				// REMOVE OLD IMAGE FILE !!
				unlink(WWW_ROOT.'img'.DS.'upload'.DS.$myid.'.'.$mytype);
				unlink(WWW_ROOT.'img'.DS.'upload'.DS.'thumb'.DS.$myid.'.'.$mytype);

				// DELETE ENTRY METAS TOO !!
				$this->EntryMeta->deleteAll(array('EntryMeta.entry_id' => $myid));
			}
			else // create new data !!
			{
				// set the type of this entry...
				$this->request->data['Entry']['entry_type'] = 'media';
				$this->request->data['Entry']['title'] = $filename;
				// generate slug from title...
				$this->request->data['Entry']['slug'] = $this->get_slug($this->request->data['Entry']['title']);
				// write my creator...
				
				$this->request->data['Entry']['created_by'] = $this->user['id'];
				$this->request->data['Entry']['modified_by'] = $this->user['id'];
				$this->Entry->create();
				$this->Entry->save($this->request->data);
				
				$myid = $this->Entry->id;
			}

			// rename the filename...
			rename( WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$info[0]->name , WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$myid.'.'.$mytype);
			
			// now generate for display and thumb image according to the media settings...
			$myType = $this->Type->findBySlug($this->request->data['Type']['slug']);
			$myMediaSettings = $this->Entry->getMediaSettings($myType);
			
			// save the image type...			
			$this->request->data['EntryMeta']['entry_id'] = $myid;
			$this->request->data['EntryMeta']['key'] = 'image_type';
			$this->request->data['EntryMeta']['value'] = $mytype;
			$this->EntryMeta->create();
			$this->EntryMeta->save($this->request->data);
			// save the image size...
			$this->request->data['EntryMeta']['key'] = 'image_size';
			$this->request->data['EntryMeta']['value'] = $this->Entry->createDisplay($myid , $mytype , $myMediaSettings);
			$this->EntryMeta->create();
			$this->EntryMeta->save($this->request->data);
			
			//Resize original file for thumb...
			$this->Entry->createThumb($myid , $mytype , $myMediaSettings);
			
			// REMOVE ORIGINAL IMAGE FILE !!
			unlink(WWW_ROOT.'img'.DS.'upload'.DS.'original'.DS.$myid.'.'.$mytype);
		}
	}

	/**
	 * generate upload popup for uploading image to media library
 	 * @param string $myTypeSlug contains from what database type this function is called(used for media settings arrangements)
	 * @return void
	 * @public
	 **/
	public function upload_popup($myTypeSlug = NULL)
	{			
		$this->layout = 'ajax';	
		$this->set('myTypeSlug' , $myTypeSlug);
	}
	
	/**
	 * generate upload popup form for selecting image from media library
	 * @param integer $paging[optional] contains selected page of lists you want to retrieve
	 * @param string $myCaller[optional] contains type of method this popup is called
 	 * @param string $myTypeSlug[optional] contains from what database type this function is called(used for media settings arrangements)
	 * @return void
	 * @public
	 **/
	public function media_popup_single($paging = NULL , $mycaller = NULL , $myTypeSlug = NULL)
	{
		$this->setTitle("Media Library");
		$this->layout = ($this->request->is('ajax')?'ajax':'cms_blankpage');

		if(is_null($paging))
        {
            $paging = 1;
        }
        $this->set('paging' , $paging);
		$this->set('isAjax' , (is_null($mycaller) && is_null($myTypeSlug)?1:0) );		
		$this->set('myTypeSlug' , $myTypeSlug);
		
		// DEFINE MY TYPE CROP !!
		if(!empty($myTypeSlug))
		{
			$temp = $this->Type->findBySlug($myTypeSlug);
			$crop = -1;
			foreach ($temp['TypeMeta'] as $key => $value) 
			{
				if($value['key'] == 'display_crop')
				{
					$crop = $value['value'];
				}
			}
			$this->set('crop' , $crop);
		}
		
		$countPage = $this->mediaPerPage;
		
		$options['conditions'] = array(
			'Entry.entry_type' => 'media',
			'Entry.parent_id' => 0
		);
		$resultTotalList = $this->Entry->find('count' , $options);
		$this->set('totalList' , $resultTotalList);
		
		$options['order'] = array('Entry.sort_order DESC');
		$options['offset'] = ($paging-1) * $countPage;
		$options['limit'] = $countPage;
		$mysql = $this->Entry->find('all' ,$options);
		$this->set('myList' , $mysql);
		
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
		
		// set mycaller...
		if(is_null($mycaller))
		{
			$this->set('mycaller' , '0');
		}
		else
		{
			$this->set('mycaller' , $mycaller);
		}		
	}	
	
	function update_slug()
	{		
		$this->autoRender = FALSE;
		$slug = $this->Entry->get_valid_slug(    $this->get_slug($this->request->data['slug'])   ,  $this->request->data['id']  );
		$this->Entry->id = $this->request->data['id'];
		$this->Entry->saveField('slug' , $slug);
		echo $slug;
	}
	
	/**
	 * re-order entry sort_order for entries view order through ajax
	 * @return void
	 * @public
	 **/
	function reorder_list()
	{
		$this->autoRender = FALSE;
		$src = explode(',', $this->request->data['src_order']);
		$dst = explode(',', $this->request->data['dst_order']);
		unset($src[count($src)-1]);
		unset($dst[count($dst)-1]);		
		foreach ($dst as $key => $value) 
		{
			$fast_dst[$value] = $src[$key];
		}
		
		foreach ($src as $key => $value) 
		{			
			$temp = $this->Entry->findBySortOrder($value);
			$this->Entry->id = $temp['Entry']['id'];
			$result[$this->Entry->id] = $fast_dst[$this->Entry->id];
		}
		
		foreach ($result as $key => $value) 
		{			
			$this->Entry->id = $key;
			$this->Entry->saveField('sort_order' , $value);
		}
	}
    
    function ajax_title_counter($localCall = NULL)
	{
        $result = $this->Entry->ajax_title_counter($this->request->data['myTypeSlug'] , $this->request->data['frontTitle']);
        
		if(empty($localCall)) // is ajax
		{
            $this->autoRender = FALSE;
			echo $result;
		}
		else
		{
			return $result;
		}
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
		
		return (empty($options)?false:breakEntryMetas($this->Entry->find('first',$options)));
	}

	function admin_backup()
	{
		$mode = $this->request->params['mode'];

		$myTitle = "Backup Database & Files";
		$this->setTitle($myTitle);
		$this->set('myTitle' , $myTitle);

		if($mode == "clean")
		{			
			$this->Setting->cleanDatabase();
			$this->Session->setFlash('Database has been cleaned successfully.', 'success');
			$this->redirect (array('action' => 'backup'));
		}
		else if($mode == "backup-files") // uploaded files
		{
			$filename = 'files-'.get_slug($this->mySetting['title']).'-'.date('d-m-Y').'.zip';
			if(!Zip('img/upload', $filename)) // if zipping failed, then reload page and let the admin try again.
			{
				$this->render($this->backEndFolder."backup-restore");
			}
		}
		else if($mode == "backup") // database ...
		{
			$this->layout = "sql";
			$this->set('sql' , $this->Setting->backup_tables($this->get_db_host() , $this->get_db_user() , $this->get_db_password() , $this->get_db_name()));
			$this->render($this->backEndFolder."sql");
		}
		else if($mode == "restore")
		{	
			$ext = pathinfo($this->request->data['fileurl']['name'], PATHINFO_EXTENSION);
			if(strtolower($ext) == "sql")
			{
				$message = $this->Setting->executeSql($this->get_db_host(),$this->get_db_user() , $this->get_db_password() , $this->get_db_name(),$this->request->data['fileurl']['tmp_name']);
				if($message == "success")
				{
					$this->Session->setFlash('Database restoration success.', 'success');
				}
				else
				{
					$this->Session->setFlash($message, 'failed');
				}
			}
			else
			{
				$this->Session->setFlash('File extension invalid.', 'failed');
			}
			$this->redirect (array('action' => 'backup'));
		}
		else // JUST VIEWING OPTIONS !!
		{
			$this->render($this->backEndFolder."backup-restore");
		}
	}
    
    function pelunasan_tagihan($invoice) // PELUNASAN INVOICE SECARA LANGSUNG !!
	{
		$myParentEntry = $this->meta_details($invoice);		
		
        $date = date('m/d/Y');
		$temp = explode("/", $date);        
		$myTypeSlug = $this->request->data['myTypeSlug'] = ($myParentEntry['Entry']['entry_type']=="sales-order"?"piutang":"hutang");
        $frontTitle = $this->request->data['frontTitle'] = getFrontCodeId($myTypeSlug).substr($temp[2], 2).$temp[0].$temp[1];
        
        $input = array();
        $input['Entry']['entry_type'] = $myTypeSlug;
		$input['Entry']['title'] = $frontTitle.$this->ajax_title_counter('localcall');
        $input['Entry']['slug'] = $this->get_slug($input['Entry']['title']);        
		$input['Entry']['description'] = 'Invoice lunas (Instant Paid Off)';
		$input['Entry']['created_by'] = $input['Entry']['modified_by'] = $this->user['id'];
		$input['Entry']['parent_id'] = $myParentEntry['Entry']['id'];		
		$this->Entry->create();
		$this->Entry->save($input);
		
		$input['EntryMeta']['entry_id'] = $this->Entry->id;
		
        // add tanggal...
		$input['EntryMeta']['key'] = "form-tanggal";
		$input['EntryMeta']['value'] = $date;
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
        // add mutasi...
		$input['EntryMeta']['key'] = "form-".($myParentEntry['Entry']['entry_type']=="sales-order"? ($myParentEntry['EntryMeta']['balance'] > 0?"mutasi_kredit":"mutasi_debet") : ($myParentEntry['EntryMeta']['balance'] > 0?"mutasi_debet":"mutasi_kredit") );        
		$input['EntryMeta']['value'] = abs($myParentEntry['EntryMeta']['balance']);
		$this->EntryMeta->create();
		$this->EntryMeta->save($input);
		
		// SET BALANCE TO ZERO!!
		$balance = $this->EntryMeta->find('first',array(
			"conditions" => array(
				"EntryMeta.entry_id" => $myParentEntry['Entry']['id'],
				"EntryMeta.key" => "form-balance"
			)
		));
		$this->EntryMeta->id = $balance['EntryMeta']['id'];
		$this->EntryMeta->saveField('value' , 0);
		
		// UPDATE PAYMENT STATUS TO COMPLETE !!
		$payment = $this->EntryMeta->find('first',array(
			"conditions" => array(
				"EntryMeta.entry_id" => $myParentEntry['Entry']['id'],
				"EntryMeta.key" => "form-status_bayar"
			)
		));
		$this->EntryMeta->id = $payment['EntryMeta']['id'];
		$this->EntryMeta->saveField('value' , 'Lunas');
        // END OF PROCESS !!
        
		$this->redirect('/admin/entries/'.$myParentEntry['Entry']['entry_type'].'/'.$myParentEntry['Entry']['slug'].'?type='.$myTypeSlug);
	}
}

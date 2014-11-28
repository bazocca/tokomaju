<?php
class TypeMetasController extends AppController {

	var $name = 'TypeMetas';

	function index() {
		$this->UserDetail->recursive = 0;
		$this->set('userDetails', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user detail'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('userDetail', $this->UserDetail->read(null, $id));
	}

	function add() {
		if (!empty($this->request->data)) {
			$this->UserDetail->create();
			if ($this->UserDetail->save($this->request->data)) {
				$this->Session->setFlash(__('The user detail has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user detail could not be saved. Please, try again.'));
			}
		}
		$users = $this->UserDetail->User->find('list');
		$attributes = $this->UserDetail->Attribute->find('list');
		$this->set(compact('users', 'attributes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid user detail'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->UserDetail->save($this->request->data)) {
				$this->Session->setFlash(__('The user detail has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user detail could not be saved. Please, try again.'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->UserDetail->read(null, $id);
		}
		$users = $this->UserDetail->User->find('list');
		$attributes = $this->UserDetail->Attribute->find('list');
		$this->set(compact('users', 'attributes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user detail'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->UserDetail->delete($id)) {
			$this->Session->setFlash(__('User detail deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User detail was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

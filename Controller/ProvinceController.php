<?php
App::uses('AppController', 'Controller');
/**
 * Province Controller
 *
 * @property Provincia $Provincia
 */
class ProvinceController extends AppController {

    
    public function beforeFilter() {        
        $this->Auth->allow(array('display') ); //'index', 'view', 
                
        
            if($this->Auth->user('role_id')!= 1 ) {
                 $this->Session->setFlash('Non hai i permessi per getire le province.'); 
                 $this->redirect('/');
            }
        
                /*
         * @todo add defaults for users permissions
         */
        parent::beforeFilter();
    }

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Provincia->recursive = 0;
		$this->set('province', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Provincia->id = $id;
		if (!$this->Provincia->exists()) {
			throw new NotFoundException(__('Invalid provincia'));
		}
		$this->set('provincia', $this->Provincia->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Provincia->create();
			if ($this->Provincia->save($this->request->data)) {
				$this->Session->setFlash(__('The provincia has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The provincia could not be saved. Please, try again.'));
			}
		}
		$offerte = $this->Provincia->Offerta->find('list');
		$richieste = $this->Provincia->Richiesta->find('list');
		$this->set(compact('offerte', 'richieste'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Provincia->id = $id;
		if (!$this->Provincia->exists()) {
			throw new NotFoundException(__('Invalid provincia'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Provincia->save($this->request->data)) {
				$this->Session->setFlash(__('The provincia has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The provincia could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Provincia->read(null, $id);
		}
		$offerte = $this->Provincia->Offerta->find('list');
		$richieste = $this->Provincia->Richiesta->find('list');
		$this->set(compact('offerte', 'richieste'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Provincia->id = $id;
		if (!$this->Provincia->exists()) {
			throw new NotFoundException(__('Invalid provincia'));
		}
		if ($this->Provincia->delete()) {
			$this->Session->setFlash(__('Provincia deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Provincia was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

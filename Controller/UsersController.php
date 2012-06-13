<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * 
 * @property SessionComponent $Session
 * 
 */

class UsersController extends AppController {
    
    public $paginate = array('order' => array('id' => 'desc'));

    public function beforeFilter() {
        $allowed = array('login', 'forgotten_password', 'register', 'activate', 'logout');        
        $this->Auth->authenticate = array('Form');
        $this->Auth->allow($allowed);
        
        if(!in_array($this->request->action, $allowed) && $this->Auth->user('role_id') != 1 ) {
            $this->Session->setFlash('Non hai i permessi per gestire gli utenti.');
            $this->redirect('/');
        }
        
        parent::beforeFilter();
    }
    
    public function register() {
        $this->SignMeUp->register();
    }

    public function activate() {
        $this->SignMeUp->activate();
    }

    public function forgotten_password() {
        $this->SignMeUp->forgottenPassword();
    }
    
    public function login() {
        
        //$this->Facebook->noAuth = true;
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Session->setFlash('Autenticato correttamente', 'default', array('class' => 'information')); //, 'auth'
                
                //remove the "all" province session filter for anonymous user; next request will set users's default filter
                $this->Session->delete('provincia_id');            
                                
                return $this->redirect($this->Auth->redirect('/richieste'));
            } else {
                $this->Session->setFlash('Combinazione di username e password errata', 'default', array('class' => 'error')); //, 'auth'
            }
        }
    }

    public function logout() {
        
        $this->Auth->logout();
        $this->Session->destroy();
        
        $this->Session->setFlash('Uscito correttamente.', 'default', array('class' => 'information'));
        $this->redirect('/');
    }


/**
 * index method 
 * 
 * @todo later, restricted for admins only
 *
 * @return void
 */
	public function index() {
            if($this->Auth->loggedIn() && $this->Auth->user('role_id') == 1) {                    
		$this->User->recursive = 0;
		$this->set('users', $this->paginate()); 
                
            } else {
                $this->Session->setFlash('Non sei autorizzato a modificare gli utenti!');
                $this->redirect($this->referer('/'));
            } 
	}

        
        /*
         * baked methods below. May serve as base for future development
         * @TODO: actual user management.
         */
        
/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
                
                $province = $this->User->Provincia->find('list');
                $this->set('province', $province);
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
            if($this->Auth->loggedIn() && $this->Auth->user('role_id') == 1) {                    
	    
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
                        $province = $this->User->Provincia->find('list');
                        $this->set('province', $province);
		}
                     
            } else {
                $this->Session->setFlash('Non sei autorizzato a modificare gli utenti!');
                $this->redirect($this->referer('/'));
            } 
	}
        
        
        public function profilo () {
            if($this->Auth->loggedIn()) {                    
	    
		$id = $this->User->id = $this->Auth->user('id');
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
                        $province = $this->User->Provincia->find('list');
                        $this->set('province', $province);
		}
                     
            } else {
                $this->Session->setFlash('Errore, id profilo errato');
                $this->redirect($this->referer('/'));
            } 
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

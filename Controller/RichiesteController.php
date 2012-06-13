<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Richieste Controller
 *
 * @property Richiesta $Richiesta
 * 
 * @property AuthComponent $Auth
 * 
 */
class RichiesteController extends AppController {

    public $paginate = array('Richiesta' => array( 'order' => array('in_evidenza' => 'desc', 'created' => 'desc'), 'contain' => array(
        'User' => array('fields' => array('id','username', 'email'), 'Provincia'),
        'Tipo',        
        'Provincia'
        ) 
    ));
    
    public $defaultContain = array(
        'User' => array('fields' => array('id','username', 'email'), 'Provincia'),
        'Tipo',
        'ProvinceRichieste',
        'Provincia'
    );
    
    //Needed for Filter Component
    var $filters = array
        (  
            'index' => array  
            (  
                'Richiesta' => array  
                (  
                    'Richiesta.cosa_serve' => array('div' => false),  
                    'Richiesta.dove_a_chi' => array('div' => false),  
                    //'Richiesta.id' => array('condition' => '='),  
//                    'Richiesta.user_id' => array  
//                    (  
//                        'type' => 'select',  
//                        'label' => 'Inserito da',  
//                        'selector' => 'getOwnerList'  
//                    )  
                )  
            )  
        );  
    
//    public $helpers = array('Cache');
//    
//    public $cacheAction = array(
//        'view_utenti' => 36000,
//        'view' => array('callbacks' => true, 'duration' => 36000),
//        'index'  => array('callbacks' => true, 'duration' => 48000) ,
//        'index_public'  => 48000
//    );
    
    public function beforeFilter() {
        $this->Auth->allow(array('display', 'index_public', 'view_public') ); //'index', 'view', 
                
        if($this->request->action == 'index') {
            if(!$this->Auth->loggedIn()) {
                $redir = am(array('action' => 'index_public'), $this->params->named);               
                $this->redirect($redir);
            }
            if($this->Auth->user('role_id') == 3) {
                $redir = am(array('action' => 'index_utenti'), $this->params->named);   
                 $this->redirect($redir);
            }
        }
        
        if($this->request->action == 'view') {
            if($this->Auth->user('role_id') == 3) {
                 $this->redirect('/richieste/view_utenti/'.$this->params->pass[0]);
            }
        }
        
        if($this->Auth->user('role_id') > 2 ) {
            $this->set('limitato', true);
//            $this->Session->setFlash("Spiacente: per ora l'elenco delle richieste è visibile solo agli amministratori o alle organizzazioni. Contatta promozione@csvferrara.it o segreteria@csvferrara.it per infromazioni, oppure segnala la tua disponibilità nelle offerte");
//            $this->redirect('/offerte/add');
        } else {
            $this->set('limitato', false);
        }
        
        /*
         * @todo add defaults for users permissions
         */
        parent::beforeFilter();
    }
   
    public function invia_messaggio($id) {
        if(!isset($id)) {
            $this->Session->setFlash('Errore, id della richiesta non specificato');
            $this->redirect($this->referer());
        }
        $richiesta = $this->Richiesta->read(null,$id);
        if ($this->request->is('post') || $this->request->is('put')) {
            
            $from    = array( $this->request->data['Richiesta']['tua_email'] =>  $this->request->data['Richiesta']['tua_email']);
            if(empty($this->request->data['Richiesta']['tua_email']))  $email->from = array( 'noreply@serveaiuto.org' => 'Mail dal sito');
            $to      = array($richiesta['Richiesta']['email'] => $richiesta['Richiesta']['email'] );
            $ccn     = array($richiesta['User']['email'] => $richiesta['User']['email'],'promozione@csvferrara.it' => 'promozione@csvferrara.it');
            
            $subject = 'Risposta a: ' . $richiesta['Richiesta']['cosa_serve'];
            
            $email = new CakeEmail(array('transport' => 'Mail',
                       
                'charset' => 'utf-8',
                'headerCharset' => 'utf-8',
                'from' => $from,
                'to' => $to,
                'ccn' => $ccn,
                'subject' => $subject 
                )
            );
            
//            debug($this->request->data);
//            debug($email->from);
//            debug($email);
            $inviata = $email->send($this->request->data['Richiesta']['testo']);  
            
            if(!empty($inviata)) {
                $this->Session->setFlash('Risposta inviata correttamente.');
            } else {
                $this->Session->setFlash('Errore. Risposta non inviata, riprovare');
            }
            
        }
        
        //$this->render('view');
        $this->redirect($this->referer());
    }    
    
    public function completa ($id) {
        if(!isset($id)) {
            $this->Session->setFlash('Richiesta non specificata');
            $this->redirect($this->referer());
        }
        $this->_check_ownership_and_with_redirect($id);
        $richiesta = $this->Richiesta->read(null, $id);
        if($richiesta) {
            $richiesta['Richiesta']['completa'] = !$richiesta['Richiesta']['completa'];
            $this->Richiesta->save($richiesta);
            $this->Session->setFlash('Stato della richiesta modificato correttamente');
        }  else { 
            $this->Session->setFlash('Errore. Stato della richiesta non modificato');
        }
        $this->redirect( $this->referer() );
    }
    
    
/**
 * index method
 *
 * @return void
 */
	public function index() {
            
                if(!$this->Auth->loggedIn()) {
                    $this->Session->setFlash("Non puoi accedere all'elenco dettagliato. Se vuoi rispondere alle richieste devi registrarti ed accedere.");
                }
		$this->Richiesta->recursive = 0;
                
                $parameters = array_diff_key($this->request->named, array_flip(array('page','sort','direction')));
                
                if(isset($this->request->named['completa'])) {
                    if ($this->request->named['completa'] == 'all') {
                        unset($parameters['completa'] );
                    }
                }
                
                if(!array_key_exists('provincia', $parameters)) {                
                    $prov = $this->_filtra_per_provincia();
                    if($prov) $parameters['provincia'] = $prov;
                }
                                
                //add query filters based on named params
                $conditions = null;
                if(!empty($parameters)) {
                    foreach($parameters as $pkey => $pvalue) {
                        if(!in_array($pkey, array('dove_a_chi','cosa_serve'))) {
                        
                            if (in_array($pkey, array('completa'))) { //simple value 
                                $conditions['Richiesta.'.$pkey] = $pvalue;
                            } elseif (in_array($pkey, array('provincia'))) { //many to many, set habtm query and filter
                                $conditions['ProvinceRichieste.'.$pkey .'_id'] = $pvalue;
                                $this->paginate['Richiesta']['contain'] = $this->defaultContain;
                                $this->Richiesta->bindModel(array('hasOne'=>array('ProvinceRichieste')), false);
                                $this->paginate['Richiesta']['group'] = 'Richiesta.id';
                                //debug($this->paginate); debug($this->Richiesta);
                            } else { //id based belongs_to
                                $conditions['Richiesta.'.$pkey.'_id'] = $pvalue;
                            }
                        }
                    }
                }                
                
                $richieste = $this->paginate('Richiesta', $conditions);
		$this->set('richieste', $richieste);
                
                $this->render($this->request->action);
                
	}
        
        function index_public() {
            $this->index();
        }
        
        function index_utenti() {
            $this->index();
        }

        /**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Richiesta->id = $id;
		if (!$this->Richiesta->exists()) {
			throw new NotFoundException(__('Invalid richiesta'));
		}
		$this->set('richiesta', $this->Richiesta->read(null, $id));
	}

        public function view_utenti($id = null) {
            $this->view($id);
        }
        
        public function view_public($id = null) {
            $this->view($id);
        }
        
/**
 * add method
 *
 * @return void
 */
	public function add() {
                if($this->Auth->user('role_id') == 3) {
                    $this->Session->setFlash("Spiacente: Non hai i permessi per aggiungere richieste. Contatta promozione@csvferrara.it o segreteria@csvferrara.it per infromazioni, oppure segnala la tua disponibilità nelle offerte");
                    $this->redirect('/richieste/index'); 
                }
		if ($this->request->is('post')) {
			$this->Richiesta->create();
			if ($this->Richiesta->save($this->request->data)) {
				$this->Session->setFlash('Richiesta salvata.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Richiesta non salvata, riprovare');
			}
		}
		$tipi = $this->Richiesta->Tipo->find('list');
                $province = $this->Richiesta->Provincia->find('list');
		//$users = $this->Richiesta->User->find('list');
		$this->set(compact('tipi', 'users', 'province'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
                        
		$this->Richiesta->id = $id;
		if (!$this->Richiesta->exists()) {
			throw new NotFoundException(__('Invalid richiesta'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                    
                    if(!$this->_is_mine($id)) {
                        $this->Session->setFlash('Non hai i permessi di modificare questa richiesta');
                        $this->redirect($this->referer());
                    }
                    
                    if ($this->Richiesta->save($this->request->data)) {
                            $this->Session->setFlash(__('The richiesta has been saved'));
                            $this->redirect($this->Session->read('redirect'));
                    } else {
                            $this->Session->setFlash('Richiesta non salvata, riprovare');
                    }
		} else {
			$this->request->data = $this->Richiesta->read(null, $id);
                        if(!$this->_is_mine($this->request->data)) {
                            $this->Session->setFlash('Non hai i permessi di modificare questa richiesta');
                            $this->redirect($this->referer());
                        }
		}
                $this->Session->write('redirect', $this->referer());
		$tipi = $this->Richiesta->Tipo->find('list');
                $province = $this->Richiesta->Provincia->find('list');
		//$users = $this->Richiesta->User->find('list');
		$this->set(compact('tipi', 'users', 'province'));
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
		$this->Richiesta->id = $id;
		if (!$this->Richiesta->exists()) {
			throw new NotFoundException(__('Invalid richiesta'));
		}
		if ($this->Richiesta->delete()) {
			$this->Session->setFlash(__('Richiesta deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Richiesta was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

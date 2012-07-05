<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array(
        'DebugKit.Toolbar', 
        'Session',           
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login',
                //'plugin' => 'users'
            ),
//            'logoutAction' => array(
//                'controller' => 'users',
//                'action' => 'logout',
//                //'plugin' => 'users'
//            ),
            'authError' => ' ', //'Non hai i permessi per questo!!',
            'authenticate' => array( //well, no, leave the username as login field
                'Form' => array(
                    'fields' => array('username' => 'username')
                )
            )
        ),
        'Facebook.Connect' => array('model' => 'User'),        
        'SignMeUp.SignMeUp', 
//        => array(
//            'activation_field' => false, //do not require activation by verification email, yet
//            'useractive_field' => false,
//        )

        //'Tools.Tiny'        
        'Filter.Filter'
    );
    
    public $helpers = array(
        'Html','Js', 'Form', 'Time', 'Text', 'Session',
        'Facebook.Facebook' => array('redirect' => true),    
        'Filter.Filter' => array(  
            'nopersist' => array('index')
        ),         
        "TB" => array(
            "className" => "TwitterBootstrap.TwitterBootstrap"
        ),
        'GoogleMapV3',
        'AddressFinder.AddressFinder'
    );
    
    
    public function beforeFilter() {
        //debug($this->helpers); 
        
        $this->_common_elements();
        
        $this->_provincia_attuale();
        
        $this->_check_aperta();
                
        //includere TinyAuth
        //$this->Auth->authenticate = array('Form');
        
        $this->Auth->allow(array('display')); //'index', 'view', 
        if($this->referer() == '/users/logout' && $this->Auth->user('id'))
            $this->Session->setFlash('Uscito correttamente.');
         
        /*
         * @todo add defaults for users permissions
         */
        
        
        parent::beforeFilter();
    }
    
    /**
     * sets the session var "aperta" to the corresponding value of users's default province.
     * it is used to precompile (check) the "public" / visible to org. level users falg in offer / request add action
     * and maybe some other dafault beahvour..
     */
        protected function _check_aperta() {
            if($this->Auth->user('id')) {
                if(!$this->Session->check('aperta')) {
                    App::uses('Provincia', 'Model');
                    $Provincia = new Provincia;
                    $provincia = $Provincia->find('first',array('conditions' => array('Provincia.id' => $this->Auth->user('provincia_id'))));
                    if($provincia['Provincia']['id'] == $this->Session->read('provincia_id')) {
                        $this->Session->write('aperta',$provincia['Provincia']['aperta']);                        
                    }
                }
            }
        }
    
        function beforeFacebookSave(){
            $this->Connect->authUser['User']['email'] = $this->Connect->user('email');
            $this->Connect->authUser['User']['username'] = $this->Connect->user('username');
            $this->Connect->authUser['User']['active'] =  1;
            debug($this->Connect->authUser);
            return true; //Must return true or will not save.
        }

//        function beforeFacebookLogin($user){
//            //Logic to happen before a facebook login
//        }
//        
        
        function afterFacebookLogin(){
            //Logic to happen after successful facebook login.
            $this->Session->delete('provincia_id');
            $this->Session->setFlash('Autenticato correttamente', 'default', array('class' => 'information')); //, 'auth'
                        
            $this->redirect($this->referer());
        }
        
        
        /**
         * ajax function for getting autocomplete suggestions for tags
         * @return string, json simple list of matching terms to feed jquery Ui multiple autocomplete
         */
        public function get_tags($model = null){
            //if not manually set, get current model
            if(is_null($model)) $model = $this->modelClass;        
            
            //$this->layout = 'basic';
            $this->autoRender = false;
            //should be set (at least 3 chards from javascript layer)
            if(isset($this->request->query['term'])) {
                $search = $this->request->query['term'];
            }
            //$this->modelClass is either Richiesta or Offerta
            $tags = $this->{$model}->Tag->find('list',array('conditions' => array('nome LIKE ' => '%'.$search.'%') ) );
            
            return json_encode(array_values($tags));
        }
        
        
        protected function _provincia_attuale() {
            if(!$this->Session->check('provincia_id')) {
                if($this->Auth->loggedIn()) {
                    $this->Session->write('provincia_id', $this->Auth->user('provincia_id'));
                } else {
                    $this->Session->write('provincia_id', 'all');
                }
            }
        }
        
        public function cambia_provincia($id = null) {
            if(!is_null($id)) {
                $this->Session->write('provincia_id', $id);
            }
            $this->redirect($this->referer());
        }
        
        
        protected function _filtra_per_provincia() {
            if($this->Session->check('provincia_id')) {
                if(is_numeric($this->Session->read('provincia_id')))
                    return $this->Session->read('provincia_id');
            }
            return false;
        }


        public function attiva_disattiva ($id, $field) {
            if(!isset($id)) {
                $this->Session->setFlash('Errore, id non specificato');
                $this->redirect($this->referer());
            }
            $this->_check_ownership_and_with_redirect($id);
            $record = $this->{$this->modelClass}->read(null, $id);
            if($record) {
                if(is_nan($record[$this->modelClass][$field])) $record[$this->modelClass][$field] = 0;
                $record[$this->modelClass][$field] = !$record[$this->modelClass][$field];
                $this->{$this->modelClass}->save($record);
                $this->Session->setFlash('Stato modificato correttamente');
            }  else { 
                $this->Session->setFlash('Errore. Stato non modificato');
            }
            $this->redirect( $this->referer() );
        }
        
        
        protected function _is_mine($record) {   
            if($this->Auth->user('role_id') == 1) return true;

            if(is_numeric($record)) {
                $record = $this->{$this->modelClass}->read(null,$record);
            }

            if(is_array($record)) {
                return $this->Auth->user('id') == $record[$this->modelClass]['user_id'];            
            }        
            return false;

        }

        protected function _check_ownership_and_with_redirect($record) {
            if(!$this->_is_mine($record)) {
                $this->Session->setFlash('Spiacente, non hai i permessi per questo.');
                $this->redirect($this->referer());                    
            }
            return true;
        }



        /**
        *retrieve from cache (or db) the values for rendering layout (sidebar) links 
        */
        protected function _common_elements () {

            $this->set('roles', array(
                '1' => 'admin',
                '2' => 'organizzazione, richiedente',
                '3' => 'utente, offerente'
            ));

            $this->_get_list_values('Tipo', 'tipi');
            $this->_get_list_values('Provincia', 'province');
        }    


        /**
        * helper method to get from cache (or from db) the values for menu links in layout
        * @param type $modelName
        * @param type $name_for_list 
        */
        protected function _get_list_values($modelName, $name_for_list = null) {
            if(is_null($name_for_list)) $name_for_list = Inflector::tableize ($modelName);

            if(!$list = Cache::read($name_for_list.'_list')) {
                App::uses($modelName, 'Model');
                $ActualModel = new $modelName;            
                //debug($ActualModel);
                $list = $ActualModel->find('list',array(
                    'recursive' => -1,
                    'order' => array($modelName.'.id' => 'asc')
                ));
                Cache::write($name_for_list.'_list', $list);
            }
            //debug($list);
            $this->set($name_for_list.'_list', $list);
        }
}

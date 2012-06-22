<?php

App::uses('AppController', 'Controller');
App::uses('Tag', 'Model');

/**
 * Offerte Controller
 *
 * @property Offerta $Offerta
 */
class OfferteController extends AppController {

    public $paginate = array('Offerta' => array('order' => array('Offerta.in_evidenza' => 'desc', 'Offerta.id' => 'desc'), 'contain' => array(
                'User' => array('fields' => array('id', 'username', 'email')),
                'Tipo',
                'OfferteTags',
                'Tag',
                'Categoria',
                'Provincia'
            )
            ));
    public $defaultContain = array(
        'User' => array('fields' => array('id', 'username', 'email')),
        'Tipo',
        'OfferteTags',
        'Tag',
        'Categoria',
        'OfferteProvince',
        'Provincia'
    );
    //Needed for Filter Component
    var $filters = array
        (
        'index' => array
            (
            'Offerta' => array
                (
                'Offerta.nome' => array('div' => false),
                'Offerta.offerta' => array('div' => false),
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
//        'view' => 36000,
//        'index'  => 48000
//    );

    public function beforeFilter() {
        $this->Auth->allow(array('display')); //'index', 'view', 
        $this->Auth->deny();
//        if($this->Auth->user('role_id') > 2) {
//            $this->Session->setFlash("Spiacente: per ora l'elenco delle richieste è visibile solo agli amministratori o alle organizzazioni. Contatta promozione@csvferrara.it o segreteria@csvferrara.it per infromazioni, oppure segnala la tua disponibilità nelle offerte");
//            $this->redirect('/offerte/add');            
//        }
//        
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
        $this->Offerta->recursive = 0;
        $conditions = null;

        if ($this->Auth->user('role_id') > 2) {
            $conditions['Offerta']['user_id'] = $this->Auth->user('id');
        }

        $parameters = array_diff_key($this->request->named, array_flip(array('page', 'sort', 'direction')));

        if (isset($this->request->named['completa'])) {
            if ($this->request->named['completa'] == 'all') {
                unset($parameters['completa']);
            }
        }


        if (!array_key_exists('provincia', $parameters)) {
            $prov = $this->_filtra_per_provincia();
            if ($prov)
                $parameters['provincia'] = $prov;
        }

        //add query filters based on named params
        if (!empty($parameters)) {
            foreach ($parameters as $pkey => $pvalue) {
                //exclude bath plugin's filter fields
                if (!in_array($pkey, array('nome', 'offerta'))) {

                    if (in_array($pkey, array('completa'))) { //simple value belongsTo
                        $conditions['Offerta.' . $pkey] = $pvalue;
                    } elseif (in_array($pkey, array('provincia'))) { //many to many, set habtm query and filter
                        $conditions['OfferteProvince.' . $pkey . '_id'] = $pvalue;
                        $this->paginate['Offerta']['contain'] = $this->defaultContain;
                        $this->Offerta->bindModel(array('hasOne' => array('OfferteProvince')), false);
                        $this->paginate['Offerta']['group'] = 'Offerta.id';
                        //debug($this->paginate); debug($this->Richiesta);                            
                    } else { //id based belongs_to
                        $conditions['Offerta.' . $pkey . '_id'] = $pvalue;
                    }
                }
            }
        }

        $this->set('offerte', $this->paginate('Offerta', $conditions));
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Offerta->id = $id;
        if (!$this->Offerta->exists()) {
            throw new NotFoundException(__('Invalid offerta'));
        }

        $offerta = $this->Offerta->read(null, $id);
        
        if($this->_is_mine($offerta)) {
            $this->set('richieste_suggerite', $this->suggerisci($offerta)); 
        }

        $offerta_privata = false;
        if ($this->Auth->user('role_id') == 2) {
            if (!$offerta['Offerta']['pubblica']) {

                $offerta_privata = true;
                $riferimenti = $this->Offerta->User->find('first', array(
                    'recursive' => 2,
                    'conditions' => array('User.id' => $offerta['Offerta']['user_id']),
                    'contain' => array('Provincia')
                    )
                );
                $this->set(compact('riferimenti'));
//                        $this->Session->setFlash("spiacente, l'offerta è privata - riservata agli amministratori - puoi contattare il csv di riferimento per infromazioni");
//                        $this->redirect($this->referer());
            }
        } else {
            $this->_check_ownership_and_with_redirect($id);
        }
        $this->set(compact('offerta', 'offerta_privata'));
    }
    
    
    public function suggerisci($offerta) {
        if(is_numeric($offerta)) {
            $offerta = $this->Richiesta->read(null, $offerta);
        }
                
        $richieste_suggerite  = $this->Offerta->User->Richiesta->suggerisci_richieste($offerta);
        if($this->request->params['action'] == 'suggerisci') {
            $this->set('richieste_suggerite', $richieste_suggerite);
            $this->set('offerta', $offerta);
        } else {
            return $richieste_suggerite;
        }
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Offerta->create();
            
            if ($this->Offerta->save($this->request->data)) {
                $tags = Tag::tagListToTags($this->request->data['Offerta']['taglist']);
                $this->setTags($this->Offerta, $tags);

                $this->Session->setFlash(__('The offerta has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The offerta could not be saved. Please, try again.'));
            }
        }
        //$users = $this->Offerta->User->find('list');
        $tipi = $this->Offerta->Tipo->find('list');
        $province = $this->Offerta->Provincia->find('list');
        $categorie = $this->Offerta->Categoria->find('list');

        $this->set(compact('users', 'tipi', 'province', 'categorie', 'taglist'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null) {

        $this->_check_ownership_and_with_redirect($id);

        $this->Offerta->id = $id;
        if (!$this->Offerta->exists()) {
            throw new NotFoundException(__('Invalid offerta'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Offerta->save($this->request->data)) {
                $tags = Tag::tagListToTags($this->request->data['Offerta']['taglist']);
                $this->setTags($this->Offerta, $tags, true);
                $this->Session->setFlash(__('The offerta has been saved'));
                $this->redirect($this->Session->read('redirect'));
            } else {
                $this->Session->setFlash(__('The offerta could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Offerta->read(null, $id);
            $taglist = Tag::tagsToTagList($this->getTags($this->Offerta, 'nome'));
            $this->request->data['Offerta']['taglist'] = $taglist;
        }
        $this->Session->write('redirect', $this->referer());
        $users = $this->Offerta->User->find('list');
        $tipi = $this->Offerta->Tipo->find('list');
        $province = $this->Offerta->Provincia->find('list');
        $categorie = $this->Offerta->Categoria->find('list');
        $tags = $this->Offerta->Tag->find('list');
        $this->set(compact('users', 'tipi', 'province', 'categorie', 'tags'));
    }

    public function completa($id) {
        if (!isset($id)) {
            $this->Session->setFlash('Offerta  non specificata');
            $this->redirect($this->referer());
        }
        $this->_check_ownership_and_with_redirect($id);
        $offerta = $this->Offerta->read(null, $id);
        if ($offerta) {
            $offerta['Offerta']['completa'] = !$offerta['Offerta']['completa'];
            if (is_null($offerta['Offerta']['completa']))
                $offerta['Offerta']['completa'] = 1;
            $this->Offerta->save($offerta);
            $this->Session->setFlash('Stato dell\'offerta modificato correttamente');
        } else {
            $this->Session->setFlash('Errore. Stato dell\'offerta non modificato');
        }
        $this->redirect($this->referer());
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->_check_ownership_and_with_redirect($id);
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Offerta->id = $id;
        if (!$this->Offerta->exists()) {
            throw new NotFoundException(__('Invalid offerta'));
        }
        if ($this->Offerta->delete()) {
            $this->Session->setFlash(__('Offerta deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Offerta was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    private function getTags(Offerta $offerta, $field = 'id') {
        $data = $offerta->find('first', array('conditions' => array('Offerta.id' => $offerta->getID())));
        $tags = $data['Tag'];
        
        
        $tagIds = array();
        foreach($tags as $tag) {
            $tagIds[] = $tag[$field];
            
        }

        return $tagIds;
    }

    private function setTags(Offerta $offerta, array $tags, $update = false) {
        $tagIds = array();

        foreach ($tags as $tag) {
            $tagIds[] = $offerta->Tag->findOrCreate($tag);
        }

        if ($update) {
            $current = $this->getTags($offerta);

            $removed = array_diff($current, $tagIds);
            $added = array_diff($tagIds, $current);
        } else {
            $removed = array();
            $added = $tagIds;
        }

        $linkTable = $this->Offerta->OfferteTags;
        $offertaId = $this->Offerta->id;

        foreach ($added as $tagId) {
            $linkTable->create();
            $linkTable->set('offerta_id', $offertaId);
            $linkTable->set('tag_id', $tagId);
            $linkTable->save();
        }

        foreach ($removed as $tagId) {
            $toDelete = $linkTable->find('list', array('conditions' => array(
                    'OfferteTags.offerta_id' => $offertaId,
                    'OfferteTags.tag_id' => $tagId,
            )));

            $linkTable->delete($toDelete);
        }
    }

}

<?php

App::uses('AppModel', 'Model');

/**
 * Richiesta Model
 *
 * @property Tipo $Tipo
 * @property User $User
 */
class Richiesta extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'cosa_serve';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'cosa_serve' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Cosa serve? questo campo non può essere vuoto',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Email non valida',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'scadenza' => array(
            'date' => array(
                'rule' => array('date'),
                'message' => 'Inserisci una data corretta..',
                'allowEmpty' => true,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Tipo' => array(
            'className' => 'Tipo',
            'foreignKey' => 'tipo_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Categoria' => array(
            'className' => 'Categoria',
            'foreignKey' => 'categoria_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'ProvinceRichieste' => array(
            'className' => 'ProvinceRichieste',
            'foreignKey' => 'richiesta_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Provincia' => array(
            'className' => 'Provincia',
            'joinTable' => 'province_richieste',
            'foreignKey' => 'richiesta_id',
            'associationForeignKey' => 'provincia_id',
            'unique' => 'keepExisting',
            'with' => 'ProvinceRichieste',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ),
        'Tag' => array(
            'className' => 'Tag',
            'joinTable' => 'richieste_tags',
            'foreignKey' => 'richiesta_id',
            'associationForeignKey' => 'tag_id',
            'unique' => 'keepExisting',
            'with' => 'RichiesteTags',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ),
    );

    public function esportabili($provincia_id = null) {

        $conditions ['Richiesta.completa'] = 0;
        $conditions ['Richiesta.segnala_in_indice_sito'] = 1;

        if (!is_null($provincia_id)) {
            $this->bindModel(array('hasOne' => array('ProvinceRichieste')), false);
            //$this->paginate['Richiesta']['group'] = 'Richiesta.id';
            $conditions['ProvinceRichieste.provincia_id'] = $provincia_id;
        }
        $esportabili = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array('DISTINCT (cosa_serve)', 'id',  'categoria_id', 'tipo_id'),
            'contain' => array(
                'Categoria',
                'Tipo',
                'ProvinceRichieste' => array('Provincia')
            ),
            'group' => array('Richiesta.cosa_serve'),
            'order' => array('Richiesta.categoria_id')
                )
        );
        return $esportabili;
    }
    
    
    /**
     *
     * @param type $richiesta the record we are matching against
     * @return false if not a valid request, or array of matching offers sorted by relevance  
     */
    public function suggerisci_richieste ($offerta) {
        if(!is_array($offerta)) return false;
        //just to recap.. set variables form the relavant values of the record we are matching against
        $this->titolo   = $offerta['Offerta']['offerta'];
        $this->testo    = $offerta['Offerta']['offerta'];
        $tipo_id = $offerta['Offerta']['tipo_id'];
        $categoria_id = $offerta['Offerta']['categoria_id'];
        
        //trick for using conditions in related habtm / hasmany model
        $this->bindModel(array('hasOne' => array('RichiesteTags')), false);
        
        //default conditions        
        $conditions = array(
            'Richiesta.completa' => 0,
            'Richiesta.categoria_id' => $categoria_id,
            'Richiesta.tipo_id' => $tipo_id
        );
        $contain = array('RichiesteTags','Provincia','Tag','Tipo','Categoria', 'User' => array('fields' => array('id','username') ));
        
        $tags = $offerta['Tag'];
        if( !empty ($tags) ) {
            foreach ($tags as $tag) {
                $tags_id[] = $tag['id'];
            }            
            $conditions['RichiesteTags.tag_id'] = $tags_id;
            
            //conta risultati per query più selettiva (conicidono tipo, categoria, tags)
            $results_tags = $this->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain 
            ));            
            $exclude_ids = Set::extract('/Richiesta/id', $results_tags);
            
            
            unset($conditions['Richiesta.categoria_id']);
            if(is_array($exclude_ids)) $conditions['not']['Richiesta.id'] = $exclude_ids;
            
            $results_tags_only = $this->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain 
            ));
            
            $exclude_ids = am($exclude_ids, Set::extract('/Richiesta/id', $results_tags_only));
            
            
        }
        
                
        if(is_array($exclude_ids)) $conditions['not']['Richiesta.id'] = $exclude_ids;
        
        if (isset($results_tags) && count($results_tags) < 10) {
            unset($conditions['Richiesta.categoria_id']);
            
            $results_tipo = $this->find('all', array(
                'conditions' => $conditions,
                'contain' =>  $contain
            ));
        }
        
        if (count ($results_tipo) < 10) {
            unset($conditions['RichiesteTags.tag_id']);
            
            $results = $this->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain
            ));
        }
        
        
        
        //first macro sort is by group (records found with strict conditions fisrt, with luosy conditions later)
        //then sort each group by relevance
        $results_tags   = $this->_sort_matches($results_tags, '4', 'cosa_serve');
        $results_tags_only = $this->_sort_matches($results_tags_only, '3', 'cosa_serve');
        $results_tipo   = $this->_sort_matches($results_tipo, '2', 'cosa_serve');
        $results        = $this->_sort_matches($results, '1', 'cosa_serve');
        //sort..
        //        
        $richieste = array_merge($results_tags, $results_tags_only, $results_tipo, $results);
           
        return array_slice($richieste,0,10,true);
    }
    
    

}

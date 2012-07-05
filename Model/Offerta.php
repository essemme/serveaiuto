<?php

App::uses('AppModel', 'Model');

/**
 * Offerta Model
 *
 * @property User $User
 * @property Tipo $Tipo
 */
class Offerta extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'nome';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'offerta' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
//        'sito' => array(
//            'sito' => array(
//                'rule' => array('url'),
//            //'message' => 'Your custom message here',
//            //'allowEmpty' => false,
//            //'required' => false,
//            //'last' => false, // Stop validation after this rule
//            //'on' => 'create', // Limit validation to 'create' or 'update' operations
//            ),
//        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
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
        )
    );

//        
//        /**
// * hasMany associations
// *
// * @var array
// */
//	public $hasMany = array(
//		'OfferteProvince' => array(
//			'className' => 'OfferteProvince',
//			'foreignKey' => 'offerta_id',
//                        
//			'dependent' => false,
//			'conditions' => '',
//			'fields' => '',
//			'order' => '',
//			'limit' => '',
//			'offset' => '',
//			'exclusive' => '',
//			'finderQuery' => '',
//			'counterQuery' => ''
//		),
//		
//	);

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Provincia' => array(
            'className' => 'Provincia',
            'joinTable' => 'offerte_province',
            'foreignKey' => 'offerta_id',
            'associationForeignKey' => 'provincia_id',
            'unique' => 'keepExisting',
            'with' => 'OfferteProvince',
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
            'joinTable' => 'offerte_tags',
            'foreignKey' => 'offerta_id',
            'associationForeignKey' => 'tag_id',
            'unique' => 'keepExisting',
            'with' => 'OfferteTags',
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

    
    
    /**
     *
     * @param type $richiesta the record we are matching against
     * @return false if not a valid request, or array of matching offers sorted by relevance  
     */
    public function suggerisci_offerte ($richiesta) {
        if(!is_array($richiesta)) return false;
        //just to recap.. set variables form the relavant values of the record we are matching against
        $this->titolo   = $richiesta['Richiesta']['cosa_serve'];
        $this->testo    = $richiesta['Richiesta']['testo'];
        $tipo_id = $richiesta['Richiesta']['tipo_id'];
        $categoria_id = $richiesta['Richiesta']['categoria_id'];
        
        //trick for using conditions in related habtm / hasmany model
        $this->bindModel(array('hasOne' => array('OfferteTags')), false);
        
        //default conditions        
        $conditions = array(
            'Offerta.completa' => 0,
            'Offerta.categoria_id' => $categoria_id,
            'Offerta.tipo_id' => $tipo_id
        );
        $contain = array('OfferteTags','Provincia','Tag','Tipo','Categoria');
        
        $tags = $richiesta['Tag'];
        if( !empty ($tags) ) {
            foreach ($tags as $tag) {
                $tags_id[] = $tag['id'];
            }            
            $conditions['OfferteTags.tag_id'] = $tags_id;
            
            //conta risultati per query più selettiva (conicidono tipo, categoria, tags)
            $results_tags = $this->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain 
            ));
            
            $exclude_ids = Set::extract('/Offerta/id', $results_tags);
            
            
            unset($conditions['Offerta.categoria_id']);
            if(is_array($exclude_ids)) $conditions['not']['Offerta.id'] = $exclude_ids;
                        
            $results_tags_only = $this->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain 
            ));
            $exclude_ids = am($exclude_ids, Set::extract('/Offerta/id', $results_tags_only));
            
        }
        
                
        if(is_array($exclude_ids)) $conditions['not']['Offerta.id'] = $exclude_ids;
        if (isset($results_tags) && count($results_tags) < 10) {
            unset($conditions['Offerta.categoria_id']);
            
            $results_tipo = $this->find('all', array(
                'conditions' => $conditions,
                'contain' =>  $contain
            ));
        }
        
        if (count ($results_tipo) < 10) {
            unset($conditions['OfferteTags.tag_id']);
            
            $results = $this->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain
            ));
        }
        
        
        
        //first macro sort is by group (records found with strict conditions fisrt, with luosy conditions later)
        //then sort each group by relevance
        $results_tags   = $this->_sort_matches($results_tags, '4', 'offerta');
        $results_tags   = $this->_sort_matches($results_tags_only, '3', 'offerta');
        $results_tipo   = $this->_sort_matches($results_tipo,'2', 'offerta');
        $results        = $this->_sort_matches($results, '1', 'offerta');
        //sort..
        //        
        $offerte = array_merge($results_tags,$results_tipo, $results);
           
        return array_slice($offerte,0,10,true);
    }
    
    
    
}

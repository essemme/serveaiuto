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
	);        
        
        
        public function esportabili($provincia_id = null) {
            
            $conditions ['Richiesta.completa'] = 0;  
            $conditions ['Richiesta.segnala_in_indice_sito'] = 1;   
            
            if(!is_null($provincia_id)) {
                $this->bindModel(array('hasOne'=>array('ProvinceRichieste')), false);
                //$this->paginate['Richiesta']['group'] = 'Richiesta.id';
                $conditions['ProvinceRichieste.provincia_id'] = $provincia_id;
                
            }
            $esportabili = $this->find('all', array(
                'conditions' => $conditions,
                'fields' => array('id', 'cosa_serve', 'categoria_id', 'tipo_id'),
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
        
}

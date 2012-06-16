<?php
App::uses('AppModel', 'Model');
/**
 * Categoria Model
 *
 * @property Offerta $Offerta
 * @property Richiesta $Richiesta
 */
class Categoria extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'categoria';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'categoria' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Offerta' => array(
			'className' => 'Offerta',
			'foreignKey' => 'categoria_id',
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
		'Richiesta' => array(
			'className' => 'Richiesta',
			'foreignKey' => 'categoria_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}

<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Media $Media
 * @property Project $Project
 */
class User extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';
        
        // using this plugin as a shortcut to registration / password reset features, etc.  
        // https://github.com/voidet/sign_me_up
        public $actsAs = array('SignMeUp.SignMeUp');
        // so, don't define validation rules here, behavior's defaults are fine
        

/**
 * Validation rules
 *
 * @var array
 */
//	public $validate = array(
//		'username' => array(
//			'minLength' => array(
//				'rule' => array('minLength',4),
//				'message' => 'At least 4 chars',
//				//'allowEmpty' => false,
//				//'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
//		),
//		'password' => array(
//			'alphanumeric' => array(
//				'rule' => array('alphanumeric'),
//				//'message' => 'Your custom message here',
//				'allowEmpty' => false,
//				'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
//                        'minLength' => array(
//				'rule' => array('minLength',6),
//				'message' => 'At least 6 chars',
//				'allowEmpty' => false,
//				'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
//		),
//		'email' => array(
//			'email' => array(
//				'rule' => array('email'),
//				//'message' => 'Your custom message here',
//				'allowEmpty' => false,
//				'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
//		),
//	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Provincia' => array(
			'className' => 'Provincia',
			'foreignKey' => 'provincia_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);
        
        
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Offerta' => array(
			'className' => 'Offerta',
			'foreignKey' => 'user_id',
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
			'foreignKey' => 'user_id',
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

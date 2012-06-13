<?php
App::uses('AppModel', 'Model');
/**
 * OfferteProvince Model
 *
 * @property Provincia $Provincia
 * @property Offerta $Offerta
 */
class OfferteProvince extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'offerte_province';

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
		'Offerta' => array(
			'className' => 'Offerta',
			'foreignKey' => 'offerta_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

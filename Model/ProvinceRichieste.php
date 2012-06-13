<?php
App::uses('AppModel', 'Model');
/**
 * ProvinceRichieste Model
 *
 * @property Provincia $Provincia
 * @property Richiesta $Richiesta
 */
class ProvinceRichieste extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'province_richieste';

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
		'Richiesta' => array(
			'className' => 'Richiesta',
			'foreignKey' => 'richiesta_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

<?php

App::uses('AppModel', 'Model');

/**
 * OfferteTags Model
 *
 * @property Offerta $Offerta
 * @property Tag $Tag
 */
class OfferteTags extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'offerte_tags';

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Offerta' => array(
            'className' => 'Offerta',
            'foreignKey' => 'offerta_id',
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

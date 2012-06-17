<?php

App::uses('AppModel', 'Model');

/**
 * RichiesteTags Model
 *
 * @property Richiesta $Richiesta
 * @property Tag $Tag
 */
class RichiesteTags extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'richieste_tags';

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Tag' => array(
            'className' => 'Tag',
            'foreignKey' => 'tag_id',
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

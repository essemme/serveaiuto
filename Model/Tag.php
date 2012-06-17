<?php

App::uses('AppModel', 'Model');

/**
 * Tag Model
 * 
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Tag extends AppModel {
    public $displayField = 'nome';
    
    public $validate = array (
        'nome' => array(
            'rule' => 'notEmpty'
        ),
        'slug' => array(
            'rule' => '/^[a-z0-9_]$/'
        )
    );
}

<?php

App::uses('AppModel', 'Model');

/**
 * Tag Model
 * 
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Tag extends AppModel {
    public $validate = array (
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'slug' => array(
            'rule' => '/^[a-z0-9_]$/'
        )
    );
}

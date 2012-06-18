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
    
    public static function tagListToTags($tagList)
    {
        $tags = explode(',', $tagList);
        array_walk($tags, 'trim');
        
        return array_unique($tags);
    }
    
    public static function tagsToTagList($tags)
    {
        array_walk($tags, 'trim');
        
        return implode(', ', $tags);
    }
}

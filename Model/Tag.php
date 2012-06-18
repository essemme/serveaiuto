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
    
    /**
     * Converts a comma separated tag list into an array, trimming tags
     * 
     * @param type $tagList
     * @return array
     */
    public static function tagListToTags($tagList)
    {
        $tags = explode(',', $tagList);
        array_walk($tags, 'trim');
        
        return array_unique($tags);
    }
    
    /**
     * Converts a tag array to a comma separated list, trimming tags
     * 
     * @param array $tags
     * @return string
     */
    public static function tagsToTagList(array $tags)
    {
        array_walk($tags, 'trim');
        
        return implode(', ', $tags);
    }
}

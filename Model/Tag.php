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
        // otherwise function will return an empty string as first element
        if(strlen(trim($tagList)) === 0) {
            return array();
        }
        
        // for some weird reason array_walk won't work with trim
        $tags = array();
        foreach(explode(',', $tagList) as $tag) {
            // avoids to use array_unique later
            if(!in_array($tag, $tags)) {
                $tags[] = trim($tag);
            }
        }
        
        return $tags;
    }
    
    /**
     * Converts a tag array to a comma separated list, trimming tags
     * 
     * @param array $tags
     * @return string
     */
    public static function tagsToTagList(array $tags)
    {
        // array_walk not working
        foreach($tags as $key => $tag) {
            $tags[$key] = trim($tag);
        }
        
        return implode(', ', array_unique($tags));
    }
}

<?php

App::uses('AppModel', 'Model');

/**
 * Tag Model
 * 
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Tag extends AppModel {

    public $displayField = 'nome';
    public $validate = array(
        'nome' => array(
            'rule' => 'notEmpty'
        ),
        'slug' => array(
            'rule' => 'notEmpty'
        )
    );

    /**
     * Converts a comma separated tag list into an array, trimming tags
     * 
     * @param type $tagList
     * @return array
     */
    public static function tagListToTags($tagList) {
        // otherwise function will return an empty string as first element
        if (strlen(trim($tagList)) === 0) {
            return array();
        }

        // for some weird reason array_walk won't work with trim
        $tags = array();
        foreach (explode(',', $tagList) as $tag) {
            // avoids to use array_unique later
            if (!in_array($tag, $tags)) {
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
    public static function tagsToTagList(array $tags) {
        // array_walk not working
        foreach ($tags as $key => $tag) {
            $tags[$key] = trim($tag);
        }

        return implode(', ', array_unique($tags));
    }

    /**
     * Finds a tag by name or creates it
     * 
     * @param string $nome
     * @return int tag id
     */
    public function findOrCreate($nome) {
        $tag = $this->findByNome($nome);
        
        if ($tag !== false) {
            return $tag['Tag']['id'];
        }

        $slug = $oldSlug = Inflector::slug($nome);
        
        $i = 1;
        
        while ($this->findBySlug($slug) !== false) {
            $slug = $oldSlug . $i++;
        }
        
        $this->create(array('nome' => $nome, 'slug' => $slug));
        $data = $this->save();
        return $data[0];
    }

}

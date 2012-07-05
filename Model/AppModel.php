<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    
    public $actsAs = array('Containable');
    
    //ways to store useful properties for offers and requests -mainly for matching purposes
    public $titolo;
    
    public $testo;
    
    
    
    
    /**
     *
     * @param array $data_array     * 
     * @param string $sort_order (an integer as string)  main "group" sort - i.e. keep records matching by tags end type and category before those matching by type and category..
     * @param string $text_field  name of the field (of the current model) we use to look for word macthes (with main field of the related model)
     * @return array key sorted array, by word matches and then similar text percentage 
     */
    protected function _sort_matches ($data_array, $sort_order, $text_field = 'offerta') {
        if(empty($data_array)) return array();
        
        $search_text    = $this->titolo;
        $testo          = $this->testo;
        
        
        //prendi le parole di 4 lettere o più..
        preg_match_all('/([a-zA-Z0-9-_]{4,}+)/', $search_text, $parole);
        $parole = $parole[0];
        
        //check for word matches
        foreach($data_array as $oid => $data) {     
            
            $matches = 0;
            foreach ($parole as $parola) {
                if(strpos(' ' . $data[$this->alias][$text_field], $parola)) $matches++;
            } 
            
            //set to 5 digits
            if($matches == 0) { $matches = '00000'; }
            else {
                $missing = (5 - strlen($matches));
                for($i = 1; $i < $missing; $i++) {
                    $matches = '0'.$matches;
                }
            }
            
            //save additional field
            $data_array[$oid][$this->alias]['match'] = $matches; 
            
            // get similar text percentage (# of changed charachters..)
            $pc = 0;
            similar_text($data[$this->alias][$text_field], $testo, $pc);
            $data_array[$oid][$this->alias]['quoziente'] = $pc;
            
            $data_array[$sort_order.'-'.$matches.'-'.$pc] = $data_array[$oid];
            unset($data_array[$oid]);
        }
        krsort($data_array);
        return $data_array;
    }
    
}

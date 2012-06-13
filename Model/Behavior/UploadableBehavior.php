<?php
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');

/**
 * still have to refactor and generalize it.. now it's meant for the specific request  
 * a lot could be done..
 */
class UploadableBehavior extends ModelBehavior { 
    
                
        public $defaultOptions = array(		
		'allowedMime' => array('image/jpeg','application/pdf', 'application/x-pdf', 'application/acrobat', 'applications/vnd.pdf', 'text/pdf', 'text/x-pdf'),
                'sizeLimit' => 2097152, // 2MB
		'fields' => array(
                        'input' => 'name',
			'filename' => 'name',
			'dirname' => 'dirname',
			'user_id' => 'user_id'
		),
                'directory' => 'uploads'
	);
                
        public $actualOptions = array();
        
        public $actual_file = null;
        
        public $folder = null;
        
        /**
         * merge defaults options with passed behavior settings; set (or create) uploads folder
         * @param type $model
         * @param type $settings 
         */
        function setup(Model $model, $settings = array()) {     
                
                $this->actualOptions = am($this->defaultOptions, $settings);		
                $Folder = new Folder(WWW_ROOT.$this->actualOptions['directory'],true);
                $this->folder =  $Folder->pwd();
	}
        
        /**
         * sotres info for file dletion in afterDelete
         * @param Model $model
         * @param type $cascade
         * @return boolean 
         */
        public function beforeDelete(Model $model, $cascade = true) {
                
                $data = $model->read(null, $model->id);   
                
		if (isset($data) && !empty($data)) {
                       $this->actual_file = $model->data[$model->alias] [$this->actualOptions['fields']['filename']];  
                
		}
		return true;           
        }
        
        /**
         *if delete from bd successful, remove the file
         * @param Model $model 
         */
        public function afterDelete(Model $model) {
            
            $file_path = $this->folder . DS .$this->actual_file;
            
            $File = new File($file_path);
            $File->delete();
            parent::afterDelete($model);
        }


        public function beforeValidate(Model $model) {
            if(!$this->_validateFile($model, $model->data[$model->alias][$this->actualOptions['fields']['input']])) {
                return false;
            }
            return parent::beforeValidate($model);
        }
        
        /**
         * checks if file with same name already exists, then rename; sets addditional fields 
         * @param Model $model
         * @param type $options
         * @return boolean 
         */
        public function beforeSave(Model $model,  $options = array() ) {
            //check same name file exists
            $filename = $model->data[$model->alias] [$this->actualOptions['fields']['input']] ['name'];            
            $already_here = $model->find('first', array('conditions' => array('name' => $filename)));
            if($already_here[$model->alias]['id']) {
                $filename = 'new_'.$filename;
                $model->data[$model->alias] [$this->actualOptions['fields']['input']] ['name'] = $filename;
            }
            
            if(!$this->saveFile($model)) {
                return false;
            }
            //set additional fields
            $model->data[$model->alias][$this->actualOptions['fields']['filename']] = $model->data[$model->alias][$this->actualOptions['fields']['input']] ['name'];
            $model->data[$model->alias][$this->actualOptions['fields']['dirname']] = $this->folder;
            
            return parent::beforeSave($model, $options);
        }
    
        /*
         * actually saves (moves temp) the files to the uploads (or custom) folder
         */
        public function saveFile(Model $model) {
            $file_data = $model->data[$model->alias][$this->actualOptions['fields']['input']];
            //debug($this->folder);
            if($this->_moveUploadedFile($file_data['tmp_name'], $this->folder . DS .$file_data['name'] )) {
                return true;
            }            
            return false;            
        }
        
        
        /**
         * check mime type and size; invalidate if something's not ok
         * 
         * @param  $file_array 
         */
        public function _validateFile (Model $model, $file_array = array()) {           
            
            if(!in_array($file_array['type'], $this->actualOptions['allowedMime'])) {
                $model->validationErrors[$this->actualOptions['fields']['input']] = 'Mime type not allowed'; 
                return false;
            }
            
            if($file_array['size'] > $this->actualOptions['sizeLimit']) {
                $model->validationErrors[$this->actualOptions['fields']['input']] = 'File toot big'; 
                return false;
            }
            return true;
        }
        
                
/**
 * Move Uploaded file from temporary directory to destination
 *
 * @param string $tmpName path to temporary file
 * @param string $saveAs path to destination
 * @return mixed true is successful, false otherwise
 * @access protected
 */
	protected function _moveUploadedFile($tmp, $destination) {
		$results = true;		
		$tmpFile         = new File($tmp);
		$destinationFile = new File($destination, true);
		if (!$destinationFile->write($tmpFile->read())) {
			$results = false;
		}
		$destinationFile->close();
		$tmpFile->close();                
		return $results;
	}

        
}

<?php
namespace Library;

class FileStore {
    
    private $archivos;
    public function __construct($name){
        $this->archivos=$name;
    }

    public function save(array $file){
        if (empty($file)){
            return False;
        }
        $save= implode ("\n", $file);
        file_put_contents($this->archivos,$save);
        if (!empty ($this->archivos)){
	        return True;
        }
        
    }

    public function read(){
        if (!file_exists($this->archivos)){
            touch($this->archivos);
        }
        $file=file_get_contents($this->archivos);
        if (empty($file)){
            return False;
        }
        $retorno= explode ("\n", $file);
        return $retorno;
        }   
    }
    

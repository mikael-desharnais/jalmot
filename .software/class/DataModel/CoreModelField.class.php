<?php 
class CoreModelField{
    protected $name;
    protected $type;
    protected $model;
    protected $isPrimaryKey;
    protected $params;
    protected $encryptor;
    
    public function __construct($model,$name,$type,$isPrimaryKey){
        $this->model=$model;
        $this->name=$name;
        $this->type=$type;
        $this->isPrimaryKey=$isPrimaryKey;
    }
    public function getCode(){
        return 'protected $'.strtolower($this->name).";\n";
    }
    public function getName(){
    	return $this->name;
    }
    public function getType(){
    	return $this->type;
    }
    public function getModel(){
        return $this->model;
    }
    public function isPrimaryKey(){
        return $this->isPrimaryKey;
    }
    public function setParams($params){
        $this->params=$params;
    }
    public function getParams(){
        return $this->params;
    }
    public function getEncryptor($value){
        return new $this->encryptor($value);
    }
    public function setEncryptor($encryptorName){
        $this->encryptor=$encryptorName;
    }
}


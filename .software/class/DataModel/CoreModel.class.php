<?php 
class CoreModel{
    private static $models=array();
    public static function getModel($name){
        if (!array_key_exists($name, self::$models)){
            self::loadModel($name);
        }
        return self::$models[$name];
    }
    public static function loadModel($name){
        $xml=XMLDocument::parseFromFile(new File("xml/model",$name.".xml",false));
        $model=new Model($name);
        foreach($xml->fields->children() as $field){
            $model_field=new ModelField($model,$field->name."",$field->type."",(isset($field->primary_key)&&$field->primary_key.""==1?true:false));
			$params=XMLParamsReader::read($field);
            $model_field->setParams($params);
            if (!empty($field->encryptor)){
	            $model_field->setEncryptor($field->encryptor."");
            } 
        	$model->addField($model_field);
        }
        self::$models[$name]=$model;
		if (count($xml->relations)>0){
	        foreach($xml->relations->children() as $relation){
	            $source=$model->getField($relation->source."");
	            $destination=Model::getModel($relation->destination->model."")->getField($relation->destination->field."");
	           	$model->addRelation(new ModelRelation($relation->name."",$source,$destination,($relation->type."")));
	        }
		}
    }
    
    public $name;
    public $fields=array();
    public $relations=array();
    
    public $cachedInstances=array();

    public function __construct($name){
        $this->name=$name;
    }
    public function addField($field){
        $this->fields[strtolower($field->getName())]=$field;
    }
    public function addRelation($relation){
        $this->relations[strtolower($relation->getName())]=$relation;
    }
    public function getInstance(){
        if (!class_exists($this->name)){
        	$this->includeClass();
        }
        return new $this->name($this);
    }
    public function getInstanceWithData($params){
        if (in_array(serialize($params),$this->cachedInstances)){
            return $this->cachedInstances[serialize($params)];
        }
        $instance=$this->getInstance();
        foreach($params as $key=>$value){
            $setter="set".ucfirst($key);
            $instance->$setter($value);
        }
        $this->cachedInstances[serialize($params)]=$instance;
        return $instance;
        
    }
    public function includeClass(){
    	$code="
    	class Core".$this->name." extends ModelData{
    	";
    	foreach($this->fields as $field){
    	    $code.=$field->getCode();
    	}
    	$code.="\n}";
    	eval($code);
    	$name=$this->name;
		if (file_exists('override/model/'.$this->name.'.class.php')){
			include('override/model/'.$this->name.'.class.php');
		}else {
			eval('class '.$this->name.' extends Core'.$this->name.' {}');
		}
    	
    }
    public function getField($name){
		if (!array_key_exists(strtolower($name),$this->fields)){
			Log::Error('Trying to access to unknown Model Field "'.$this->name.'"."'.strtolower($name).'"');
		}
        $toReturn= $this->fields[strtolower($name)];
        return $toReturn;
    }
    public function getFields(){
        return $this->fields;
    }
    public function getName(){
        return $this->name;
    }
    public function getRelation($name){
        $toReturn= $this->relations[strtolower($name)];
        return $toReturn;
    }
    public function getRelations(){
        return $this->relations;
    }
}


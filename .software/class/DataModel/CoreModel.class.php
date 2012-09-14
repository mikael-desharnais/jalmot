<?php
/**
* Model Class, A Model is a data structure.
* It may be compared to a Table in Mysql
* A model has fields and has relations with other models
*/
class CoreModel{
    /**
    * Static list of all models
    */
    private static $models=array();
    /**
    * Returns the model object corresponding to the given name
    * @return Model The model Object corresponding to the given name
    * @param string $name name of the module to return
    */
    public static function getModel($name){
        if (!array_key_exists($name, self::$models)){
            self::loadModel($name);
        }
        return self::$models[$name];
    }
    /**
    * Load a model from XML File : xml/model/[$name].xml
    * @param string $name name of the model to load
    */
    public static function loadModel($name){
        $xml=XMLDocument::parseFromFile(new File("xml/model",$name.".xml",false));
        $model=new Model($name,DataSource::getDataSource($xml->datasource.""));
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
    /**
    * Name of the model
    */
    public $name;
    /**
    * Fields Contained by the model
    */
    public $fields=array();
    /**
    * Relations between this model and other models
    */
    public $relations=array();
    
    protected $datasource;
    
    /**
    * Initialises the name of the model
    * @param string $name Nom du mod�le
    */
    private function __construct($name,$datasource){
        $this->name=$name;
        $this->datasource =$datasource;
    }
    public function getDatasource(){
        return $this->datasource;
    }
    /**
    * Add a field to the model
    * @param ModelField $field Field object to add to the model
    */
    public function addField($field){
        $this->fields[strtolower($field->getName())]=$field;
    }
    /**
    * Add a relation to the Model
    * @param ModelRelation $relation Relation object to add to the model
    */
    public function addRelation($relation){
        $this->relations[strtolower($relation->getName())]=$relation;
    }
    /**
    * Returns an Instance of ModelData corresponding  to this Model
    * @return ModelData an Instance of ModelData corresponding  to this Model
    */
    public function getInstance(){
        if (!class_exists($this->name)){
        	$this->includeClass();
        }
        return new $this->name($this);
    }

    /**
    * Creates the class of the ModelData used by this Model,
    *  a class called after the name of this model and extending ModelData
    * There is a possibility to override this class with a file named override/model/($name).class.php
    */
    public function includeClass(){
        $file = new File(".cache/class/model","Core".$this->name.'.class.php',false);
        if (!$file->exists()){
            $code="<?php
	    	class Core".$this->name." extends ModelData{
	    	";
	    	foreach($this->fields as $field){
	    	    $code.=$field->getCode();
	    	}
	    	$code.="\n}";
	    	@mkdir($file->getDirectory(),0777,true);
	    	$file->write($code);
        }
        include_once($file->toURL());
		if (file_exists('override/model/'.$this->name.'.class.php')){
			include('override/model/'.$this->name.'.class.php');
		}else {
			eval('class '.$this->name.' extends Core'.$this->name.' {}');
		}
    	
    }
    /**
    * Returns a field corresponding to the given name
    * @return ModelField The field corresponding to the given name
    * @param string $name the name of the field to return
    */
    public function getField($name){
		if (!array_key_exists(strtolower($name),$this->fields)){
			Log::Error('Trying to access to unknown Model Field "'.$this->name.'"."'.strtolower($name).'"');
		}
        $toReturn= $this->fields[strtolower($name)];
        return $toReturn;
    }
    /**
    * Returns all the fields of the Model
    * @return array All the fields of the Model
    */
    public function getFields(){
        return $this->fields;
    }
    /**
    * Returns the name of the Model
    * @return string The name of the Model
    */
    public function getName(){
        return $this->name;
    }
    /**
    * Returns the ModelRelation corresponding to the name given
    * @return ModelRelation  the ModelRelation corresponding to the name given
    * @param string $name The name of the relation to return
    */
    public function getRelation($name){
        if (!array_key_exists(strtolower($name), $this->relations)){
            Log::Error('Trying to use relation '.$name.' that cannot be found for model '.$this->name);
        }
        $toReturn= $this->relations[strtolower($name)];
        return $toReturn;
    }
    /**
    * Returns all the relations of the Model
    * @return array all the relations of the Model
    */
    public function getRelations(){
        return $this->relations;
    }
}


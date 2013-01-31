<?php
/**
* This class represents a field in a Model.
* It is the equivalent of a column in a mysql table
*/
class CoreModelField{
	
	public static function readFromXML($model,$class,$xml){
		$toReturn = new $class($model,$xml->name."",$xml->type."",(isset($xml->primary_key)&&$xml->primary_key.""==1?true:false));
		$params=XMLParamsReader::read($xml);
		$toReturn->setParams($params);
		if (!empty($xml->encryptor)){
			$toReturn->setEncryptor($xml->encryptor."");
		}
		return $toReturn;
	}
	
    /**
    * The name of this field
    */
    protected $name;
    /**
    * The type of this field (string, integer ...)
    */
    protected $type;
    /**
    * The model that contains this field
    */
    protected $model;
    /**
    * True if this field is part of the primary key for the Model
    */
    protected $isPrimaryKey;
    protected $params;
    /**
    * The encryptor's name for this field
    */
    protected $encryptor;
    /**
    * Initialises, the model, the name, the type and the primary key status of this Field
    * @param Model $model The Model that contains this Field
    * @param string $name the name of this field
    * @param string $type the type of this field
    * @param boolean $isPrimaryKey True if this Field is part of a primary key for his Model
    */
    public function __construct($model,$name,$type,$isPrimaryKey){
        $this->model=$model;
        $this->name=$name;
        $this->type=$type;
        $this->isPrimaryKey=$isPrimaryKey;
    }
    /**
    * Returns the PHP code for this Field 
    * This is used to generate the Class
    * @return string the PHP code for this Field 
    */
    public function getCode(){
        return 'protected $'.strtolower($this->name).";\n";
    }
    /**
    * Returns the name of this field
    * @return string the name of this field
    */
    public function getName(){
    	return $this->name;
    }
    /**
    * Returns the type of this field
    * @return string the type of this field
    */
    public function getType(){
    	return $this->type;
    }
    /**
    * Returns the Model that contains this Field
    * @return Model the Model that contains this Field
    */
    public function getModel(){
        return $this->model;
    }
    public function setModel($model){
        $this->model = $model;
    }
    /**
    * Returns true if this field is a part of the primary Key for his Model
    */
    public function isPrimaryKey(){
        return $this->isPrimaryKey;
    }
    /**
    * Defines the array of parameters of this Field
    * @param array $params the array of parameters of this Field
    */
    public function setParams($params){
        $this->params=$params;
    }
    /**
    * Returns the array of parameters of this Field
    * @return array the array of parameters of this Field
    */
    public function getParams(){
        return $this->params;
    }
    /**
    * Returns the encrypted value of the one given has parameter
    * @return string  the encrypted value of the one given has parameter
    * @param string $value the value to encrypt
    */
    public function getEncryptedValue($value){
        return new $this->encryptor($value);
    }
    /**
    * Defines the Encryptor name for this Field
    * 
    * @param string $encryptorName The name of the encryptor to use for this field
    */
    public function setEncryptor($encryptorName){
        $this->encryptor=$encryptorName;
    }
}




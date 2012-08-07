<?php
/**
* Mysql Implementation of the DataSource Class
*/
 class CoreMysqlDataSource extends DataSource{
     /**
     * The MySQL connection objet
     */
     private $dbConnection;
     /**
     * Calls its parent with its name
     * loads configuration parameters from  XML File : xml/dataSource/[DATASOURCE_NAME].xml
     * loads the arrays that contain the data to translate from model names to mysql names
     * @param string $name name of the datasource
     */
     public function __construct($name){
         parent::__construct($name);
         $xml=XMLDocument::parseFromFile(new File("xml/dataSource",$name.".xml",false));
         $this->dbConnection=new Mysql($xml->server."",$xml->database."",$xml->username."",$xml->password."");
         $this->loadDbToModelData();
     }
     /**
     * Returns a new Instance of MysqlDataQuery with proper type and Model.
     * 
     * @return MysqlDataQuery a new Instance of MysqlDataQuery with proper type and Model.
     * @param integer $type the type of Query required (see CoreModelDataQuery)
     * @param Model $model the model targetted by this Query
     */
     public function getModelDataQuery($type,$model){
         return new MysqlDataQuery($type,$model,$this);
     }
     /**
     * Executes a ModelDataQuery
     * @return array An array of DataModel that correspond to the Model
     * @param ModelDataQuery $query The ModelDataQuery to execute
     */
     public function execute($query){
         if ($query->getType()==ModelDataQuery::$SELECT_QUERY){
			$raw_query=$query->getSQL();
         	$resultset=$this->dbConnection->query($raw_query);
         	$toReturn = new ModelDataCollection();
         	while($line=$this->dbConnection->fetchAssoc($resultset)){
         	    $instance=$query->getModel()->getInstance();
         	    $instance->source=ModelData::$SOURCE_FROM_DATASOURCE;
				$instance->data_source=$this;
         	    foreach($line as $key=>$val){
         	        $function="set".ucfirst(strtolower($this->getModelFieldName($this->getTableName($query->getModel()->getName()),$key)));
         	        $instance->$function($val);
         	    }
         	    $toReturn->addModelData($instance);
         	}
         }
        return $toReturn;
     }
     /**
     * Updates Mysql data using a ModelData as source
     * @param ModelData $element The ModelData to use as source
     */
     public function update($element){
		$model=$element->getParentModel();
         $query="UPDATE ".$this->getTableName($model->getName())." SET ";
		 $set="";
         $where="";
         foreach($model->getFields() as $field){
             $fieldName="get".ucfirst($field->getName());
             if ($field->isPrimaryKey()){
                 $where.=($where==""?" WHERE ":" AND ")." `".$this->getDBFieldName($model->getName(),$field->getName())."`='".$this->dbConnection->escapeString($element->$fieldName())."' \n";
             }else {
             	$set.=($set==""?"":",")." `".$this->getDBFieldName($model->getName(),$field->getName())."`='".$this->dbConnection->escapeString($element->$fieldName())."' \n";
             }
         }
         $query.=$set." ".$where;
       	 $this->dbConnection->query($query);
     }
     /**
     * Deletes Mysql data using a ModelData as reference
     * @param ModelData $element The ModelData to use as reference
     */
     public function delete($element){
		$model=$element->getParentModel();
         $query="DELETE FROM ".$this->getTableName($model->getName());
         $where="";
         foreach($model->getFields() as $field){
             $fieldName="get".ucfirst($field->getName());
             if ($field->isPrimaryKey()){
                 $where.=($where==""?" WHERE ":" AND ")." `".$this->getDBFieldName($model->getName(),$field->getName())."`='".$this->dbConnection->escapeString($element->$fieldName())."' \n";
             }
         }
         $query.=" ".$where;
       	 $this->dbConnection->query($query);
     }
     /**
     * Creates Mysql data using a ModelData as source
     * @param ModelData $element The ModelData to use as source
     */
     public function create($element){
		$model=$element->getParentModel();
         $query="INSERT INTO ".$this->getTableName($model->getName())." (";
         $fields="";
         $values="";
         foreach($model->getFields() as $field){
             $fieldName="get".ucfirst($field->getName());
             $fields.=($fields==""?"":",")." `".$this->getDBFieldName($model->getName(),$field->getName())."`";
             $values.=($values==""?"":",")."'".$this->dbConnection->escapeString($element->$fieldName())."' \n";
         }
         $query.= $fields.') values('.$values.')';
       	 $this->dbConnection->query($query);
       	 
       	 foreach($model->getFields() as $field){
       	     if (array_key_exists('autoincrement',$this->params[$model->getName()][$field->getName()])&&$this->params[$model->getName()][$field->getName()]['autoincrement']==1){
       	         $fieldName="set".ucfirst($field->getName());
       	         $element->$fieldName($this->dbConnection->getInsertedId());
       	     }
       	 }
     }
	/**
	* Returns a ModelDataQueryCondition corresponding to the symbol given as first parameter
	* @return ModelDataQueryCondition  a ModelDataQueryCondition corresponding to the symbol given as first parameter
	* @param array $args All The arguments passed
	*/
	public function getConditionBySymbol($args){
		switch($args[0]){
			case "=" :
				return new MysqlEqualCondition($args[1],$args[2]);
			break;
		}
	}
	/**
	* Array to translate Mysql field names into Model field names
	*/
	public $modelFieldNames=array();
	/**
	* Array to translate Mysql table names into Model names
	*/
	public $modelNames=array();
	/**
	* Array to translate  Model field names into Mysql field names
	*/
	public $dbFieldNames=array();
	/**
	* Array to translate  Model names into Mysql table names
	*/
	public $dbTableNames=array();
	/**
	* Array of configuration parameters
	*/
	public $params=array();
	/**
	* Load the data to translate names between Mysql And Models
	*/
	private function loadDbToModelData(){	    
	    $directory = glob ( "xml/dataSource/".$this->getName()."/*.xml" );
	    foreach($directory as $file){
		    $xml=XMLDocument::parseFromFile(File::createFromURL($file));
		    $this->modelNames[$xml->dbname.""]=$xml->name."";
		    $this->modelFieldNames[$xml->dbname.""]=array();
		    $this->dbTableNames[$xml->name.""]=$xml->dbname."";
		    $this->dbFieldNames[$xml->name.""]=array();
		    foreach($xml->fields->children() as $field){
		        $this->dbFieldNames[$xml->name.""][$field->name.""]=$field->dbname."";
		        $this->dbFieldNames[$xml->dbname.""][$field->dbname.""]=$field->name."";
				$this->params[$xml->name.""][$field->name.""]=XMLParamsReader::read($field);
		    }
	    }
	}
	/**
	* Return the name of the table corresponding to a model Name
	* @return string  the name of the table corresponding to a model Name
	* @param string $modelName Model Name to translate
	*/
	public function getTableName($modelName){
	    return $this->dbTableNames[$modelName];
	}
	/**
	* Return the name of the mysql field corresponding to a model field Name
	* @return string the name of the mysql field corresponding to a model field Name
	* @param string $modelName The name of the model containing the field
	* @param string $modelFieldName The field name
	*/
	public function getDbFieldName($modelName,$modelFieldName){
	    return $this->dbFieldNames[$modelName][$modelFieldName];
	}
	/**
	* Return the name of the model field Name corresponding to a mysql field
	* @return string the name of the model field Name corresponding to a mysql field
	* @param string $tableName The name of the mysql table containing the field
	* @param string $dbFieldName The name of the field
	*/
	public function getModelFieldName($tableName,$dbFieldName){
	    if (!array_key_exists($tableName, $this->dbFieldNames)){
	        Log::Error("Can't find table name ".$tableName." in mysql tables description");
	    }else if (!array_key_exists($dbFieldName, $this->dbFieldNames[$tableName])){
	        Log::Error("Can't find field name ".$dbFieldName." in mysql fields description of table ".$tableName);
	    }else {
	    	return $this->dbFieldNames[$tableName][$dbFieldName];
	    }
	}
}


?>

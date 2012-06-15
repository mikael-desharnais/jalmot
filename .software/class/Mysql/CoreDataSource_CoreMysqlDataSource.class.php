<?php

 class CoreMysqlDataSource extends DataSource{
     private $dbConnection;
     public function __construct($name){
         parent::__construct($name);
         $xml=XMLDocument::parseFromFile(new File("xml/dataSource",$name.".xml",false));
         $this->dbConnection=new Mysql($xml->server."",$xml->database."",$xml->username."",$xml->password."");
         $this->loadDbToModelData();
     }
     public function getModelDataRequest($type,$model){
         return new MysqlDataRequest($type,$model,$this);
     }
     public function execute($request){
         if ($request->getType()==ModelDataRequest::$SELECT_REQUEST){
			$query=$request->getSQL();
         	$resultset=$this->dbConnection->query($query);
         	$toReturn=array();
         	while($line=$this->dbConnection->fetchAssoc($resultset)){
         	    $instance=$request->getModel()->getInstance();
         	    $instance->source=ModelData::$SOURCE_FROM_DATASOURCE;
				$instance->data_source=$this;
         	    foreach($line as $key=>$val){
         	        $function="set".ucfirst(strtolower($this->getModelFieldName($this->getTableName($request->getModel()->getName()),$key)));
         	        $instance->$function($val);
         	    }
         	    $toReturn[]=$instance;
         	}
         }
        return $toReturn;
     }
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
	public function getConditionBySymbol($args){
		switch($args[0]){
			case "=" :
				return new MysqlEqualCondition($args[1],$args[2]);
			break;
		}
	}
	
	public $modelFieldNames=array();
	public $modelNames=array();
	public $dbFieldNames=array();
	public $dbTableNames=array();
	public $params=array();
	
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
	
	public function getTableName($modelName){
	    return $this->dbTableNames[$modelName];
	}
	public function getDbFieldName($modelName,$modelFieldName){
	    return $this->dbFieldNames[$modelName][$modelFieldName];
	}
	
	public function getModelFieldName($tableName,$dbFieldName){
	    return $this->dbFieldNames[$tableName][$dbFieldName];
	}
}
?>

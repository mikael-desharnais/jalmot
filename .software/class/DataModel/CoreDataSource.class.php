<?php
 abstract class CoreDataSource{
     private static $DataSource=array();
     public static function addDataSource($data_source){
         self::$DataSource[$data_source->getName()]=$data_source;
     }
     public static function getDataSource($name){
         return self::$DataSource[$name];
     }
     public static function getCurrentDataSource(){
         $defaultDataSourceType=Ressource::getConfiguration()->getValue('DefaultDataSourceType');
         $defaultDataSourceName=Ressource::getConfiguration()->getValue('DefaultDataSourceName');
         if (!in_array($defaultDataSourceName,self::$DataSource)){
             $dataSource=new $defaultDataSourceType($defaultDataSourceName);
             self::addDataSource($dataSource);
         }
         return self::getDataSource($defaultDataSourceName);
     }
     
     private $name;
	 private $cachedQueries=array();
     public function __construct($name){
         $this->name=$name;
         $xml = XMLDocument::parseFromFile(new File("xml/dataSource",$this->name.".xml",false));
         self::addDataSource($this);
     }
     public function getName(){
         return $this->name;
     }
	 public function isDataModelCached($queryUUID){
		return array_key_exists($queryUUID,$this->cachedQueries);
	}
	public function getCachedDataModel($queryUUID){
		return $this->cachedQueries[$queryUUID];
	}
	public function registerDataModelForCache($queryUUID,$dataModel){
		$this->cachedQueries[$queryUUID]=$dataModel;
	}
}
?>

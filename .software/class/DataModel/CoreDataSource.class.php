<?php
/**
* DataSource Abstract Class
* Extend this class to create your own Datasource
* A datasource is the object you should use to access to any DB (never write a Raw Query)
*/
 abstract class CoreDataSource{
     /**
     * Static list of all datasources
     */
     private static $DataSource=array();
     /**
     * Adds a new datasource to the list of datasources
     * The system will use this list to find the one to use
     * @param DataSource $data_source the object of the datasource to add
     */
     public static function addDataSource($data_source){
         self::$DataSource[$data_source->getName()]=$data_source;
     }
     /**
     * returns the datasource corresponding to the name given in parameter
     * @return DataSource The object of the datasource required
     * @param String $name Name of the datasource to return
     */
     public static function getDataSource($name){
         if (!array_key_exists($name,self::$DataSource)){
             self::loadDataSource($name);
         }
         return self::$DataSource[$name];
     }
     public static function loadDataSource($name){
         $xml = XMLDocument::parseFromFile(new File("xml/dataSource",$name.".xml",false));
         $datasourceType = $xml->type."";
         $dataSource = new $datasourceType($name);
     }

     /**
     * Name of the datasource
     */
     private $name;
	 /**
	 * Cached queries for the current datasource, the system manages a cache for queries. A query can be cached if it's specificaly intended to be.
	 */
	 private $cachedQueries=array();
     /**
     * Object constructor
     * It reads the XML file xml/dataSource[$name].xml
     * And creates the corresponding datasource. The datasource is automaticaly added to the list of datasources
     * @param string $name Name of the datasource to load
     */
     public function __construct($name){
         $this->name=$name;
         self::addDataSource($this);
     }
     /**
     * Returns the name of the datasource
     * @return String The name of the datasource
     */
     public function getName(){
         return $this->name;
     }
	 /**
	 * Returns true if the given queryUUID corresponds to a cached QueryResult, false otherwise
	 * @return boolean  true if the given queryUUID corresponds to a cached QueryResult, false otherwise
	 * @param string $queryUUID the query UUID that identifies the cached element
	 */
	 public function isDataModelCached($queryUUID){
		return array_key_exists($queryUUID,$this->cachedQueries);
	}
	/**
	* Returns the QueryResult corresponding to the queryUUID
	* This method does not check if there is a corresponding Query Result : use isDataModelCached before using this one
	* @see DataSource::isDataModelCached($queryUUID)
	* @return Array The Query Result corresponding to the queryUUID
	* @param string $queryUUID the query UUID that identifies the cached element
	*/
	public function getCachedDataModel($queryUUID){
		return $this->cachedQueries[$queryUUID];
	}
	/**
	* Caches a Query Result with its corresponding queryUUID
	* @param string $queryUUID The query UUID that identifies the Query Result to be Cached
	* @param Array $dataModel the Query Result to be Cached
	*/
	public function registerDataModelForCache($queryUUID,$dataModel){
		$this->cachedQueries[$queryUUID]=$dataModel;
	}
	public function getOrderBy($field,$type){
	    return new ModelDataQueryOrderBy($field,$type);
	}
}


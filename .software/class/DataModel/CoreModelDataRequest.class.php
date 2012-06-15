<?php 
abstract class CoreModelDataRequest{
    public static $SELECT_REQUEST=1;
    public static $UPDATE_REQUEST=2;
    public static $INSERT_REQUEST=3;
    
    protected $conditionContainer;
    private $model;
    private $dataSource;
    private $type;
    
    public function __construct($type,$model,$data_source){
        $this->type=$type;
        $this->model=$model;
        $this->dataSource=$data_source;
		$this->setRootConditionContainer();
    }
	public function setRootConditionContainer(){
		$this->conditionContainer=self::getConditionContainerInstance(ModelDataRequestConditionContainer::$MODEL_DATA_REQUEST_CONDITION_CONTAINER_AND);
	}
	public function getUUID(){
		return md5($this->getSQL());
	}
    public function addCondition($condition){
        $this->conditionContainer->addCondition($condition);
        return $this;
    }
	public function addConditionBySymbol($symbol){
		$this->addCondition(call_user_func(array($this->dataSource,"getConditionBySymbol"),func_get_args()));
		return $this;
	}
    public function addConditionContainer($conditionContainer){
        $this->conditionContainer->addConditionContainer($conditionContainer);
        return $this;
    }
    public function getModelData($useCache=false){
		if ($useCache&&$this->dataSource->isDataModelCached($this->getUUID())){
			return $this->dataSource->getCachedDataModel($this->getUUID());
		}else {
    		$array= $this->dataSource->execute($this);
			if ($useCache){
				$this->dataSource->registerDataModelForCache($this->getUUID(),$array);
			}
    		return $array;
		}
    }
    public function getModelDataElement($useCache=false){
        $array=$this->getModelData($useCache);
        return $array[0];
    }
    public function getModel(){
    	return $this->model;
    }
    public function getType(){
        return $this->type;
    }
    public function getConditionContainer(){
        return $this->conditionContainer;
    }
    public function getDataSource(){
        return $this->dataSource;
    }
}


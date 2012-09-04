<?php
/**
* This class describes an order by
* 
*/
class CoreModelDataQueryOrderBy{
	/**
	* Type for the ASC order by
	*/
    public static $ORDER_ASC = 1;
	/**
	* Type for the DESC order by
	*/
    public static $ORDER_DESC = 2;
    
    public $field;
    public $order_type;
    
    /**
     * Initialises the field and order type for this order by
     * @param mixed The field according to order by
     * @param int	the type of the order_type
     */
    public function __construct($field,$order_type){
        $this->field = $field;
        $this->order_type = $order_type;
    }
}




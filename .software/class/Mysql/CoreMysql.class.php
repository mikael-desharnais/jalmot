<?php
/**
* Encapsulating the Mysql connection Code
*/
 class CoreMysql{
	 /**
	 * The reference to the DB connection
	 */
	 private $sql_link;
	 /**
	 * The name of the Mysql Server
	 */
	 private $server='';
	 /**
	 * The username used to connect to DB
	 */
	 private $user="";
	 /**
	 * The password used to connect to DB
	 */
	 private $password="";
	 /**
	 * The database to connect to
	 */
	 private $database="";
	/**
	* Initialises the servername, database, user, password and connects to DB
	*/
	public function __construct($server,$database,$user,$password){
	     $this->server=$server;
	     $this->user=$user;
	     $this->password=$password;
	     $this->database=$database;
	     $this->connect();
	}
	/**
	* Connects to DB and sets charset to UTF8
	*/
	public function connect(){
	    $this->sql_link=mysqli_connect($this->server,$this->user,$this->password) or Log::Error('Could not connect to database : '.mysqli_connect_error());
	    if ($this==false)
	    {
	        $this->mysqli_error_loader(0);
	    }
	    mysqli_select_db($this->sql_link,$this->database);
	    mysqli_query($this->sql_link,"SET NAMES 'utf8'");
	     
	}
	/**
	* Executes a query and returns its result
	* @return ressource The resultset correspoding to the query result
	* @param string $query the query to execute
	*/
	public function query($query){
	    Log::LogData($query, Log::$LOG_LEVEL_INFO);
		$result= mysqli_query($this->sql_link,$query) or die(mysqli_error($this->sql_link));
		return $result;
	}
	/**
	* Fetches a row as an object from a Mysql resultset
	* @return ressource The resultset to read
	* @param object $resultset the object for the row
	*/
	public function fetchObject($resultset){
		return mysqli_fetch_object($resultset);
	}
	/**
	* Fetches a row as an associative array from a Mysql resultset
	* @return ressource The resultset to read
	* @param object $resultset the object for the row
	*/
	public function fetchAssoc($resultset){
		return mysqli_fetch_assoc($resultset);
	}
	/**
	* Executes multiple queries
	* @return boolean True if the execution was succesfull
	* @param string $query the string queries to execute
	*/
	public function multi_query($query){
		return mysqli_multi_query($this->sql_link,$query);
	}
	/**
	* Returns the link ressource to DB
	* @return mysqli the link ressource to DB
	*/
	public function getLink(){
		return $this->sql_link;
	}
	/**
	* Returns the given string escaped
	* @return string  the given string escaped
	* @param string $value the value to escape
	*/
	public function escapeString($value){
	    return mysqli_real_escape_string($this->sql_link,$value);
	}
	/**
	* Returns the last inserted id
	* @return integer the last inserted id
	*/
	public function getInsertedId(){
	    return mysqli_insert_id ($this->sql_link);
	}
}


?>

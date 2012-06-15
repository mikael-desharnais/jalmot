<?php

 class CoreMysql{
	 private $sql_link;
	 private $server='';
	 private $user="";
	 private $password="";
	 private $database="";
	 
	public function __construct($server,$database,$user,$password){
	     $this->server=$server;
	     $this->user=$user;
	     $this->password=$password;
	     $this->database=$database;
	     $this->connect();
	}
	public function connect(){
	    $this->sql_link=mysqli_connect($this->server,$this->user,$this->password) or Log::Error('Could not connect to database : '.mysqli_connect_error());
	    if ($this==false)
	    {
	        $this->mysqli_error_loader(0);
	    }
	    mysqli_select_db($this->sql_link,$this->database);
	    mysqli_query($this->sql_link,"SET NAMES 'utf8'");
	     
	}
	public function query($query){
		$result= mysqli_query($this->sql_link,$query) or die(mysqli_error($this->sql_link));
		return $result;
	}
	public function fetchObject($resultset){
		return mysqli_fetch_object($resultset);
	}
	public function fetchAssoc($resultset){
		return mysqli_fetch_assoc($resultset);
	}
	public function multi_query($query){
		return mysqli_multi_query($this->sql_link,$query);
	}
	public function getLink(){
		return $this->sql_link;
	}
	public function escapeString($value){
	    return mysqli_real_escape_string($this->sql_link,$value);
	}
	public function getInsertedId(){
	    return mysqli_insert_id ($this->sql_link);
	}
}
?>

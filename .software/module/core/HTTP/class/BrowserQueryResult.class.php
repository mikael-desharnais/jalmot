<?php
class BrowserQueryResult{

	private $curl;
	private $browser;
	private $response;
	private $status;
	private $headers;
	
	public function __construct($browser,$curl,$response,$status,$headers){
		$this->browser=$browser;
		$this->curl=$curl;
		$this->response=$response;
		$this->status=$status;
		$this->headers=$headers;
	}
	public function getJSONResponse($message){
		$toReturn = json_decode($this->response);
		if (is_null($toReturn)){
			throw new Exception('Error While trying to read JSON content during the operation : '.$message);
		}
		return $toReturn;
	}
	public function getHTMLResponse($message){
		$doc = new DOMDocument();
		$doc->strictErrorChecking = FALSE;
		@$doc->loadHTML($this->response);
		$html = @simplexml_import_dom($doc);
		if (!is_object($html)){
			throw new Exception('Error While trying to read HTML content during the operation : '.$message);
		}
		return $html;
	}
	public function getXMLResponse($message){
		Log::GlobalLogData($message." ".$this->response,Log::$LOG_LEVEL_INFO);
		$html = @simplexml_load_string($this->response);
		if (!is_object($html)){
			throw new Exception('Error While trying to read XML content during the operation : '.$message);
		}
		return $html;
	}
	public function getResponse(){
		return $this->response;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getHeaders(){
		return $this->headers;
	}
	public function setResponse($response){
		$this->response = $response;
	}
	
}
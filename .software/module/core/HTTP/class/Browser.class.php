<?php
class Browser{

	public static $POST = 1;
	public static $GET = 2;
	public static $PUT = 3;
	
	
	private $charset='utf8';
	private $timeout = 300;
	private $userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.12011-10-16";
	private $cookieDirectory;
	private $cookieFile;
	private $url;
	private $currentCurlRessource;
	
	public $followLocation=true;
	
	public function getCookieFile(){
		return new File($this->cookieDirectory,$this->cookieFile,false);
	}
	
	public function __construct(){
		$this->cookieDirectory = 'tmp/cookies/'.getmypid().'_'.time().'/';
		if (!file_exists($this->cookieDirectory)){
			mkdir($this->cookieDirectory,0777,true);
		}
		$this->cookieFile = 'cookie';
		$this->currentCurlRessource = curl_init();
		curl_setopt($this->currentCurlRessource, CURLOPT_HEADER, false);
		curl_setopt($this->currentCurlRessource, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->currentCurlRessource, CURLOPT_SSLVERSION,3);
		curl_setopt($this->currentCurlRessource, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($this->currentCurlRessource, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($this->currentCurlRessource, CURLOPT_ENCODING, 1);
		curl_setopt($this->currentCurlRessource, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($this->currentCurlRessource, CURLOPT_AUTOREFERER, true );
		curl_setopt($this->currentCurlRessource, CURLINFO_HEADER_OUT, TRUE);
	}
	public function openURL($method,$url,$parameters=array(),$header=array(),$referer=""){
		curl_setopt($this->currentCurlRessource, CURLOPT_URL, $url);
		curl_setopt($this->currentCurlRessource, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($this->currentCurlRessource, CURLOPT_FOLLOWLOCATION, $this->followLocation );
		curl_setopt($this->currentCurlRessource, CURLOPT_TIMEOUT, $this->timeout);
		
		curl_setopt($this->currentCurlRessource, CURLOPT_HTTPHEADER, $header);

		curl_setopt($this->currentCurlRessource, CURLOPT_HTTPGET, false);
		curl_setopt($this->currentCurlRessource, CURLOPT_POST, false);
		if ($method==self::$POST){
			curl_setopt($this->currentCurlRessource, CURLOPT_POST, true);
		}elseif ($method==self::$GET){
			curl_setopt($this->currentCurlRessource, CURLOPT_HTTPGET, true);
		}elseif ($method==self::$PUT){
			curl_setopt($this->currentCurlRessource, CURLOPT_CUSTOMREQUEST, "PUT");
		}
		// Setting cookies
		curl_setopt($this->currentCurlRessource, CURLOPT_COOKIEFILE, $this->cookieDirectory.$this->cookieFile);
		curl_setopt($this->currentCurlRessource, CURLOPT_COOKIEJAR, $this->cookieDirectory.$this->cookieFile);
		
		
		if(!empty($referer)){
			curl_setopt($this->currentCurlRessource, CURLOPT_REFERER, $referer);
		}
		
		if (is_array($parameters)){
			$stringParameters = http_build_query($parameters);
		}else {
			$stringParameters=$parameters;
		}
		if ($method!=self::$GET){
			if($this->charset == "utf8"){
				curl_setopt($this->currentCurlRessource, CURLOPT_POSTFIELDS, utf8_encode($stringParameters));
			}
			else{
				curl_setopt($this->currentCurlRessource, CURLOPT_POSTFIELDS, $stringParameters);
			}
		}
		$response = curl_exec($this->currentCurlRessource);
		
		$status = curl_getinfo($this->currentCurlRessource, CURLINFO_HTTP_CODE)."";
		$browserResponse = new BrowserQueryResult($this,$this->currentCurlRessource,$response,$status,curl_getinfo($this->currentCurlRessource)); 
		if ($status[0]!='2'&&$status[0]!='3'){
			throw new HTTPException($browserResponse,'Error trying to fetch '.$url.', status : '.$status);
		}
		return $browserResponse;
	}
	public function __destruct(){
		@curl_close($this->currentCurlRessource);
		if (file_exists($this->cookieDirectory.$this->cookieFile)){
			@unlink($this->cookieDirectory.$this->cookieFile);
		}
		if (file_exists($this->cookieDirectory)){
			@rmdir($this->cookieDirectory);
		}
	}
	public function test(){
		print("Call ".$this->cookieDirectory.$this->cookieFile."<br>");
		print("<pre>".file_get_contents($this->cookieDirectory.$this->cookieFile)."</pre><br>");
	}
	public function lock(){
		chmod($this->cookieDirectory.$this->cookieFile,0444);
		print('lock Cookie File');
	}
	public function setTimeout($timeout){
		$this->timeout = $timeout;
	}
}
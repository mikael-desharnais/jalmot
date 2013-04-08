<?php
/**
 * Manages the logging system
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
class CoreLog
{
    
    
    /**
     * Log Level for errors
     */
    public static $LOG_MODE_STD_OUTPUT=0;
    /**
     * Log Level for errors
     */
    public static $LOG_MODE_STD_OUTPUT_AND_FILE=1;
    

    /**
     * Log Level for errors
     */
    public static $LOG_LEVEL_SILENT=0;
    /**
     * Log Level for errors
     */
    public static $LOG_LEVEL_ERROR=0;
    
    /**
     * Log Level for warnings
     */
    public static $LOG_LEVEL_WARNING=1;
    
    /**
     * Log Level for debugs
     */
    public static $LOG_LEVEL_DEBUG=2;
    
    /**
     * Log Level for infos
     */
    public static $LOG_LEVEL_INFO=3;
    
    private static $baseLog;

    protected $logLevel=3;
    protected $logMode;
    protected $logFile;
    protected $startTime;
    
    //private $logLevel;
    
    public function __construct(){
    	$this->startTime = microtime(true);
    	
    }
    public function setLogLevel($logLevel){
    	$this->logLevel = $logLevel;
    }
    public function getLogLevel(){
    	return $this->logLevel;
    }
    public function setLogMode($logMode){
    	$this->logMode = $logMode;
    }
    public function setLogFile($file){
    	$this->logFile = $file;
    }
    public function logData($data,$level=3){
	    if ($this->logLevel>=$level){
	    	if ($this->logLevel ==Log::$LOG_LEVEL_ERROR) {
	    		print('<pre>');
	    		print_r($data);
	    		print('</pre>');
	    	}
	    	if ($this->logMode>=Log::$LOG_MODE_STD_OUTPUT_AND_FILE){
	    		$microtime = microtime(true);
	    		$this->logFile->append(date('d M Y H:i:s',$microtime)."\t\t".(round(($microtime-$this->startTime)*1000))."ms\t\t".str_replace("\r","",str_replace("\n","",$data))."\r\n");
	    	}
	    }
    }
    
    /**
     * propagate a log with error log level then logs the stacktrace then sends a die 
     * 
     * @param $message		message to log 
     */
    public static function Error ($message){
        self::GlobalLogData($message, self::$LOG_LEVEL_ERROR);
		throw new Exception($message);
    }    
    /**
     * propagate a log with warning log level then logs the stacktrace 
     * 
     * @param $message		message to log 
     */
    public static function Warning ($message){
        self::GlobalLogData($message, self::$LOG_LEVEL_WARNING);
    }

    public static function Debug ($message){
        self::GlobalLogData($message, self::$LOG_LEVEL_DEBUG);
    }
    /**
     * propagate a log with custom log level if the given level is greater than or equal to the global log level, the message will be displayed 
     * 
     * @param $data		data to log (can be anything (array, boolean ...))
     * @param $level	level of the current log entry 
     */
    public static function GlobalLogData ($data, $level){
        if (empty(self::$baseLog)){
            self::createBaseLog();
        }
        self::$baseLog->logData($data,$level);
    }
    public static function setGlobalLogLevel($logLevelToSet){
    	if (empty(self::$baseLog)){
    		self::createBaseLog();
    	}
    	self::$baseLog->setLogLevel($logLevelToSet);
    }
    public static function getGlobalLogLevel(){
    	if (empty(self::$baseLog)){
    		self::createBaseLog();
    	}
    	return self::$baseLog->getLogLevel();
    }
    private static function createBaseLog(){
    	self::$baseLog = new Log();
    	self::$baseLog->setLogMode(self::$LOG_MODE_STD_OUTPUT_AND_FILE);
    	self::$baseLog->setLogFile(new File('tmp/log','jalmot.log',false));
    }
}
?>

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
     * log level : the minimum log level for data to be take into account
     *
     * @access protected
     * @var int
     */
    protected static $logLevel = 0;
    
    
    protected static $logMode = 1;
    
    
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
    
    private static $startTime; 
    
    
    /**
     * propagate a log with error log level then logs the stacktrace then sends a die 
     * 
     * @param $message		message to log 
     */
    public static function Error ($message){
        self::LogData($message, self::$LOG_LEVEL_ERROR);
        self::LogData(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), self::$LOG_LEVEL_ERROR);
		throw new Exception($message);
    }    
    /**
     * propagate a log with warning log level then logs the stacktrace 
     * 
     * @param $message		message to log 
     */
    public static function Warning ($message){
        self::LogData($message, self::$LOG_LEVEL_WARNING);
        self::LogData(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), self::$LOG_LEVEL_WARNING);
    }
    /**
     * propagate a log with custom log level if the given level is greater than or equal to the global log level, the message will be displayed 
     * 
     * @param $data		data to log (can be anything (array, boolean ...))
     * @param $level	level of the current log entry 
     */
    public static function LogData ($data, $level){
        if (empty(self::$startTime)){
            self::$startTime = microtime(true);
        }
        
        if (self::$logLevel >= $level) {
            print('<pre>');
            print_r($data);
            print('</pre>');
        }
        if (self::$logMode>=Log::$LOG_MODE_STD_OUTPUT_AND_FILE){
	        $file = new File('tmp/log','jalmot.log',false);
	        $microtime = microtime(true);
	        $file->append(date('d M Y H:i:s',$microtime)."\t\t".(round(($microtime-self::$startTime)*1000))."ms\t\t".str_replace("\r","",str_replace("\n","",$data))."\r\n");
        }
    }
}
?>

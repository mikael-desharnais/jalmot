<?php
/**
 * Manages the logging system
 * TODO : Add the possibility to log custom data
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
    
    /**
     * propagate a log with error log level then logs the stacktrace then sends a die TODO : create constants for logging levels 
     * 
     * @param $message		message to log 
     */
    public static function Error ($message){
        self::LogData($message, 0);
        self::LogData(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 0);
        die();
    }    
    /**
     * propagate a log with warning log level then logs the stacktrace 
     * 
     * @param $message		message to log 
     */
    public static function Warning ($message){
        self::LogData($message, 1);
        self::LogData(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 1);
    }
    /**
     * propagate a log with custom log level if the given level is greater than or equal to the global log level, the message will be displayed 
     * 
     * @param $data		data to log (can be anything (array, boolean ...))
     * @param $level	level of the current log entry 
     */
    public static function LogData ($data, $level){
        if (self::$logLevel >= $level) {
            print('<pre>');
            print_r($data);
            print('</pre>');
        }
    }
}
?>

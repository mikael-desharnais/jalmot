<?php
/**
* Date wrapper for dates
*/
date_default_timezone_set("GMT");
 class CoreDate{
     
     public $year;
     public $month;
     public $day;
     public $hour;
     public $minute;
     public $second;
     
     private $timestamp;
     
     
     /**
      * Initialises the 
      * @param array $CSSArray two dimensions array containing the css files to filter
      */
     public function __construct($year,$month,$day,$hour,$minute,$second){
         $this->year = $year;
         $this->month  = $month;
         $this->day  = $day;
         $this->hour = $hour;
         $this->minute= $minute;
         $this->second=$second;
     }
     public static function getNow(){
         return self::parseFromTimestamp(time());
     }
     public static function getEmptyDate(){
         return self::parseFromTimestamp(0);
     }
     public function getDay(){
         return $this->day;
     }
     
     public function getMonth(){
         return $this->month;
     }
     
     public function getYear(){
         return $this->year;
     }
     public function getHour(){
         return $this->hour;
     }
     public function getMinute(){
         return $this->minute;
     }
     public function getSecond(){
         return $this->second;
     }
     public static function parseFromTimeStamp($timestamp){
         return new date(date("Y",$timestamp),date("n",$timestamp),date("j",$timestamp),date("H",$timestamp),date("i",$timestamp),date("s",$timestamp));
     }
     public function getTimeStamp(){
     	if (is_null($this->timestamp)){
     		$this->timestamp=mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
     	}
        return $this->timestamp;
     }
     public static function parseFromFormat($format,$date){
         $dateArray = date_parse_from_format($format, $date);
         return new Date($dateArray['year'],$dateArray['month'],$dateArray['day'],$dateArray['hour'],$dateArray['minute'],$dateArray['second']);
     }
     public function format($format = "d/m/Y H:i:s"){
         return date($format,$this->getTimeStamp());
     }
     public function __toString(){
     	return $this->format();
     }
}
?>

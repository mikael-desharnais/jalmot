<?php
 class CoreNumber{
 	public static function sign($number){
 		return min(1, max(-1, $number));
 	}
}
?>

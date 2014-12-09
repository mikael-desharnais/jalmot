<?php
/**
* The container for all String operation
*/
class CoreString{
	/**
	* Transforms string to upper case
	* @return String The string to upper case
	* @param String $string the string to transform
	*/
	public static function strtoupper($string){
		return strtoupper($string);
	}
	/**
	* Transforms string to lower case
	* @return String The string to lower case
	* @param String $string the string to transform
	*/
	public static function strtolower($string){
		return strtolower($string);
	}

	public static function shorten($string,$length,$textAppend){
		if (mb_strlen($string)<$length){
			return $string;
		}
		$words = explode(" ",$string);
		$toReturn = "";
		foreach($words as $word){
			$toReturn.=($toReturn==""?"":" ").$word;
			if (mb_strlen($toReturn)>$length){
				break;
			}
		}
		return $toReturn.$textAppend;
 	}
	public static function getUniqId(){
		return str_replace('.','',uniqid("",true));
	}
	public static function isValidEmail($email) {
		$isValid = true;
		$atIndex = strrpos ( $email, "@" );
		if (is_bool ( $atIndex ) && ! $atIndex) {
			$isValid = false;
		} else {
			$domain = substr ( $email, $atIndex + 1 );
			$local = substr ( $email, 0, $atIndex );
			$localLen = strlen ( $local );
			$domainLen = strlen ( $domain );
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			} else if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else if ($local [0] == '.' || $local [$localLen - 1] == '.') {
				// local part starts or ends with '.'
				$isValid = false;
			} else if (preg_match ( '/\\.\\./', $local )) {
				// local part has two consecutive dots
				$isValid = false;
			} else if (! preg_match ( '/^[A-Za-z0-9\\-\\.]+$/', $domain )) {
				// character not valid in domain part
				$isValid = false;
			} else if (preg_match ( '/\\.\\./', $domain )) {
				// domain part has two consecutive dots
				$isValid = false;
			} else if (! preg_match ( '/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace ( "\\\\", "", $local ) )) {
				// character not valid in local part unless
				// local part is quoted
				if (! preg_match ( '/^"(\\\\"|[^"])+"$/', str_replace ( "\\\\", "", $local ) )) {
					$isValid = false;
				}
			}
			if ($isValid && ! (checkdnsrr ( $domain, "MX" ) || checkdnsrr ( $domain, "A" ))) {
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}
}


?>

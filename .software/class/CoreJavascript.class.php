<?php
class CoreJavascript{
	public static function parseArray(&$arrayJS){
		$content = "";
		$arrayPHP=array();
		if (strlen($arrayJS)==0){
			return;
		}
		$isInString=false;
		while(!in_array($arrayJS[0],array("[","]"))||$isInString){

			if (substr($arrayJS,0,2)=='\"'){
				$content.= substr($arrayJS,0,2);
				$arrayJS=substr($arrayJS,2);
				continue;
			}
			if ($arrayJS[0]=='"'){
				$isInString = !$isInString;
			}
			$content.= $arrayJS[0];
			$arrayJS=substr($arrayJS,1);
		}
		if($arrayJS[0]=="["){
			$arrayJS=substr($arrayJS,1);
			if (!empty($content)){
				if ($content[strlen($content)-1]==","){
					$content = substr($content,0,-1);
				}
				$contentArray = explode(',',$content);
				$content = "";
				foreach($contentArray as $contentElement){
					$content.=(empty($content)?"":",").(strlen($contentElement)==0?'""':$contentElement);
				}
				eval('$arrayPHP =array('.$content.');');
			}
			$container = Javascript::parseArray($arrayJS);
			$arrayPHP[]=$container;
			if ($arrayJS[0]==","){
				$arrayJS = substr($arrayJS,1);
			}
			$container = Javascript::parseArray($arrayJS);
			if (!empty($container)){
				$arrayPHP=array_merge($arrayPHP,$container);
			}
		}
		if ($arrayJS[0]=="]") {
			$arrayJS=substr($arrayJS,1);
			if (!empty($content)){
				if ($content[0]==","){
					$content = substr($content,1);
				}
				$contentArray = explode(',',$content);
				$content = "";
				foreach($contentArray as $contentElement){
					$content.=(empty($content)?"":",").(strlen($contentElement)==0?'""':$contentElement);
				}
				eval('$jsData =array('.$content.');');
				return $jsData;
			}else {
				return array();
			}
		}
		return $arrayPHP;
	}
}
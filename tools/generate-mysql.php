<?php


$server = "localhost";
$database = "jalmot";
$user = "root";
$password = "";

$dataSourceName = "MysqlDefault";

@mkdir('tmp/xml/model',0777,true);
@mkdir('tmp/xml/dataSource/'.$dataSourceName,0777,true);

$cnt = new mysqli($server,$user,$password,$database);

if ($cnt->connect_error) {
    die('Erreur de connexion (' . $cnt->connect_errno . ') '
            . $cnt->connect_error);
}

$result = $cnt->query("SHOW TABLES");

while($table_data = $result->fetch_array()){
	createFileForTable($cnt,$table_data[0],$dataSourceName);
}

$cnt->close();

function createFileForTable($cnt,$tableName,$dataSourceName){
  $result = $cnt->query("SHOW COLUMNS FROM ".$tableName);
  $modelXml = new SimpleXMLElement('<model/>');
  $modelXml->addChild("datasource",$dataSourceName);
  $modelXml->addChild("name",snakeToCamel($tableName));

  $mysqlXml = new SimpleXMLElement('<modelInDS/>');
  $mysqlXml->addChild("name",snakeToCamel($tableName));
  $mysqlXml->addChild("dbname",$tableName);

  $mysqlFields = $mysqlXml->addChild("fields");

  $fields = $modelXml->addChild("fields");
  $relations = $modelXml->addChild('relations');
  while($table_data = $result->fetch_array()){
      $field = $fields->addChild('field');
      $field->addChild('name',lcfirst(snakeToCamel($table_data['Field'])));
      $field->addChild('type',getTypeFromMysqlType($table_data['Type']));
      if ($table_data['Key']=="PRI"){
        $field->addChild('primary_key',1);
      }

      $mysqlField = $mysqlFields->addChild('field');
      $mysqlField->addChild('name',lcfirst(snakeToCamel($table_data['Field'])));
      $mysqlField->addChild('dbname',$table_data['Field']);
      if ($table_data['Extra']=="auto_increment"){
        $params = $mysqlField->addChild('params');
        $param = $params->addChild('param',1);
        $param->addAttribute("type","simple");
        $param->addAttribute("name","autoincrement");
      }
  }
  $dom = new DOMDocument("1.0");
  $dom->preserveWhiteSpace = false;
  $dom->formatOutput = true;
  $dom->loadXML($modelXml->asXML());
  file_put_contents("tmp/xml/model/".snakeToCamel($tableName).".xml",$dom->saveXML());
  $dom = new DOMDocument("1.0");
  $dom->preserveWhiteSpace = false;
  $dom->formatOutput = true;
  $dom->loadXML($mysqlXml->asXML());
  file_put_contents("tmp/xml/dataSource/".$dataSourceName."/".snakeToCamel($tableName).".xml",$dom->saveXML());
}
function snakeToCamel($val) {
  return str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
}
function getTypeFromMysqlType($mysqlType){
  $typesRegExp = array("/int\([0-9]*\)/"=>"integer",
                        "/varchar\([0-9]*\)/"=>"string",
                        "/text/"=>"string",
                        "/datetime/"=>"date");
  foreach($typesRegExp as $regExp => $jalmotType){
    if (preg_match($regExp,$mysqlType)===1){
      return $jalmotType;
    }
  }
  die("notypefound : ".$mysqlType);
}

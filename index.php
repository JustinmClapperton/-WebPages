<!doctype html>
<?php 
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
?>
<html>
<head>
<link href="style.css" rel="stylesheet">
<title>This is my first PHP program!</title>
</head>
<body>
<div>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
Please Upload JSON file
<input name="jsonFile" type="file">


</form>
<?php 
//phpinfo();
 include 'dataBase.php';
//$data = array("1", "2", "[3, 4]");
//echo implode(", ", $data);
// $db1 = mysqli_connect("localhost","justinc",'-dvdra-018',"library"); 
// if(is_null($db1)){
// 	echo "null";
// }


 ////////////////////////////////////////
$filename = 'test2.json';
$file_handle = fopen($filename, "a+");
$jsonData = fread($file_handle, filesize($filename));
$jsonDecoded = json_decode($jsonData);
//print_r($jsonDecoded->firmwareRev);

//$db = new mysqli("localhost", "justinc", '-dvdra-018', "skookumElements");
$db2 = new DatabaseConnect("library");

$db2->getName();
$password = "-dvdra-018";
$db2->connect("justinc", $password);
$tableName = "testTable1";
$colArray = array("ID", "firstName", "lastName");
$typeArray = array("INTEGER PRIMARY KEY", "TEXT", "TEXT");
$data = array(1, "Justin", "Clapperton");
$jsonTableName = "skookumData";
$jsonArrayType = array("INTEGER PRIMARY KEY AUTO_INCREMENT",
					   "TEXT", 
					   "TEXT", 
					   "TEXT", 
					   "TEXT", 
					   "BLOB", 
					   "INTEGER", 
					   "INTEGER", 
					   "TEXT", 
					   "TEXT", 
					   "TEXT", 
					   "TEXT", 
					   "TEXT");


$jsonObject = $jsonDecoded;
$keys = get_object_vars($jsonDecoded);
$arrayKeys = array_keys($keys);
$jsonElement = get_object_vars($jsonObject->$arrayKeys[0]);
$elementKeys = array_keys($jsonElement);
array_unshift($elementKeys, "id_index");




$db2->createTable($jsonTableName, $elementKeys, $jsonArrayType);
foreach($jsonObject as $row){
	$row = get_object_vars($row);
	array_unshift($row, "");
	$db2->addToTable($jsonTableName, $row, $elementKeys);
	//echo "<br/>";
	//sleep(0.1);
	//$rowMenu = strtoupper($row->menu);


}
$tableData = $db2->displayTable("*", $jsonTableName);
echo "<table style='border:1px solid black' rules='all'><tr>";
$tableKeys = array_keys($tableData[0]);
foreach($tableKeys as $key){
	echo "<th>{$key}</th>";
}
echo "</tr>";
foreach($tableData as $table){

	echo "<tr>";
		foreach($table as $data){
			echo "<td>{$data}</td>";
		}
	echo "</tr>";
}
?>
</div>

</body>
</html>
<?php 
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

class DatabaseConnect {

	public $name;
	private $db;

	public function __construct($dbName){
		$this->name = $dbName;
		$this->db = "hello";
	}

	public function getName(){
		return $this->name;
	}

	public function connect($username, $password){
		$this->db = new PDO ("mysql:host=localhost;dbname={$this->name}", 
							  $username, 
							  $password);
		if(isset($this->db)){
			//echo "DB Connected <br/><br/>";
		}
	}

	public function createTable($tableName, $colArray, $typeArray){
		$sql = "CREATE TABLE $tableName (";
		$size = count($colArray);	
		for($i = 0;$i<$size;$i++){

				if(!($i == ($size - 1))){
					$sql .= "{$colArray[$i]} {$typeArray[$i]}, ";
				}else{
					$sql .= "{$colArray[$i]} {$typeArray[$i]});";

				}

			}
		//print($sql);
		$readyToSend = $this->db->prepare($sql);
		$readyToSend->execute();
	}

	public function addToTable($tableName, $data, $colArray){
		//static $j = 0;
		//echo ++$j;
		foreach($data as $key=>$value){
			if(is_array($value)){
				$data[$key] = "[" . implode(", ", $data[$key]) . "]";
			}
		}
		$data = implode("', '", $data);
		$colArray = implode(", ", $colArray);
		$sql = "INSERT INTO $tableName ({$colArray}) VALUES ('{$data}');";
		//echo $sql;
		//echo "<br/>";
		$readyToSend = $this->db->prepare($sql);
		$readyToSend->execute();
	}


	public function displayTable($colArray, $tableArray, $whereArgs, $orderBy){
		$dataArray = array();
		if(is_array($tableArray)){
		$tableArray = implode(", ", $tableArray);
		}
		if(is_array($tableArray)){
		$colArray = implode(", ", $colArray);
		}
		$sql = "SELECT {$colArray} FROM {$tableArray}";
		if(isset($whereArgs)){
			$sql .= " WHERE {$whereArgs}";
		}
		if(isset($orderBy)){
			$sql .= " ORDER BY {$orderBy}";
		}
		$sql .= ";";
		//print($sql);
		$readyToSend = $this->db->prepare($sql);
		$readyToSend->execute();
		while ($row = $readyToSend->fetch(PDO::FETCH_ASSOC)) {
			array_push($dataArray, $row);
			//echo "<br/>";
		// or do something more meaningful with each returned row
		}
		//
		//print_r($dataArray);
		return $dataArray;
	}

	public function printDataToTable(){

	}

	public function deleteFromTable($tableName, $deleteType, $idArray){
		if(isset($idArray)){
			if(is_array($idArray)){
				$idArray = implode("' AND '", $idArray);
			}
		}elseif($deleteType == "DELETE_ROW"){

			echo "No Id Selected, Nothing Deleted";
		}else{
			$idArray = "";
		}

		if($deleteType == "DELETE_TABLE"){
			$sql = "DROP TABLE $tableName;";
		}elseif($deleteType == "CLEAR_TABLE"){
			$sql = "TRUNCATE TABLE $tableName;";
		}elseif($deleteType == "DELETE_ROW"){
			$sql = "DELETE FROM $tableName WHERE ID = '{$idArray}';";
		}else{
			echo "No Type Selected";
		}
		print($sql);
		$readyToSend = $this->db->prepare($sql);
		$readyToSend->execute();
	}

	public function jsonToArray(){
		$keys = get_object_vars($jsonDecoded);
		$arrayKeys = array_keys($keys);
	}


}

?>
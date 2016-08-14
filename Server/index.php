<?php
// Connect to DB
include("dbconnect.php");

//echo "Webpage loaded \n"; // DEBUG

if (isset($_POST["id"]) && $_POST["id"] == "1337") {
	post_request_handler($connection);
} else 
if (isset($_POST["id"]) && $_POST["id"] == "1338") {
	post_request_with_answer_handler($connection);
} else 
if (isset($_GET["id"]) && $_GET["id"] == "42") {
	get_request_handler($connection);
} else { 
	echo "No opcode recognized within id parameter"; 
}

mysqli_close($connection);

// echo "END \n"; // DEBUG

// END of runtime code

// BEGIN of
// FUNCTION DEFINITIONS
// vvvvvvvvvvvvvvvvvvvv

function get_request_handler($connection) {
	// echo "Get request recognized \n";  // DEBUG
	testTheQuery($connection);
	
}

function post_request_with_answer_handler($connection) {
	if (isset($_POST["query"])) {
		$sqlStmt = $_POST["query"];
		
		// Do query
		if ($results = $connection->query($sqlStmt)) {
			echo "200"; // Status code für erfolgreiche Ausführung der übergebenen query
		  	if (mysqli_num_rows($results) > 0) {
			  	while ($row = $results->fetch_row()) {
			  		foreach ($row as $value) {
			  			echo ";$value";
			  		}
			  	}
			  	$results->free();
		  	}
	  		
		} else {
			echo "400"; // Status code für Fehler bei der Query Ausführung
		}
	} else {
		echo "400"; // Keine Query Erkannt
	}
}

function post_request_handler($connection) {
	// echo "Post request recognized \n";  // DEBUG
	// echo "Message was: id=".$_POST["id"]." \n"; // DEBUG
	if (isset($_POST["query"])) {
		$sqlStmt = $_POST["query"];
		if ($connection->query($sqlStmt)) {
			echo "Query operation successfull \n";
		} else {
			echo "Query was not successfull \n";
		}
	} else {
		echo "No query recognized in POST request \n";
	}
		
}

function testTheQuery($connection){
	$sqlStmt = "SELECT * FROM Lagerposition;";
  	if ($results = $connection->query($sqlStmt)) {
		echo "200;\n"; // Status code für erfolgreiche Ausführung der übergebenen query
		while ($row = $results->fetch_row()) {
			foreach ($row as $value) {
				echo "$value;";
	  		}
	  		echo "\n";		  	
	  	}
	  	$results->free();
	} else {
			echo "400"; // Status code für Fehler bei der Query Ausführung
		}
	
	$sqlStmt = "SELECT * FROM Material;";
  	if ($results = $connection->query($sqlStmt)) {
		echo "200;\n"; // Status code für erfolgreiche Ausführung der übergebenen query
		while ($row = $results->fetch_row()) {
		  	foreach ($row as $value) {
		  		echo "$value;";
		  	}
		  	echo "\n";
		}
	  	$results->free();
	} else {
		echo "400"; // Status code für Fehler bei der Query Ausführung
	}
}

// Human readable output of test-the-query
  	/*$data = array();
  	if ($results = $connection->query($sqlStmt)) {
      	while ($row = $results->fetch_assoc()) {
        	$id = $row["ID"];
        	$description = $row["Beschreibung"];
        	array_push($data,array("ID"=> $id,"Beschreibung"=>$description));  
      	}
  		$results->free();
	}

  	foreach ($data as $d){
    	echo "ID: " . $d["ID"] . " Beschreibung: " . $d["Beschreibung"] . "\n";
  	}*/

?>

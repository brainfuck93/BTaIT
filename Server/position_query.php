<?php
if (isset($_POST['submit']) && $_POST['submit'] == true) {
	// Connect to DB
	include("dbconnect.php");
	if (isset($_POST['query'])) {
		switch ($_POST['query']) {
			case "lpos":
				do_warehouse_pos_query($connection);
				break;
			case "dpos":
				do_devices_pos_query($connection);
				break;
			default:
				echo "Dont manipulate POST parameters, bitch.";
		}
	}
}



// Function definitions

function do_warehouse_pos_query($connection) {
	$results_per_col = 3;
	$sql = "SELECT * FROM Lagerposition;";

	if ($results = $connection->query($sql)) {
	
		$position_amount = mysqli_num_rows($results);
		$row_amount = intval($position_amount/$results_per_col) + 1;
		//echo "Position amount: $position_amount, Row amount: $row_amount <br>";
	  	
		if ($position_amount > 0) {
			echo '<tr>';
			for ($pos_counter = 1; $pos_counter <= $position_amount; $pos_counter++) {
				$row = $results->fetch_assoc();
				if ($row["Typ"] == "L") {
					$id = $row["ID"];
					echo "<td id='t$id'>$id</td>";
					if ($pos_counter % 3 == 0) {
						echo "</tr><tr>";
					}
				}
			}
			echo '</tr>';
		  	$results->free();
	  	}
	 		
	} else {
		echo "400"; // Status code für Fehler bei der Query Ausführung
	}
	
}
function do_devices_pos_query($connection) {
	$results_per_col = 3;
	$sql = "SELECT * FROM Lagerposition;";

	if ($results = $connection->query($sql)) {
	
		$position_amount = mysqli_num_rows($results);
		$row_amount = intval($position_amount/$results_per_col) + 1;
		//echo "Position amount: $position_amount, Row amount: $row_amount <br>";
	  	
		if ($position_amount > 0) {
			for ($pos_counter = 1; $pos_counter <= $position_amount; $pos_counter++) {
				$row = $results->fetch_assoc();
				if ($row["Typ"] == "D") {
					$id = $row["ID"];
					echo "<tr><td id='t$id'>$id</td></tr>";
				}
			}
		  	$results->free();
	  	}
	 		
	} else {
		echo "400"; // Status code für Fehler bei der Query Ausführung
	}
	
}

?>
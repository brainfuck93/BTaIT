<?php
if (isset($_POST['submit']) && $_POST['submit'] == true) {
	// Connect to DB
	include("dbconnect.php");
	// Decide which query should be done
	if (isset($_POST['query'])) {
		switch ($_POST['query']) {
			case "lpos":
				do_warehouse_pos_query($connection);
				break;
			case "dpos":
				do_devices_pos_query($connection);
				break;
			case "mat":
				do_materials_query($connection);
				break;
			default:
				echo "Dont manipulate POST parameters, bitch.";
		}
	}
}



// Function definitions
// -----------------------------------------------------------

// Returns a JSON-Object containing an assignment of Material.ID -> Lagerposition.ID
function do_materials_query($connection) {
	$sql = "SELECT M.ID as mat_id, L.ID as pos_id, M.Position FROM Material M, Lagerposition L WHERE M.Position = L.ID";
	
	if ($results = $connection->query($sql)) {
		$material_amount = mysqli_num_rows($results);
		if ($material_amount > 0) {
			$data = array();
			for ($mat_counter = 0; $mat_counter < $material_amount; $mat_counter++) {
				$row = $results->fetch_assoc();
				$mat_id = $row["mat_id"];
				$data[$mat_id] = $row["pos_id"];
			}
			echo json_encode($data);
		}
	}
}

// Returns a table containg as many columns as warehouse positions exist. Every column has an
// ID (t0 - tX where X is the amount of colums - 1).
function do_warehouse_pos_query($connection) {
	$results_per_col = 3;
	$sql = "SELECT * FROM Lagerposition;";

	if ($results = $connection->query($sql)) {
	
		$position_amount = mysqli_num_rows($results);
		$row_amount = intval($position_amount/$results_per_col) + 1;
	  	
		if ($position_amount > 0) {
			echo '<tr>';
			for ($pos_counter = 1; $pos_counter <= $position_amount; $pos_counter++) {
				$row = $results->fetch_assoc();
				if ($row["Typ"] == "L") {
					$id = $row["ID"];
					echo "<td id='t$id'><span class='id'>$id </span>";
					echo "<span class='amount'></span>";
					echo "<img src='spanner-klein.png' alt='Schraubenschlüssel' class='s1'>";
					echo "<img src='spanner-klein.png' alt='Schraubenschlüssel' class='s2'>";
					echo "<img src='spanner-klein.png' alt='Schraubenschlüssel' class='s3'>";
					echo "</td>";
					if ($pos_counter % $results_per_col == 0) {
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

// Returns a table containg as many columns as device positions exist. Every column has an
// ID (t0 - tX where X is the amount of colums - 1).
function do_devices_pos_query($connection) {
	$sql = "SELECT * FROM Lagerposition;";

	if ($results = $connection->query($sql)) {
	
		$position_amount = mysqli_num_rows($results);
	  	
		if ($position_amount > 0) {
			for ($pos_counter = 1; $pos_counter <= $position_amount; $pos_counter++) {
				$row = $results->fetch_assoc();
				if ($row["Typ"] == "D") {
					$id = $row["ID"];
					echo "<tr><td id='t$id'><span class='id'>$id </span>";
					echo "<span class='amount'></span>";
					echo "<img src='spanner-klein.png' alt='Schraubenschlüssel' class='s1'>";
					echo "<img src='spanner-klein.png' alt='Schraubenschlüssel' class='s2'>";
					echo "<img src='spanner-klein.png' alt='Schraubenschlüssel' class='s3'>";
					echo "</td></tr>";
				}
			}
		  	$results->free();
	  	}
	 		
	} else {
		echo "400"; // Status code für Fehler bei der Query Ausführung
	}
	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>BTaIT Monitoring</title>
	<script src="jquery-3.1.0.js"></script>
	<style type="text/css">
		body {
			font-family: Verdana;
			height: auto;
		}
		/* Header enthält Logo und Überschrift */
		#header {
			height: 70px;
			vertical-align: middle;
			margin-bottom: 5px;
		}
		#header img {
			float: left;
		}
		#header h1 {
			display: inline-block;
			padding-left: 10px;
			margin-top: 18px;
		}
		/* Im Contentwrapper sind beide Unterüberschriften mit dazugehörigen Subcontentwrappern
		(#warehouse, #devices) und den jeweiligen Tabellen (#maintable, #devtable) */
		#contentwrapper {
			padding: 5px;
			padding-bottom: 15px;
			clear: both;
			width: auto;
			height: auto;
			border: 1px solid black;
		}
		#warehouse {
			display: inline-block;
			margin-left: 5px;
			margin-top: 0px;
			width: 60%;
			height: auto;
		}
		#devices {
			display: inline-block;
			margin-left:80px;
			margin-top:0px;
			width: 20%;
			height: auto;
		}
		#maintable, #devtable {
			width: 100%;
			height: auto;
		}
		table, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		td {
			text-align: left;
			vertical-align: top;
			overflow: hidden;
		}
		#maintable td {
			height: 150px;
			width: 33%;
		}
		#devtable td {
			height: 150px;
		}
		/* Dies ist der Inhalt eines td Elements. Ein td enthält ein span.id Element,
		 * das die ID des Feldes aus aus der DB anzeigt, sowie ein span.amount Element
		 * in das die Anzahl an Positionen (Device oder Lager) hineingeschrieben wird. */
		.id {
			font-weight: bold;
		}
		.amount {
			
		}
		/* .s1, .s2, .s3 stehen für die 3 Schraubenschlüssel Bilder */
		.s1, .s2, .s3 {
			position: absolute;
			display: none;
		}
		.s1 {
			margin-left: 10px;
			margin-top: 10px;
		}
		.s2 {
			margin-left: 20px;
			margin-top: 20px;
		}
		.s3 {
			margin-left: 30px;
			margin-top: 30px;
		}
		#footer {
			padding-top: 50px;
			clear: both;
			text-align: right;
		}
	</style>
	<script>
		// Einstellbare Parameter
		timeoutMaterial = 2000;
		timeoutPositions = 10000;
		fadeSpeed = 1000;
		
		/* getMaterials lädt mit einem AJAX-Request eine Zuordnung von Material zu
		 * Lagerposition. Diese wird bei erfolgreichem Request ausgewertet und es
		 * wird die Anzahl der Materialien auf einer Position in die jeweilge Position
		 * geschrieben. Zusätzlich wird eine Veranschaulichung durchgeführt indem
		 * zwischen 0 und 3 Bildern an der jeweiligen Position angezeigt werden. */
		function getMaterials() {
			$.ajax({
				type: "POST",
				url: 'position_query.php',
				data: {
					submit: true,
					query: "mat"
				},
				success: function(data) {
					// Wenn es eine Änderung bei der Position von einem Material gab,
					// werden alle Materialien neu gezeichnet
					if (matJsonData != data) {
						var matToPosAssignment = jQuery.parseJSON(data);
						var keys = Object.keys(matToPosAssignment);
						$('#maintable tr, #devtable tr').children().each(function() {
							var matCountPerPos = 0;
							// Anzahl der Materialien für Lagerposition (this) herausfinden
							for (var i = 0; i < keys.length; i++) {
								if ($(this).attr('id') == "t"+matToPosAssignment[keys[i]]) {
									matCountPerPos++;
								}
							}
							// Wenn mindestens ein Material an dieser Position ist
							if (matCountPerPos > 0) {
								$(this).children('.amount').html('Anzahl Objekte: ' + matCountPerPos + '<br>');
								if (matCountPerPos > 2) {
									$(this).children('img').fadeIn(fadeSpeed);
								} else if (matCountPerPos > 1) {
									$(this).children('img.s1, img.s2').fadeIn(fadeSpeed);
									$(this).children('img.s3').fadeOut(fadeSpeed);
								} else {
									$(this).children('img.s1').fadeIn(fadeSpeed);
									$(this).children('img.s2, img.s3').fadeOut(fadeSpeed);
								}
							} else {
								$(this).children('img').fadeOut(fadeSpeed);
								$(this).children('.amount').html('');
							}
						});
					}
					// Speichern des Vergleichswertes für die Überprüfung ob sich etwas geändert hat
					matJsonData = data;
				},
				complete: function() {
					setTimeout(getMaterials, timeoutMaterial);
				}
			});
		}
		
		/* getNewLPositions lädt mit einem AJAX-Request eine Tabelle von Lagerpositionen,
		 * in der sich bereits die IDs der Lagerpositionen befinden. Diese Tabelle wird
		 * später von getMaterials() gefüllt */
		function getNewLPositions() {
			$.ajax({
				type: "POST",
				url: 'position_query.php',
				data: {
					submit: true,
					query: "lpos"
				},
				success: function(data) {
					// Wenn es eine Veränderung gab
					if (data != lagerPosData) {
						$('#maintable').html(data);
						// Zähle die Anzahl von Positionen und schreibe sie in die Überschrift
						var lagerPosCounter = 0;
						$('#maintable tr').children().each(function() {
							lagerPosCounter++;
						});
						$('#warehouse h2').append(': ' + lagerPosCounter);
					}
					lagerPosData = data;
				},
				complete: function() {
					setTimeout(getNewLPositions, timeoutPositions);
				}
			});
		}
		
		/* getNewDPositions lädt mit einem AJAX-Request eine Tabelle von Gerätepositionen,
		 * in der sich bereits die IDs der Gerätepositionen befinden. Diese Tabelle wird
		 * später von getMaterials() gefüllt */
		function getNewDPositions() {
			$.ajax({
				type: "POST",
				url: 'position_query.php',
				data: {
					submit: true,
					query: "dpos"
				},
				success: function(data) {
					// Wenn es eine Veränderung gab
					if (data != devPosData) {
						$('#devtable').html(data);
						// Zähle die Anzahl von Positionen und schreibe sie in die Überschrift
						var deviceCounter = 0;
						$('#devtable tr').children().each(function() {
							deviceCounter++;
						});
						$('#devices h2').append(': ' + deviceCounter);
					}
					devPosData = data;
				},
				complete: function() {
					setTimeout(getNewDPositions, timeoutPositions);
				}
			});
		}
		
		$(document).ready(function(){
			// Initialisiere globale Variablen
			matJsonData = "";
			lagerPosData = "";
			devPosData = "";
			// Starte AJAX-Loop
			setTimeout(getNewLPositions, 0);
			setTimeout(getNewDPositions, 0);
			setTimeout(getMaterials, 1000);
		});
	</script>
</head>
<body>
	<div id="header">
		<img src="DiK_Logo.jpg" alt="DiK Logo" class="dik_logo" width="80" height="70">
		<h1>BTaIT Lager Monitoring</h1>
	</div>
	<div id="contentwrapper">
		<div id="warehouse">
			<h2>Lagerpositionen</h2>
			<table id="maintable">
				<tr>
					<td>1</td>
					<td>2</td>
				</tr>
				<tr>
					<td>3</td>
					<td>4</td>
				</tr>
			</table>
		</div>
		<div id="devices">
			<h2>Geräte</h2>
			<table id="devtable">
				<tr><td>Device 1</td></tr>
				<tr><td>Device 2</td></tr>
			</table>
		</div>
	</div>
	<div id="footer">
		<div id="phpResponse"></div>
	</div>
</body>
</html>

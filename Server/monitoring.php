<!DOCTYPE html>
<html>
<head>
	<title>BTaIT Monitoring</title>
	<script src="jquery-3.1.0.js"></script>
	<style type="text/css">
		body {
			font-family: Verdana;
			position: relative;
		}
		table, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		td {
			text-align: left;
			vertical-align: top;
		}
		#contentwrapper {
			position: absolute;
			width: 100%;
			height: auto;
			background: yellow;
		}
		#warehouse {
			float: left;
			position: relative;
			margin-left: 10px;
			margin-top: 0px;
			width: 60%;
			height: auto;
			background-color: red;
		}
		#devices {
			float: right;
			position: relative;
			margin-left:10px;
			margin-top:0px;
			width: 20%;
			height: 50%;
			display: inline-block;
			background-color: green;
		}
		#maintable, #devtable {
			width: 100%;
			height: 100%;
		}
		#maintable td {
			width: 33%;
			height: 100px;
		}
		#devtable td {
			width: 33%;
			height: 150px;
		}
	</style>
	<script>
		function getNewLPositions() {
			$.ajax({
				type: "POST",
				url: 'position_query.php',
				data: {
					submit: true,
					query: "lpos"
				},
				success: function(data) {
					$('#maintable').html(data);
				},
				complete: function() {
					setTimeout(getNewLPositions, 10000);
				}
			});
		}
		function getNewDPositions() {
			$.ajax({
				type: "POST",
				url: 'position_query.php',
				data: {
					submit: true,
					query: "dpos"
				},
				success: function(data) {
					$('#devtable').html(data);
				},
				complete: function() {
					setTimeout(getNewDPositions, 10000);
				}
			});
		}
		
		$(document).ready(function(){
		setTimeout(getNewLPositions, 1000);
		setTimeout(getNewDPositions, 1000);
		});
	</script>
</head>
<body>
	<h1>BTaIT Lager Monitoring</h1>
	<div id="contentwrapper">
		<div id="warehouse">
			<h2>Lager</h2>
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
			</table>
		</div>
	</div>
</body>
</html>

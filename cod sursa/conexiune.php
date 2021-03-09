<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="conexiune.css">
</head>
<body>
<?php
	$servername="DESKTOP-US6TA1H\SQLEXPRESS";                               //conectare la baza de date
	$connectionInfo= array("database" => "Evidenta medicamentelor");
	$conn=sqlsrv_connect($servername, $connectionInfo);
	/*
	if($conn){
		echo"Conexiune la baza de date reusita. <br/>";
	}
	else{
		echo"Conexiune la baza de date nereusita! <br/>";
		die( print_r(sqlsrv_errors(),true));
	}
	*/

?>
	<ul>
		<li><a class="active" href="conexiune.php">Home</a></li>
		<li><a href="medicamente.php">Medicamente</a></li>
		<li><a href="producatori.php">Producatori</a></li>
		<li><a href="distribuitori.php">Distribuitori</a></li>
		<li><a href="substante.php">Substante</a></li>
		<li><a href="simptome.php">Simptome</a></li>
		<li><a href="cauta.php">Cauta</a></li>
		<li><a href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>
	
	<?php
	
	$sql="SELECT M.Denumire, M.Pret, M.Stoc, M.Raft, SB.Denumire ,MSB.Concentratie,SI.Denumire, P.Denumire, D.Denumire
		  FROM Medicamente M LEFT JOIN Producatori P ON M.[Producator ID] = P.[Producator ID]
							 JOIN [Medicamente-Distribuitori] MD ON M.[Medicament ID] = MD.[Medicament ID]
							 JOIN Distribuitori D ON MD.[Distribuitor ID]=D.[Distribuitor ID]
							 JOIN [Medicamente-Substante] MSB ON M.[Medicament ID]=MSB.[Medicament ID]
							 JOIN Substante SB ON MSB.[Substanta ID]=SB.[Substanta ID]
							 JOIN [Medicamente-Simptome] MSI ON MSI.[Medicament ID]=M.[Medicament ID]
							 JOIN Simptome SI ON MSI.[Simptom ID]=SI.[Simptom ID]";
	$stmt= sqlsrv_query($conn, $sql);
	if($stmt === false){
		 die( print_r( sqlsrv_errors(), true) );
	}
	?>
	
	<table>
		<caption>Medicamente</caption>
		<tr>
			<th>Medicament</th>
			<th>Pret</th>
			<th>Stoc</th>
			<th>Raft</th>
			<th>Substanta</th>
			<th>Concentratie</th>
			<th>Simptom</th>
			<th>Producator</th>
			<th>Distribuitor</th>
		</tr>
		<?php	
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
					<th>$row[0]</th>
					<th>$row[1]</th>
					<th>$row[2]</th>
					<th>$row[3]</th>
					<th>$row[4]</th>
					<th>$row[5]</th>
					<th>$row[6]</th>
					<th>$row[7]</th>
					<th>$row[8]</th>
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	<br/><br/>
	
	
	
</body>
</html>
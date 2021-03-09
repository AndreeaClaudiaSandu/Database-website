<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="statistici.css">
</head>
<body>
<?php
	$servername="DESKTOP-US6TA1H\SQLEXPRESS";
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
		<li><a href="conexiune.php">Home</a></li>
		<li><a href="medicamente.php">Medicamente</a></li>
		<li><a href="producatori.php">Producatori</a></li>
		<li><a href="distribuitori.php">Distribuitori</a></li>
		<li><a href="substante.php">Substante</a></li>
		<li><a href="simptome.php">Simptome</a></li>
		<li><a href="cauta.php">Cauta</a></li>
		<li><a class="active" href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>
	
	<?php
		$sql="SELECT M.Denumire, M.Stoc, P.Denumire, D.Denumire, D.Telefon, D.Gmail
			  FROM Medicamente M JOIN Producatori P ON M.[Producator ID]=P.[Producator ID]
				                 JOIN [Medicamente-Distribuitori] MD ON M.[Medicament ID]=MD.[Medicament ID]
				                 JOIN Distribuitori D ON D.[Distribuitor ID]=MD.[Distribuitor ID]
              WHERE M.Stoc<=20";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<table>
		<caption style="color:#e60000;">Medicamente al caror stoc trebuie refacut urgent!</caption>
		<tr>
			<th>Medicament</th>
			<th>Stoc</th>
			<th>Producator</th>
			<th>Distribuitor</th>
			<th>Telefon Distribuitor</th>
			<th>Gmail Distribuitor</th>
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
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	
	
	<br/>
	<?php
		$sql="SELECT TOP 3 (M.Denumire), M.Pret, P.Denumire
			  FROM Medicamente M JOIN Producatori P ON M.[Producator ID]=P.[Producator ID]
              ORDER BY M.Pret DESC";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<table style="width: 450px">
		<caption  style="font-size: 25px">TOP 3 cele mai scumpe medicamente</caption>
		<tr>
			<th>Medicament</th>
			<th>Pret</th>
			<th>Producator</th>
		</tr>
		<?php	
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
					<th>$row[0]</th>
					<th>$row[1]</th>
					<th>$row[2]</th>
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	<br/><br/>
	
	<?php
		$sql="SELECT P.Denumire, AVG(M.Pret)
			  FROM Medicamente M JOIN Producatori P ON M.[Producator ID]=P.[Producator ID]
              GROUP BY P.Denumire
              ORDER BY AVG(M.Pret) DESC";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<table style="width: 600px">
		<caption  style="font-size: 25px">Producatorii ordonati descrescator dupa media preturilor</caption>
		<tr>
			<th>Producator</th>
			<th>Medie preturi</th>
		</tr>
		<?php	
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
					<th>$row[0]</th>
					<th>$row[1]</th>
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	<br/><br/><br/>
	
	<?php
		$sql="SELECT M.Denumire
			  FROM Medicamente M JOIN [Medicamente-Substante] MS ON M.[Medicament ID]=MS.[Medicament ID]
              GROUP BY M.Denumire, M.[Medicament ID]
              HAVING COUNT(MS.[Medicament ID])>1";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<table style="width: 600px">
		<caption  style="font-size: 25px">Medicamente care contin mai mult de o substanta activa</caption>
		<tr>
			<th>Medicament</th>
		</tr>
		<?php	
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
					<th>$row[0]</th>
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	<br/><br/><br/>
	
	<?php
		$sql="SELECT P.Denumire, (SELECT TOP 1 MM.Denumire
									FROM Medicamente MM
									WHERE MM.[Producator ID]=P.[Producator ID]
									ORDER BY MM.Pret ),  (SELECT TOP 1 MM.Denumire
														  FROM Medicamente MM
														  WHERE MM.[Producator ID]=P.[Producator ID]
														  ORDER BY MM.Pret DESC)
				FROM Producatori P 
				GROUP BY P.Denumire, P.[Producator ID]";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<table style="width: 750px">
		<caption  style="font-size: 25px">Cel mai scump si cel mai ieftin medicament al producatorilor</caption>
		<tr>
			<th>Producator</th>
			<th>Medicament ieftin</th>
			<th>Medicament scump</th>
		</tr>
		<?php	
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
					<th>$row[0]</th>
					<th>$row[1]</th>
					<th>$row[2]</th>
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	<br/><br/><br/>
	
	<?php
		$sql="SELECT DISTINCT M.Denumire
				FROM Medicamente M LEFT JOIN Producatori P ON M.[Producator ID] = P.[Producator ID]
										 JOIN [Medicamente-Distribuitori] MD ON M.[Medicament ID] = MD.[Medicament ID]
										 JOIN Distribuitori D ON MD.[Distribuitor ID]=D.[Distribuitor ID]
										 JOIN [Medicamente-Substante] MSB ON M.[Medicament ID]=MSB.[Medicament ID]
										 JOIN Substante SB ON MSB.[Substanta ID]=SB.[Substanta ID]
										 JOIN [Medicamente-Simptome] MSI ON MSI.[Medicament ID]=M.[Medicament ID]
										 JOIN Simptome SI ON MSI.[Simptom ID]=SI.[Simptom ID]
				WHERE M.Denumire IN (SELECT TOP 1 MM.Denumire
									FROM Medicamente MM JOIN [Medicamente-Simptome] MSS ON MM.[Medicament ID]=MSS.[Medicament ID]
									GROUP BY MM.Denumire
									ORDER BY COUNT(MSS.[Medicament ID]) DESC)";
		$stmt= sqlsrv_query($conn, $sql);
				
		
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<table style="width: 750px">
		<caption  style="font-size: 25px">Medicamentul care trateaza cele mai multe simptome</caption>
		<tr>
			<th>Medicament</th>
			<th>Nr. simptome</th>
		</tr>
		<?php	
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
					<th>$row[0]</th>";
					$sql2="SELECT COUNT(MSI.[Medicament ID])
							FROM Medicamente M JOIN [Medicamente-Simptome] MSI ON MSI.[Medicament ID]=M.[Medicament ID]
							WHERE M.Denumire='$row[0]'";
					$stmt2= sqlsrv_query($conn, $sql2);
					while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC) ) {
					echo "
					<th>$row2[0]</th>"; }
				echo "	
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	<br/><br/><br/>
	
	<?php
		$sql="SELECT M.Denumire,M.Stoc, P.Denumire
			  FROM Medicamente M JOIN Producatori P ON M.[Producator ID]=P.[Producator ID]
			  WHERE M.Denumire NOT IN (SELECT MM.Denumire
										FROM Medicamente MM JOIN [Medicamente-Distribuitori] MDD ON MM.[Medicament ID]=MDD.[Medicament ID]
										GROUP BY MM.Denumire
										HAVING COUNT(MDD.[Medicament ID])=1)
			  ORDER BY M.Stoc";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<table style="width: 750px">
		<caption  style="font-size: 25px">Medicamente al caror producator lucreaza cu mai multi distribuitori ordonate in functie de stoc</caption>
		<tr>
			<th>Medicament</th>
			<th>Stoc</th>
			<th>Producator</th>
		</tr>
		<?php	
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
					<th>$row[0]</th>
					<th>$row[1]</th>
					<th>$row[2]</th>
				</tr>";
			}
			sqlsrv_free_stmt( $stmt);
		?>
	</table>
	<br/><br/><br/>
	
</body>
</html>
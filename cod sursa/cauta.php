<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="cauta.css">
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
		<li><a class="active" href="cauta.php">Cauta</a></li>
		<li><a href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>

	<br/><br/><br/>
	<form action="cauta.php" method="post" class="f2">
	<p>Cauta toate medicamentele de la distribuitorul:<input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button class="b1" type="submit" name="submit1"><b>cauta</b></button>
	<table>
	<?php
	if(isset($_POST['submit1'])){
		$denumire=$_POST['denumire'];
		$sql="SELECT  M.Denumire, M.Stoc
			  FROM Medicamente M JOIN [Medicamente-Distribuitori] MD ON M.[Medicament ID]=MD.[Medicament ID]
				                 JOIN Distribuitori D ON MD.[Distribuitor ID]=D.[Distribuitor ID]
              WHERE D.Denumire= '$denumire'";
		$stmt=sqlsrv_query($conn,$sql);


		echo "
		<tr>
			<th>$denumire</th>
			<th>Medicament</th>
			<th>Stoc</th>
		</tr> ";

			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
						<th></th>
						<th>$row[0]</th>
						<th>$row[1]</th>
					</tr>";
			}
			sqlsrv_free_stmt( $stmt);


	}

	?>
		</table>

	</form>

	<br/><br/><br/>
	<form action="cauta.php" method="post" class="f2">
	<p>Cauta toate simptomele pentru care se recomanda medicamentul:<input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button class="b1" type="submit" name="submit2"><b>cauta</b></button>
	<table>
	<?php
	if(isset($_POST['submit2'])){
		$denumire=$_POST['denumire'];
		$sql="SELECT S.Denumire
			  FROM Medicamente M JOIN [Medicamente-Simptome] MS ON M.[Medicament ID]=MS.[Medicament ID]
								 JOIN Simptome S ON S.[Simptom ID]=MS.[Simptom ID]
								 WHERE M.Denumire= '$denumire'";
		$stmt=sqlsrv_query($conn,$sql);


		echo "
		<tr>
			<th>$denumire</th>
			<th>Simptom</th>
		</tr> ";

			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
						<th></th>
						<th>$row[0]</th>
					</tr>";
			}
			sqlsrv_free_stmt( $stmt);


	}

	?>
		</table>

	</form>

	<br/><br/><br/>
	<form action="cauta.php" method="post" class="f2">
	<p>Cauta medicamentele care ajuta la tratarea simptomului:<input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button class="b1" type="submit" name="submit3"><b>cauta</b></button>
	<table>
	<?php
	if(isset($_POST['submit3'])){
		$denumire=$_POST['denumire'];
		$sql="SELECT M.Denumire
			  FROM Medicamente M JOIN [Medicamente-Simptome] MS ON M.[Medicament ID]=MS.[Medicament ID]
								 JOIN Simptome S ON S.[Simptom ID]=MS.[Simptom ID]
			  WHERE S.Denumire='$denumire'";
		$stmt=sqlsrv_query($conn,$sql);


		echo "
		<tr>
			<th>$denumire</th>
			<th>Medicament</th>
		</tr> ";

			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
						<th></th>
						<th>$row[0]</th>
					</tr>";
			}
			sqlsrv_free_stmt( $stmt);


	}

	?>
		</table>

	</form>

	<br/><br/><br/>
	<form action="cauta.php" method="post" class="f2">
	<p>Cauta simptomele care pot fi tratate de substanta:<input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button class="b1" type="submit" name="submit4"><b>cauta</b></button>
	<table>
	<?php
	if(isset($_POST['submit4'])){
		$denumire=$_POST['denumire'];
		$sql="SELECT DISTINCT S.Denumire, M.Denumire
			  FROM Medicamente M JOIN [Medicamente-Simptome] MS ON M.[Medicament ID]=MS.[Medicament ID]
							     JOIN Simptome S ON S.[Simptom ID]=MS.[Simptom ID]
							     JOIN [Medicamente-Substante] MSS ON M.[Medicament ID]=MSS.[Medicament ID]
							     JOIN Substante SS ON SS.[Substanta ID]=MSS.[Substanta ID]
			  WHERE SS.Denumire='$denumire'";
		$stmt=sqlsrv_query($conn,$sql);


		echo "
		<tr>
			<th>$denumire</th>
			<th>Simptom</th>
			<th>Medicament</th>
		</tr> ";

			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
						<th></th>
						<th>$row[0]</th>
						<th>$row[1]</th>
					</tr>";
			}
			sqlsrv_free_stmt( $stmt);


	}

	?>
		</table>

	</form>
	<br/><br/><br/>

	<form action="cauta.php" method="post" class="f1">
	<p>Cauta medicamentul care contine in cea mai mare concentratie substanta:<input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button class="b1" type="submit" name="submit5"><b>cauta</b></button>
	<table>
	<?php
	if(isset($_POST['submit5'])){
		$denumire=$_POST['denumire'];
		$sql="SELECT DISTINCT M.Denumire, M.Pret, M.Stoc, M.Raft, SB.Denumire ,MSB.Concentratie, P.Denumire
			  FROM Medicamente M LEFT JOIN Producatori P ON M.[Producator ID] = P.[Producator ID]
									 JOIN [Medicamente-Distribuitori] MD ON M.[Medicament ID] = MD.[Medicament ID]
									 JOIN Distribuitori D ON MD.[Distribuitor ID]=D.[Distribuitor ID]
									 JOIN [Medicamente-Substante] MSB ON M.[Medicament ID]=MSB.[Medicament ID]
									 JOIN Substante SB ON MSB.[Substanta ID]=SB.[Substanta ID]
									 JOIN [Medicamente-Simptome] MSI ON MSI.[Medicament ID]=M.[Medicament ID]
									 JOIN Simptome SI ON MSI.[Simptom ID]=SI.[Simptom ID]
			  WHERE SB.Denumire='$denumire' AND MSB.Concentratie= (SELECT TOP 1 MS.Concentratie
																  FROM [Medicamente-Substante] MS JOIN Substante S ON MS.[Substanta ID]=S.[Substanta ID]
																  WHERE S.Denumire='$denumire'
																  ORDER BY MS.Concentratie DESC)";
		$stmt=sqlsrv_query($conn,$sql);


		echo "
		<tr>
			<th>Medicament</th>
			<th>Pret</th>
			<th>Stoc</th>
			<th>Raft</th>
			<th>Substanta</th>
			<th>Concentratie</th>
			<th>Producator</th>
		</tr> ";

			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
				echo "<tr>
						<th>$row[0]</th>
						<th>$row[1]</th>
						<th>$row[2]</th>
						<th>$row[3]</th>
						<th>$row[4]</th>
						<th>$row[5]</th>
						<th>$row[6]</th>
					</tr>";
			}
			sqlsrv_free_stmt( $stmt);


	}

	?>
		</table>

	</form>
	<br/><br/><br/>

</body>
</html>
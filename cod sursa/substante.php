<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="substante.css">
</head>
<body>

	<?php
		$servername="DESKTOP-US6TA1H\SQLEXPRESS";
		$connectionInfo= array("database" => "Evidenta medicamentelor");
		$conn=sqlsrv_connect($servername, $connectionInfo);
	?>
	<ul>
		<li><a href="conexiune.php">Home</a></li>
		<li><a href="medicamente.php">Medicamente</a></li>
		<li><a href="producatori.php">Producatori</a></li>
		<li><a href="distribuitori.php">Distribuitori</a></li>
		<li><a class="active" href="substante.php">Substante</a></li>
		<li><a href="simptome.php">Simptome</a></li>
		<li><a href="cauta.php">Cauta</a></li>
		<li><a href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>
	<div id="ss">
	<?php
		$sql="SELECT Denumire
			  FROM Substante";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	
	<table class="subst">
		<caption>Substante</caption>
		<tr>
			<th>Substanta</th>
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
	
	<?php
		$sql="SELECT M.Denumire, S.Denumire, MS.Concentratie
			  FROM Medicamente M JOIN [Medicamente-Substante] MS ON M.[Medicament ID]=MS.[Medicament ID]
				                 JOIN Substante S ON S.[Substanta ID]=MS.[Substanta ID]";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	
	<table class="med">
		<caption>Medicamente-Substante</caption>
		<tr>
			<th>Medicament</th>
			<th>Substanta</th>
			<th>Concentratie</th>
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
	</div>
	<div id="s">
	<h3>Inserare</h3>
	<form action="substante.php" method="post" >
	<p>Denumire <input class="caseta1" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button type="submit" name="submit"><b>inserare</b></button> 
	
	
	<?php
	$accept=1;
	if(isset($_POST['submit'])){
		$denumire=$_POST['denumire'];
		$accept=1;
		$denumiri=sqlsrv_query($conn,"SELECT Denumire FROM Substante");
		while( $row = sqlsrv_fetch_array( $denumiri, SQLSRV_FETCH_NUMERIC) ) {
			if($row[0]==$denumire)
				$accept=0;
		}
		if($accept==1){
			$sql="INSERT INTO Substante VALUES('$denumire' )";
			sqlsrv_query($conn,$sql);
		}
		
	}
	
	if($accept==0)
		echo "Substanta existenta!"; 
	?>
	</form>
	
	
	<h3>Stergere</h3>
	<form action="substante.php" method="post" >
	<p>Denumirea substantei care se sterge <input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button type="submit" name="submit3"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit3'])){
		$denumire=$_POST['denumire'];
		$sql="DELETE FROM Substante 
			  WHERE Denumire='$denumire'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	</form>
	<br/><br/><br/>
	
	
	<h5>Atribuiti medicamentelor substante active</h5>
	<form action="substante.php" method="post" >
	<p>Medicament<input class="caseta5" type="text" placeholder="introduceti denumirea" name="med" required /></p>
	<p>Substanta<input class="caseta6" type="text" placeholder="introduceti denumirea" name="subst" required /></p>
	<p>Concentratie<input class="caseta7" type="text" placeholder="introduceti concentratia" name="concentratie" required /></p>
	<button class="b1" type="submit" name="submit5"><b>adaugare</b></button> 
	
	<?php
	if(isset($_POST['submit5'])){
		$med=$_POST['med'];
		$subst=$_POST['subst'];
		$concentratie=$_POST['concentratie'];
		
		$id=sqlsrv_query($conn,"SELECT [Medicament ID] FROM Medicamente WHERE Denumire= '$med' ");
		$id_med=sqlsrv_fetch_array( $id, SQLSRV_FETCH_NUMERIC);
		$idd=sqlsrv_query($conn,"SELECT [Substanta ID] FROM Substante WHERE Denumire= '$subst' ");
		$id_subst=sqlsrv_fetch_array( $idd, SQLSRV_FETCH_NUMERIC);
		if($id_med!=0 && $id_subst!=0){
			$accept=1;
			$bb=sqlsrv_query($conn,"SELECT * FROM [Medicamente-Substante] WHERE [Medicament ID]= '$id_med[0]' AND [Substanta ID]='$id_subst[0]'");
			if($bb!=0){
				while($substanta=sqlsrv_fetch_array( $bb, SQLSRV_FETCH_NUMERIC) ) {
					if($substanta[3]==$concentratie)
						
						$accept=0;
					}
					
			}
			if($accept==1){
				$sql="INSERT INTO [Medicamente-Substante] VALUES('$id_med[0]','$id_subst[0]', '$concentratie')";
				sqlsrv_query($conn,$sql);
			}
			else
				echo "Nu se accepta dubluri."; 
		}	
		else{
			if($id_med==0)
				echo "Medicament inexistent!";
			if($id_subst==0)
				echo "Substanta inexistenta!";
			
		}
		
		
	}
	
	?>
	
	</form>
	
	<br/><br/><br/>
	
	<h5>Stergere legatura</h5>
	<form action="substante.php" method="post" >
	<p>Medicament<input class="caseta5" type="text" placeholder="introduceti denumirea" name="med" required /></p>
	<p>Substanta<input class="caseta6" type="text" placeholder="introduceti denumirea" name="subst" required /></p>
	<p>Concentratie<input class="caseta7" type="text" placeholder="introduceti concentratia" name="concentratie" required /></p>
	<button type="submit" name="submit6"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit6'])){
		$denumireM=$_POST['med'];
		$denumireS=$_POST['subst'];
		$concentratie=$_POST['concentratie'];
		
		$sql1=sqlsrv_query($conn, "SELECT [Medicament ID] FROM Medicamente WHERE Denumire='$denumireM'");
		$idM=sqlsrv_fetch_array( $sql1, SQLSRV_FETCH_NUMERIC);
		
		$sql2=sqlsrv_query($conn, "SELECT [Substanta ID] FROM Substante WHERE Denumire='$denumireS'");
		$idS=sqlsrv_fetch_array( $sql2, SQLSRV_FETCH_NUMERIC);
		
		
		$sql="DELETE FROM [Medicamente-Substante]
			  WHERE [Medicament ID]='$idM[0]' AND [Substanta ID]='$idS[0]' AND Concentratie='$concentratie'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	</form>
	<br/><br/><br/>
	
	</div>
</body>
</html>
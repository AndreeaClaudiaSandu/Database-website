<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="medicamente.css">
</head>
<body>

	<?php
		$servername="DESKTOP-US6TA1H\SQLEXPRESS";
		$connectionInfo= array("database" => "Evidenta medicamentelor");
		$conn=sqlsrv_connect($servername, $connectionInfo);
	?>

	<ul>
		<li><a href="conexiune.php">Home</a></li>
		<li><a class="active" href="medicamente.php">Medicamente</a></li>
		<li><a href="producatori.php">Producatori</a></li>
		<li><a href="distribuitori.php">Distribuitori</a></li>
		<li><a href="substante.php">Substante</a></li>
		<li><a href="simptome.php">Simptome</a></li>
		<li><a href="cauta.php">Cauta</a></li>
		<li><a href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>
	
	<div id="ss">
	<?php
		$sql="SELECT M.Denumire, M.Pret, M.Stoc, M.Raft,P.Denumire
			  FROM Medicamente M LEFT JOIN Producatori P ON M.[Producator ID] = P.[Producator ID]";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	<br/><br/>
	<table>
		<caption>Medicamente</caption>
		<tr>
			<th>Medicament</th>
			<th>Pret</th>
			<th>Stoc</th>
			<th>Raft</th>
			<th>Producator</th>
		</tr>
		<?php
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
			echo "<tr>
					<th>$row[0]</th>
					<th>$row[1]</th>
					<th>$row[2]</th>
					<th>$row[3]</th>
					<th>$row[4]</th>
				</tr>";
		}
		sqlsrv_free_stmt( $stmt);

		?>
	</table>
	</div>
	
	<div id="s">
	<h3>Inserare</h3>
	<form action="medicamente.php" method="post" >
	<p>Denumire <input class="caseta3" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<p>Producator <input class="caseta1" type="text" placeholder="introduceti producatorul" name="producator" required /></p>
	<p>Pret <input class="caseta2" type="text" placeholder="introduceti pretul" name="pret"  /></p>
	<p>Stoc <input class="caseta2" type="text" placeholder="introduceti stocul" name="stoc" /></p>
	<p>Raft <input class="caseta2" type="text" placeholder="introduceti raftul" name="raft" /></p>
	<button class="b1" type="submit" name="submit"><b>inserare</b></button> 
	
	
	<?php
	$accept=1;
	if(isset($_POST['submit'])){
		$denumire=$_POST['denumire'];
		$producator=$_POST["producator"] ;
		$pret=$_POST['pret'];
		$stoc=$_POST['stoc'];
		$raft=$_POST['raft'];
		$accept=1;
		$denumiri=sqlsrv_query($conn,"SELECT Denumire FROM Medicamente");
		while( $row = sqlsrv_fetch_array( $denumiri, SQLSRV_FETCH_NUMERIC) ) {
			if($row[0]==$denumire)
				$accept=0;
		}
		if($accept==1){
			$producatorr= "SELECT [Producator ID] 
						  FROM Producatori 
						  WHERE Denumire= '$producator'  ";
			$stmt= sqlsrv_query($conn, $producatorr);
			$id=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
			$sql="INSERT INTO Medicamente VALUES('$id[0]','$denumire' ,'$pret','$stoc', '$raft')";
			sqlsrv_query($conn,$sql);
		}
		
	}
	
	if($accept==0)
		echo "Medicament existent!"; 
	?>
	</form>
	
	<br/>
	<h3>Modificare</h3>
	<form action="medicamente.php" method="post" >
	<p>Denumirea medicamentului care se modifica <input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumireM" required /></p>
	<p>Modificari:</p>
	<p>Pret <input class="caseta2" type="text" placeholder="introduceti pretul" name="pret"   /></p>
	<p>Stoc <input class="caseta2" type="text" placeholder="introduceti stocul" name="stoc"  /></p>
	<p>Raft <input class="caseta2" type="text" placeholder="introduceti raftul" name="raft"  /></p>
	<button class="b1" type="submit" name="submit2"><b>modificare</b></button> 
	
	<?php
	if(isset($_POST['submit2'])){
		$denumireM=$_POST['denumireM'];
		$pret=$_POST['pret'];
		$stoc=$_POST['stoc'];
		$raft=$_POST['raft'];
		
		$d=sqlsrv_query($conn,"SELECT * FROM Medicamente WHERE Denumire= '$denumireM' ");
		$date=sqlsrv_fetch_array( $d, SQLSRV_FETCH_NUMERIC);
		
		if($pret == 0){
			$pret_final=$date[3];
		}
		else{
			$pret_final=$pret;
		}
		
		if($stoc==0)
			$stoc_final=$date[4];
		else
			$stoc_final=$stoc;
		
		if($raft=='')
			$raft_final=$date[5];
		else
			$raft_final=$raft;
		
		$sql="UPDATE Medicamente 
		      SET Pret='$pret_final', Stoc='$stoc_final', Raft='$raft_final'
			  WHERE Denumire='$denumireM'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	
	</form>
	<br/>
	
	<h3>Stergere</h3>
	<form action="medicamente.php" method="post" >
	<p>Denumirea medicamentului care se sterge <input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button class="b1" type="submit" name="submit3"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit3'])){
		$denumire=$_POST['denumire'];
		$sql="DELETE FROM Medicamente 
			  WHERE Denumire='$denumire'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	</form>
	<br/><br/><br/>
	
	
	
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="simptome.css">
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
		<li><a href="substante.php">Substante</a></li>
		<li><a class="active" href="simptome.php">Simptome</a></li>
		<li><a href="cauta.php">Cauta</a></li>
		<li><a href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>
	<div id="ss">
	<?php
		$sql="SELECT Denumire
			  FROM Simptome";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	
	<table class="simpt">
		<caption>Simptome</caption>
		<tr>
			<th>Simptom</th>
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
		$sql="SELECT M.Denumire, S.Denumire
		      FROM Medicamente M JOIN [Medicamente-Simptome] MS ON M.[Medicament ID]=MS.[Medicament ID]
				                 JOIN Simptome S ON S.[Simptom ID]=MS.[Simptom ID]";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	
	<table class="med">
		<caption>Medicamente-Simptome</caption>
		<tr>
			<th>Medicament</th>
			<th>Simptom</th>
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
	</div>
	<div id="s">
	<h3>Inserare</h3>
	<form action="simptome.php" method="post" >
	<p>Denumire <input class="caseta1" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button type="submit" name="submit"><b>inserare</b></button> 
	
	
	<?php
	$accept=1;
	if(isset($_POST['submit'])){
		$denumire=$_POST['denumire'];
		$accept=1;
		$denumiri=sqlsrv_query($conn,"SELECT Denumire FROM Simptome");
		while( $row = sqlsrv_fetch_array( $denumiri, SQLSRV_FETCH_NUMERIC) ) {
			if($row[0]==$denumire)
				$accept=0;
		}
		if($accept==1){
			$sql="INSERT INTO Simptome VALUES('$denumire' )";
			sqlsrv_query($conn,$sql);
		}
		
	}
	
	if($accept==0)
		echo "Simptom existent!"; 
	?>
	</form>
	
	
	<h3>Stergere</h3>
	<form action="simptome.php" method="post" >
	<p>Denumirea simptomului care se sterge <input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button type="submit" name="submit3"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit3'])){
		$denumire=$_POST['denumire'];
		$sql="DELETE FROM Simptome 
			  WHERE Denumire='$denumire'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	</form>
	<br/><br/><br/>
	
	<h5>Atribuiti medicamentelor simptome</h5>
	<form action="simptome.php" method="post" >
	<p>Medicament<input class="caseta5" type="text" placeholder="introduceti denumirea" name="med" required /></p>
	<p>Simptom<input class="caseta6" type="text" placeholder="introduceti denumirea" name="simp" required /></p>
	<button class="b1" type="submit" name="submit6"><b>adaugare</b></button> 
	
	<?php
		if(isset($_POST['submit6'])){
			$med=$_POST['med'];
			$simp=$_POST['simp'];
			
			$id=sqlsrv_query($conn,"SELECT [Medicament ID] FROM Medicamente WHERE Denumire= '$med' ");
			$id_med=sqlsrv_fetch_array( $id, SQLSRV_FETCH_NUMERIC);
			$idd=sqlsrv_query($conn,"SELECT [Simptom ID] FROM Simptome WHERE Denumire= '$simp' ");
			$id_simp=sqlsrv_fetch_array( $idd, SQLSRV_FETCH_NUMERIC);
			if($id_med!=0 && $id_simp!=0){
				$accept=1;
				$bb=sqlsrv_query($conn,"SELECT * FROM [Medicamente-Simptome] WHERE [Medicament ID]= '$id_med[0]' ");
				
				if($bb!=0){
					while($substanta=sqlsrv_fetch_array( $bb, SQLSRV_FETCH_NUMERIC) ) {
						if($substanta[1]==$id_simp[0]){
							$accept=0;
						}
					}
						
				}
				if($accept==1){
					$sql="INSERT INTO [Medicamente-Simptome] VALUES('$id_med[0]','$id_simp[0]')";
					sqlsrv_query($conn,$sql);
				}
				else echo "Simptom deja existent!";
			}	
			else{
				if($id_med==0)
					echo "Medicament inexistent!";
				if($id_simp==0)
					echo "Simptom inexistent!";
				
			}
		
		
		}
	
	?>
	
	</form>
	
	<br/><br/><br/>
	
	<h5>Stergere legatura</h5>
	<form action="simptome.php" method="post" >
	<p>Medicament<input class="caseta5" type="text" placeholder="introduceti denumirea" name="med" required /></p>
	<p>Simptom<input class="caseta6" type="text" placeholder="introduceti denumirea" name="simp" required /></p>
	<button type="submit" name="submit5"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit5'])){
		$denumireM=$_POST['med'];
		$denumireS=$_POST['simp'];
		
		$sql1=sqlsrv_query($conn, "SELECT [Medicament ID] FROM Medicamente WHERE Denumire='$denumireM'");
		$idM=sqlsrv_fetch_array( $sql1, SQLSRV_FETCH_NUMERIC);
		
		$sql2=sqlsrv_query($conn, "SELECT [Simptom ID] FROM Simptome WHERE Denumire='$denumireS'");
		$idS=sqlsrv_fetch_array( $sql2, SQLSRV_FETCH_NUMERIC);
		
		
		$sql="DELETE FROM [Medicamente-Simptome]
			  WHERE [Medicament ID]='$idM[0]' AND [Simptom ID]='$idS[0]'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	</form>
	<br/><br/><br/>
	
	</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="distribuitori.css">
</head>
<body>

	<?php
		$servername="DESKTOP-US6TA1H\SQLEXPRESS";
		$connectionInfo= array("database" => "Evidenta medicamentelor");
		$conn=sqlsrv_connect($servername, $connectionInfo);
	?>
	<ul>
		<li><a href="conexiune.php">Home</a></li>
		<li><a  href="medicamente.php">Medicamente</a></li>
		<li><a href="producatori.php">Producatori</a></li>
		<li><a class="active" href="distribuitori.php">Distribuitori</a></li>
		<li><a href="substante.php">Substante</a></li>
		<li><a href="simptome.php">Simptome</a></li>
		<li><a href="cauta.php">Cauta</a></li>
		<li><a href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>
	
	<div id="ss">
	<?php
		$sql="SELECT Denumire, Telefon, Gmail
			  FROM Distribuitori";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	
	
	<table class="distr">
		<caption>Distribuitori</caption>
		<tr>
			<th>Distribuitor</th>
			<th>Telefon</th>
			<th>Gmail</th>
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
	
	
	<?php
		$sql="SELECT M.Denumire, D.Denumire
			  FROM Medicamente M JOIN [Medicamente-Distribuitori] MD ON M.[Medicament ID]=MD.[Medicament ID]
				                 JOIN Distribuitori D ON D.[Distribuitor ID]=MD.[Distribuitor ID]";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	
	<table class="med">
		<caption>Medicamente-Distribuitori</caption>
		<tr>
			<th>Medicament</th>
			<th>Distribuitor</th>
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
	<form action="distribuitori.php" method="post" >
	<p>Denumire <input class="caseta1" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<p>Telefon <input class="caseta6" type="text" placeholder="introduceti telefonul" name="telefon" required /></p>
	<p>Gmail <input class="caseta7" type="text" placeholder="introduceti gmail-ul" name="gmail" required /></p>
	<button type="submit" name="submit"><b>inserare</b></button> 
	
	
	
	<?php
	$accept=1;
	if(isset($_POST['submit'])){
		$denumire=$_POST['denumire'];
		$telefon=$_POST['telefon'];
		$gmail=$_POST['gmail'];
		$accept=1;
		$denumiri=sqlsrv_query($conn,"SELECT Denumire FROM Distribuitori");
		while( $row = sqlsrv_fetch_array( $denumiri, SQLSRV_FETCH_NUMERIC) ) {
			if($row[0]==$denumire)
				$accept=0;
		}
		if($accept==1){
			$sql="INSERT INTO Distribuitori VALUES('$denumire' ,'$telefon', '$gmail')";
			sqlsrv_query($conn,$sql);
		}
		
	}
	
	if($accept==0)
		echo "Distribuitor existent!"; 
	?>
	</form>
	
	<br/>
	<h3>Modificare</h3>
	<form action="distribuitori.php" method="post" >
	<p>Denumirea distribuitorului care se modifica <input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<p>Modificari:</p>
	<p>Telefon <input class="caseta6" type="text" placeholder="introduceti telefonul" name="telefon" /></p>
	<p>Gmail <input class="caseta7" type="text" placeholder="introduceti gmail-ul" name="gmail" /></p>
	<button type="submit" name="submit2"><b>modificare</b></button> 
	
	<?php
	if(isset($_POST['submit2'])){
		$denumire=$_POST['denumire'];
		$telefon=$_POST['telefon'];
		$gmail=$_POST['gmail'];
		
		$d=sqlsrv_query($conn,"SELECT * FROM Distribuitori WHERE Denumire= '$denumire' ");
		$date=sqlsrv_fetch_array( $d, SQLSRV_FETCH_NUMERIC);
		if($telefon==''){
			$tel_final=$date[2];
		}
		else
			$tel_final=$telefon;
		
		if($gmail=='')
			$gmail_final=$date[3];
		else
			$gmail_final=$gmail;
		
		$sql="UPDATE Distribuitori 
		      SET Telefon='$tel_final', Gmail='$gmail_final'
			  WHERE Denumire='$denumire'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	
	</form>
	<br/>
	
	
	
	<h3>Stergere</h3>
	<form action="distribuitori.php" method="post" >
	<p>Denumirea distribuitorului care se sterge <input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button type="submit" name="submit3"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit3'])){
		$denumire=$_POST['denumire'];
		$sql="DELETE FROM Distribuitori 
			  WHERE Denumire='$denumire'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	</form>
	<br/><br/>
	
	<h5>Adauga distribuitor unui medicament</h5>
	<form action="distribuitori.php" method="post" >
	<p>Medicament<input class="caseta9" type="text" placeholder="introduceti denumirea" name="med" required /></p>
	<p>Distribuitor<input class="caseta5" type="text" placeholder="introduceti denumirea" name="distr" required /></p>
	<button class="b1" type="submit" name="submit4"><b>adaugare</b></button> 
	
	<?php
	if(isset($_POST['submit4'])){
		$med=$_POST['med'];
		$distr=$_POST['distr'];
		
		$id=sqlsrv_query($conn,"SELECT [Medicament ID] FROM Medicamente WHERE Denumire= '$med' ");
		$id_med=sqlsrv_fetch_array( $id, SQLSRV_FETCH_NUMERIC);
		$idd=sqlsrv_query($conn,"SELECT [Distribuitor ID] FROM Distribuitori WHERE Denumire= '$distr' ");
		$id_distr=sqlsrv_fetch_array( $idd, SQLSRV_FETCH_NUMERIC);
		
		if($id_med!=0 && $id_distr!=0){
			$sql="INSERT INTO [Medicamente-Distribuitori] VALUES('$id_med[0]','$id_distr[0]')";
			sqlsrv_query($conn,$sql);
		}	
		else{
			if($id_med==0)
				echo "Medicament inexistent!";
			if($id_distr==0)
				echo "Distribuitor inexistent!";
			
		}
		
		
	}
	
	?>
	
	</form>
	
	<br/><br/><br/>
	
	<h5>Stergere legatura</h5>
	<form action="distribuitori.php" method="post" >
	<p>Medicament<input class="caseta9" type="text" placeholder="introduceti denumirea" name="denumireM" required /></p>
	<p>Distribuitor<input class="caseta5" type="text" placeholder="introduceti denumirea" name="denumireD" required /></p>
	<button type="submit" name="submit5"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit5'])){
		$denumireM=$_POST['denumireM'];
		$denumireD=$_POST['denumireD'];
		$sql1=sqlsrv_query($conn, "SELECT [Medicament ID] FROM Medicamente WHERE Denumire='$denumireM'");
		$idM=sqlsrv_fetch_array( $sql1, SQLSRV_FETCH_NUMERIC);
		
		$sql2=sqlsrv_query($conn, "SELECT [Distribuitor ID] FROM Distribuitori WHERE Denumire='$denumireD'");
		$idD=sqlsrv_fetch_array( $sql2, SQLSRV_FETCH_NUMERIC);
		
		$sql="DELETE FROM [Medicamente-Distribuitori]
			  WHERE [Medicament ID]='$idM[0]' AND [Distribuitor ID]='$idD[0]'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	</form>
	<br/><br/><br/>
	</div>
</body>
</html>
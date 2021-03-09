<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="producatori.css">
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
		<li><a class="active" href="producatori.php">Producatori</a></li>
		<li><a href="distribuitori.php">Distribuitori</a></li>
		<li><a href="substante.php">Substante</a></li>
		<li><a href="simptome.php">Simptome</a></li>
		<li><a href="cauta.php">Cauta</a></li>
		<li><a href="statistici.php">Statistici</a></li>
		<li style="float:right"><a href="login.php">LOG OUT</a></li>
	</ul>
	
	<div id="ss">
	<?php
		$sql="SELECT Denumire, Tara, Oras, Strada, [Numar Strada], Telefon, Gmail
			  FROM Producatori";
		$stmt= sqlsrv_query($conn, $sql);
		if($stmt === false){
			die( print_r( sqlsrv_errors(), true) );
		}
	?>
	
	<table>
		<caption>Producatori</caption>
		<tr>
			<th>Producator</th>
			<th>Tara</th>
			<th>Oras</th>
			<th>Strada</th>
			<th>Nr. Strada</th>
			<th>Telefon</th>
			<th>Gmail</th>
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
				</tr>";
		}
		sqlsrv_free_stmt( $stmt);

		?>
	</table>
	</div>

	<div id="s">
	<h3>Inserare</h3>
	<form action="producatori.php" method="post" >
	<p>Denumire <input class="caseta1" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<p>Tara <input class="caseta2" type="text" placeholder="introduceti tara" name="tara" /></p>
	<p>Oras <input class="caseta3" type="text" placeholder="introduceti orasul" name="oras"  /></p>
	<p>Strada <input class="caseta4" type="text" placeholder="introduceti strada" name="strada" /></p>
	<p>Nr. Strada <input class="caseta5" type="text" placeholder="introduceti numarul strazii" name="nr" /></p>
	<p>Telefon <input class="caseta6" type="text" placeholder="introduceti telefonul" name="telefon" required /></p>
	<p>Gmail <input class="caseta7" type="text" placeholder="introduceti gmail-ul" name="gmail" required /></p>
	<button type="submit" name="submit"><b>inserare</b></button> 
	
	
	<?php
	$accept=1;
	if(isset($_POST['submit'])){
		$denumire=$_POST['denumire'];
		$tara=$_POST['tara'] ;
		$oras=$_POST['oras'];
		$strada=$_POST['strada'];
		$nr=$_POST['nr'];
		$telefon=$_POST['telefon'];
		$gmail=$_POST['gmail'];
		$accept=1;
		$denumiri=sqlsrv_query($conn,"SELECT Denumire FROM Producatori");
		while( $row = sqlsrv_fetch_array( $denumiri, SQLSRV_FETCH_NUMERIC) ) {
			if($row[0]==$denumire)
				$accept=0;
		}
		if($accept==1){
			$sql="INSERT INTO Producatori VALUES('$denumire' ,'$tara','$oras', '$strada', '$nr', '$telefon', '$gmail')";
			sqlsrv_query($conn,$sql);
		}
		
	}
	
	if($accept==0)
		echo "Producator existent!"; 
	?>
	</form>
	
	<br/>
	<h3>Modificare</h3>
	<form action="producatori.php" method="post" >
	<p>Denumirea producatorului care se modifica <input class="caseta8" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<p>Modificari:</p>
	<p>Tara <input class="caseta2" type="text" placeholder="introduceti tara" name="tara"  /></p>
	<p>Oras <input class="caseta3" type="text" placeholder="introduceti orasul" name="oras" /></p>
	<p>Strada <input class="caseta4" type="text" placeholder="introduceti strada" name="strada"  /></p>
	<p>Nr. Strada <input class="caseta5" type="text" placeholder="introduceti numarul strazii" name="nr"  /></p>
	<p>Telefon <input class="caseta6" type="text" placeholder="introduceti telefonul" name="telefon"  /></p>
	<p>Gmail <input class="caseta7" type="text" placeholder="introduceti adresa de gmail" name="gmail"  /></p>
	<button type="submit" name="submit2"><b>modificare</b></button> 
	
	<?php
	if(isset($_POST['submit2'])){
		$denumire=$_POST['denumire'];
		$tara=$_POST['tara'] ;
		$oras=$_POST['oras'];
		$strada=$_POST['strada'];
		$nr=$_POST['nr'];
		$telefon=$_POST['telefon'];
		$gmail=$_POST['gmail'];
		
		
		$d=sqlsrv_query($conn,"SELECT * FROM Producatori WHERE Denumire= '$denumire' ");
		$date=sqlsrv_fetch_array( $d, SQLSRV_FETCH_NUMERIC);
		
		if($tara == ''){
			$tara_final=$date[2];
		}
		else{
			$tara_final=$tara;
		}
		
		if($oras=='')
			$oras_final=$date[3];
		else
			$oras_final=$oras;
		
		if($strada=='')
			$strada_final=$date[4];
		else
			$strada_final=$strada;
		
		if($nr==0)
			$nr_final=$date[5];
		else
			$nr_final=$nr;
		
		if($telefon==0)
			$tel_final=$date[6];
		else
			$tel_final=$telefon;
		
		if($gmail=='')
			$gmail_final=$date[7];
		else
			$gmail_final=$gmail;
		
		
		$sql="UPDATE Producatori 
		      SET Tara='$tara_final', Oras='$oras_final', Strada='$strada_final', [Numar Strada]='$nr_final', Telefon='$tel_final', Gmail='$gmail_final'
			  WHERE Denumire='$denumire'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	
	</form>
	<br/>
	
	<h3>Stergere</h3>
	<form action="producatori.php" method="post" >
	<p>Denumirea producatorului care se sterge <input class="caseta4" type="text" placeholder="introduceti denumirea" name="denumire" required /></p>
	<button type="submit" name="submit3"><b>stergere</b></button> 
	
	<?php
	if(isset($_POST['submit3'])){
		$denumire=$_POST['denumire'];
		$sql="DELETE FROM Producatori 
			  WHERE Denumire='$denumire'";
		sqlsrv_query($conn,$sql);
		
	}
	
	?>
	
	</form>
	<br/><br/><br/>
	</div>
</body>
</html>
include "conexiune.php";
<?php
	/*
	$sql="INSERT INTO Medicamente ([Producator ID],Denumire,Pret,Stoc,Raft) VALUES(1,'Nurofen',20,50,'10B')";
	$params = array(1,"some data");
	$stmt=sqlsrv_query($conn, $sql,$params);
	if( $stmt === false){
		echo "probleme";
	}
	else {
		echo "inregistrare realizata";
	}
	*/
	/*
	$sql="DELETE FROM Medicamente WHERE Denumire='Nurofen'";
	$params = array(1,"some data");
	$stmt=sqlsrv_query($conn, $sql,$params);
	if( $stmt === false){
		echo "probleme";
	}
	else {
		echo "stergere realizata";
	}
	*/
	$sql="SELECT * FROM Medicamente";
	$stmt= sqlsrv_query($conn, $sql);
	if($stmt === false){
		 die( print_r( sqlsrv_errors(), true) );
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
      echo $row[0].", ".$row[1].", ".$row[2].", ".$row[3].", ".$row[4].", ".$row[5]."<br />";
	}

	sqlsrv_free_stmt( $stmt);
	
	<?php
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
      echo $row[0]."&nbsp&nbsp&nbsp".$row[1]."&nbsp&nbsp&nbsp".$row[2]."&nbsp&nbsp&nbsp".$row[3]."&nbsp&nbsp&nbsp".$row[4]."&nbsp&nbsp&nbsp".$row[5]."&nbsp&nbsp&nbsp".$row[6]."&nbsp&nbsp&nbsp".$row[7]."&nbsp&nbsp&nbsp".$row[8]."<br />";
	}

	sqlsrv_free_stmt( $stmt);
	?>
?>
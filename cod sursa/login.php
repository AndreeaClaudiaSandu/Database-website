<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="login.css">
</head>
<body>
<form action="login.php" method="post" >
 <p class="italic">Username<input type="text" placeholder="enter username" name="username" required /></p>
 <p class="italic">Password  <input type="password" placeholder="enter password" name="password" required /></p>
 <button type="submit" name="submit"><b>Login</b></button> 
</form>
<?php
	if(isset($_POST['submit'])){
		if($_POST["username"]=="admin" AND $_POST["password"]=="1234"){
			header("Location: conexiune.php");
		}
		else{
			header("Location: login.php");
		}
		
	}
?>
</body>
</html>
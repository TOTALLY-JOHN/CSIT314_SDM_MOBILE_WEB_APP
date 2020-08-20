<?php 
session_start();
if(!$_SESSION["userName"]) {
	header("Location:login.php");
}

if(count($_POST)>0) {
	$con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');

	$cpPwd = md5(mysqli_real_escape_string($con, trim($_POST['psw1'])));
	$cpID = $_SESSION["userID"];

	$query = mysqli_query($con,"UPDATE contact_person SET cpPwdChanged = 1, cpPwd = '$cpPwd' WHERE cpID = '$cpID'");

	if ($query) {
		$_SESSION["changePassDone"] = "done";
		header("Location:cp_main.php");
	}
	else {
		printf("error: %s\n", mysqli_error($con));
	}
	mysqli_close($con);
}
?>
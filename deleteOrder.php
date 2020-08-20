<?php 
session_start();
if(!$_SESSION["userName"]) {
	header("Location:login.php");
}

if(count($_POST)>0) {
	$con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');

	$orderID = mysqli_real_escape_string($con, trim($_POST['txtOrderID']));

	$query = mysqli_query($con,"DELETE FROM orders WHERE orderID = (SELECT orderID FROM orders JOIN clinic ON orders.clinicName = clinic.clinicName WHERE orderID = '$orderID' AND clinicOICName = '{$_SESSION['userName']}')");

	if (mysqli_affected_rows($con) == 1) {
		$_SESSION['deleteOrd'] = "Order was removed successfully.";
	}
	else {
		$_SESSION['deleteOrd'] = "Failed to remove the order. Please enter correct orderID.";
	}

	if (isset($_SESSION['deleteOrd'])) {
		header("Location:OICMain.php");
	}

	mysqli_close($con);
}
?>
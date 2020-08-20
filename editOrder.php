<?php 
session_start(); 
if(!$_SESSION["userName"]) {
	header("Location:login.php");
}

if(count($_POST)>0) {
	$con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');

	$clinicName = mysqli_real_escape_string($con, trim($_POST['clinicIDDropDown']));
	$itemType = mysqli_real_escape_string($con, trim($_POST['itemTypeDropDown']));
	$itemQuantity = mysqli_real_escape_string($con, trim($_POST['txtItemQuantity']));
	$deliveryDate = mysqli_real_escape_string($con, trim($_POST['dateName']));
	$orderDetails = mysqli_real_escape_string($con, trim($_POST['txtOrderDetails']));
	$orderDate = date("Y-m-d");
	if (isset($_POST['orderStatus'])) {
		$orderStatus = mysqli_real_escape_string($con, trim($_POST['orderStatus']));
	}
	else {
		$orderS = "SELECT orderStatus FROM orders WHERE orderID = '".$_SESSION["orderID"]."'";
		$result = @mysqli_query ($con,$orderS);
		$row = mysqli_fetch_array ($result, MYSQLI_NUM);
		$orderStatus = $row[0];
	}

	$query = mysqli_query($con,"UPDATE orders SET clinicName = '$clinicName', itemType = '$itemType', itemQuantity = '$itemQuantity', orderDate = '$orderDate', deliveryDate = '$deliveryDate', orderStatus = '$orderStatus', orderDetails = '$orderDetails' WHERE orderID = '".$_SESSION["orderID"]."' ");

	if (mysqli_affected_rows($con) == 1) {
		$_SESSION['editOrder'] = "Order was edited successfully.";
	}
	else {
		$_SESSION['editOrder'] = "Failed to edit the order!";
	}

	if (isset($_SESSION['editOrder'])) {
		header("Location:OICMain.php");
	}

	mysqli_close($con);
}
?>
<?php 
session_start(); 
if(!$_SESSION["userName"]) {
   header("Location:login.php");
}

if(count($_POST)>0) {
   $con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');

   $clinicName = mysqli_real_escape_string($con, trim($_POST['clinicNameDropDown']));
   $itemType = mysqli_real_escape_string($con, trim($_POST['itemTypeDropDown']));
   $itemQuantity = mysqli_real_escape_string($con, trim($_POST['txtItemQuantity']));
   $deliveryDate = mysqli_real_escape_string($con, trim($_POST['dateName']));
   $orderDetails = mysqli_real_escape_string($con, trim($_POST['txtOrderDetails']));
   $orderDate = date("Y-m-d");
   $orderStatus = "Pending";

   $query = mysqli_query($con,"INSERT INTO orders (clinicName, itemType, itemQuantity, orderDate, deliveryDate, orderStatus, orderDetails) VALUES ('$clinicName', '$itemType', '$itemQuantity', '$orderDate', '$deliveryDate', '$orderStatus', '$orderDetails')");

   if (mysqli_affected_rows($con) == 1) {
      $_SESSION['addOrder'] = "Order was added successfully.";
   }
   else {
      $_SESSION['addOrder'] = "Failed to add the order!";
   }

   if (isset($_SESSION['addOrder'])) {
      header("Location:OICMain.php");
   }

   mysqli_close($con);
}
?>
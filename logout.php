<?php
session_start();
unset($_SESSION["userName"]);
unset($_SESSION["userID"]);
unset($_SESSION["attempts"]);
unset($_SESSION["orderID"]);
header("Location:login.php");
?>
<?php
session_start();
$varCheck = 0;
if(count($_POST)>0) {
  $con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');
  $result = mysqli_query($con,"SELECT * FROM users WHERE userID ='" . $_POST["loginID"] . "' and userPwd = '". md5($_POST["adminPassword"])."'");
  $result2 = mysqli_query($con,"SELECT * FROM contact_person WHERE cpID ='" . $_POST["loginID"] . "' and cpPwd = '". md5($_POST["adminPassword"])."'");
  $result3 = mysqli_query($con,"SELECT * FROM users WHERE userID ='" . $_POST["loginID"] . "'");
  $row  = mysqli_fetch_array($result);
  $row2 = mysqli_fetch_array($result2);
  $row3 = mysqli_fetch_array($result3);
  if (isset($_SESSION["userID"]) && $_SESSION["userID"] == $_POST["loginID"] && is_array($row3)) {
   $varCheck = 1;
 }
 else {
   $_SESSION["attempts"] = 1;
 }

 $_SESSION["userID"] = $_POST["loginID"];

 if (is_array($row2)) {
   if ($row2['cpPwdChanged'] == 0){
    $_SESSION["userName"] = $row2['cpName']; 
    echo '<script type="text/javascript">
    window.onload = function() {openForm();};
    </script>'; 
    echo '<script type="text/javascript">alert("Contact person logs in for the first time need to set up new password.")</script>';
  }
  else {
    $_SESSION["userName"] = $row2['cpName'];
    if(isset($_SESSION["userName"])) {
      header("Location:cp_main.php");
    } 
  }
}
else if(is_array($row)) {
 $_SESSION["userName"] = $row['userName'];
 if ($row['userStatus'] == "Locked") {
  echo '<script type="text/javascript">alert("Your account is locked! Contact the system administrator, please.")</script>';
}
else if(isset($_SESSION["userName"])) {
  header("Location:OICMain.php");
}
}
else {
  echo '<script type="text/javascript">alert("Invalid Username or Password!")</script>';
  if ($varCheck == 1) {
   $_SESSION["attempts"] = $_SESSION["attempts"] + 1;
 }
 if ($_SESSION["attempts"] == 3) {
  $query = mysqli_query($con,"UPDATE users SET userStatus = 'Locked' WHERE userID = '" . $_SESSION["userID"] . "' ");
  if ($query) {
    echo '<script type="text/javascript">alert("You failed to login three times, so this ID is locked now. \nPlease, contact the system administrator.")</script>';
  }
}
}
}
?>

<!DOCTYPE html>
<html>
<head>
 <meta content='width=device-width, initial-scale=1' name='viewport'/>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="login.css">

 <style type="text/css">

  body 
  {
   background-position: center center;
   background-repeat:no-repeat;
   background-image: linear-gradient(to top, #c4dce0 , #147382);
   background-size: 2000px 2000px;
 }

 #bigBox input[type=text], [type=email],[type=tel],[type=password] { /* CSS BOX MODEL*/
   width: 100%;
   padding: 12px 20px;
   margin: 8px 0;
   display: inline-block;
   border: 1px;
   border-radius: 10px;
   box-sizing: border-box;
   font-size: 90%;
 }

 input[type=text],[type=email],[type=tel],[type=password]:enabled { /* css psuedo class 3 */
   font-family: sans-serif;
   font-style: italic;
 }


 /* Button used to open the contact form - fixed at the bottom of the page */
 .open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  top: 5%;
  bottom: 30%;
  right: 5%;
  left: 5%;
  border: 3px solid #f1f1f1;
  z-index: 1;
}

/* Add styles to the form container */
.form-container {
  width: auto;
  padding: 5%;
  background-color: #ADD8E6;
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 20px;
  opacity: 0.8;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}


</style>

</head>
<body>

  <div class="formBox" style="margin:50px auto 50px auto;">
    <center><h1><span style="color: #11939a;"><font size="6"><p>MediSupply System</p></font></span></h1></center>

    <form method="post" action="">
     <div id="bigBox">

      Login ID: <input type="text" name="loginID" id="loginID" placeholder="Enter Login ID" title="Enter Login ID" required> <br/><br/>

      Password: <input type="password" name="adminPassword" id="adminPassword" placeholder="Enter Password" pattern="^.{8,}" title="Password must be minimum 8 Characters" required><br/><br>
      <input type="submit" value="LOGIN"><br /><br />
      <div style="text-align:center;"><a href="forgotPassword.php">Forgot Password?</a></div>
    </div>
  </form>
</div>

<div class="form-popup" id="myForm">
  <form action="newPwdAction.php" class="form-container" method="post">
    <h2 style="text-align: center;">Set New Password For Contact Person</h2>

    <label for="newPassword"><b>New Password</b></label>
    <input type="password" placeholder="Enter password" pattern="^.{8,}" name="psw1" id="txtPassword" title="Password must be minimum 8 Characters" required>

    <label for="psw"><b>Confirm New Password</b></label>
    <input type="password" placeholder="Enter Confirm Password" pattern="^.{8,}" name="psw2" id="txtConfirmPassword" title="Password must be minimum 8 Characters" required>

    <input type="submit" class="btn" onclick="return Validate()"><br /><br />

  </form>
</div>
<script>
  function openForm() {
    document.getElementById("myForm").style.display = "block";
  }

  function Validate() {
    var password = document.getElementById("txtPassword").value;
    var confirmPassword = document.getElementById("txtConfirmPassword").value;
    if (password != confirmPassword) {
      alert("Passwords do not match.");
      return false;
    }
    return true;
  }
</script>

</body>
</html>
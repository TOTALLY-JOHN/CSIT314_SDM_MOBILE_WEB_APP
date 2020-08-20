<?php
  $con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');
?>

<!DOCTYPE html>
<html>
<head>
 <meta content='width=device-width, initial-scale=1' name='viewport'/>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="login.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
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
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
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
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}

div.floating { 
      position: static; /*For logout button*/
      right: 2;
      bottom: 6;
      width: 10px;
      margin-right:100px;
    }


</style>
</head>
<body>
  <div class="floating">
  <a href="login.php">
    <img border="0" alt="back" src="icons/back.svg" width="450%" height="450%">
  </a>
</div>

  <div class="formBox" style="margin:50px auto 50px auto;">
    <center><h1><span style="color: #11939a;"><font size="6"><p>MediSupply System</p></font></span></h1></center>

    <form method="post" action="">
     <div id="bigBox">
      Login ID: <input type="text" name="loginID" id="loginID" placeholder="Enter Login ID" title="Enter Login ID" required> <br/><br/>

      User Type: <br />
      <select style="width:100%;" id="userTypeDropDown" name="userTypeDropDown" required>
        <option value="OIC">OIC</option>
        <option value="CP">CP</option>
      </select><br /><br />

      Personal Question: <br />
        <select style="width:100%;" id="personalQuestionDropDown" name="personalQuestionDropDown" required>
          <option selected="true" value="" disabled="disabled">Select your personal question</option>
            <?php
              $query = "SELECT * FROM personal_questions";
              $result = @mysqli_query ($con,$query);
              if ($result) {
                $num = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo '<option style="width:100%;" value="'.$row["questionContent"].'">'.$row["questionContent"].'</option>';
                }
              }
            ?>
        </select> <br/><br/>

      Personal Answer: <input type="text" name="personalAnswer" id="personalAnswer" placeholder="Enter Personal Answer" title="Enter Personal Answer" required> <br/><br/>

      New Password: <input type="password" name="newPassword" id="newPassword" placeholder="Enter New Password" pattern="^.{8,}" title="Password must be minimum 8 Characters" required><br/><br>
      <input type="submit" value="SUBMIT"><br /><br />
      
    </div>
  </form>
</div>

<script>
</script>
<?php

  if(count($_POST) > 0) {

    $userType = $_POST["userTypeDropDown"];

    $userID = mysqli_real_escape_string($con, trim($_POST['loginID']));
    $newPwd = md5(mysqli_real_escape_string($con, trim($_POST['newPassword'])));
    $personalQuestion = mysqli_real_escape_string($con, trim($_POST['personalQuestionDropDown']));
    $personalAnswer = mysqli_real_escape_string($con, trim($_POST['personalAnswer']));

    if($userType == "OIC")
    {
      $result = mysqli_query($con, "SELECT * FROM users WHERE userID = '$userID' AND personalQuestion = '$personalQuestion' AND personalAnswer = '$personalAnswer'");

      $row  = mysqli_fetch_array($result);

      if (is_array($row)) 
      {
        $result1 = mysqli_query($con, "UPDATE users SET userPwd = '$newPwd' WHERE userID = '$userID'");

        if (mysqli_affected_rows($con) == 1) {
          echo 
          '<script type="text/javascript">
          $(document).ready(function(){
            $("#successModal").modal("show");
            });
            </script>
            ';
        } 
        else 
        {
            echo '<script type="text/javascript">
            $(document).ready(function(){
              $("#failureModal").modal("show");
              });
              </script>
              ';
        }
      }
      else {
        echo '<script type="text/javascript">
            $(document).ready(function(){
              $("#failureModal").modal("show");
              });
              </script>
              ';
      }
    }
    else
    {
      $result = mysqli_query($con, "SELECT * FROM contact_person WHERE cpID = '$userID' AND personalQuestion = '$personalQuestion' AND personalAnswer = '$personalAnswer'");

      $row  = mysqli_fetch_array($result);


      if (is_array($row)) 
      {
        $result1 = mysqli_query($con, "UPDATE contact_person SET cpPwd = '$newPwd' WHERE cpID = '$userID'");

        if (mysqli_affected_rows($con) == 1) {
          echo 
          '<script type="text/javascript">
          $(document).ready(function(){
            $("#successModal").modal("show");
            });
            </script>
            ';
        } 
        else 
        {
            echo '<script type="text/javascript">
            $(document).ready(function(){
              $("#failureModal").modal("show");
              });
              </script>
              ';
        }
      }
      else {
        echo '<script type="text/javascript">
            $(document).ready(function(){
              $("#failureModal").modal("show");
              });
              </script>
              ';
      }
    }

  }

  mysqli_close($con);
?>
  <div class="modal fade" id="successModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Successful Message</h4>
        </div>
        <div class="modal-body">
          <p>Password reset successfully!</p>
        </div>
        <div class="modal-footer">
          <a class="btn btn-primary" href="login.php">GO TO LOGIN PAGE</a>
        </div>
      </div>

    </div>
  </div>

  <div class="modal fade" id="failureModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Error Message</h4>
        </div>
        <div class="modal-body">
          <p>Your information is not correct!</p>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="forgotPassword.php">INPUT AGAIN</a>
        </div>
      </div>

    </div>
  </div>
</body>
</html>


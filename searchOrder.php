<?php 
session_start();

if(!$_SESSION["userName"]) {
  header("Location:login.php");
}

if(count($_POST)>0) {
  $con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');

  $orderID = mysqli_real_escape_string($con, trim($_POST['orderID']));
  $_SESSION["orderID"] = $orderID;

  $ulti = "SELECT * FROM orders JOIN clinic ON orders.clinicName = clinic.clinicName WHERE orderID = '$orderID' AND clinicOICName = '{$_SESSION['userName']}'";
  $resultUlti = @mysqli_query ($con,$ulti);
  $num = mysqli_num_rows($resultUlti);

  if ($num > 0) {
    $rowUlti = mysqli_fetch_array ($resultUlti, MYSQLI_NUM);
  }
  else {
    $_SESSION["orderIDError"] = "Failed to find the order. Please enter correct orderID !";
    header("Location:OICMain.php");
  }

}
?>
<!DOCTYPE html>
<html>
<head>
	<meta content='width=device-width, initial-scale=1' name='viewport'/>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	
	<style type="text/css">

		body 
		{
			background-position: center center;
			background-repeat:no-repeat;
			background-image: linear-gradient(to top, #c4dce0 , #147382);
			background-size: 2000px 2000px;
		}

		div.floating { 
			position: static; /*For logout button*/
			right: 2;
			bottom: 6;
			width: 10px;
			margin-right:100px;
		}
		
		#bigBox input[type=text], [type=number], [type=email],[type=tel],[type=password] { /* CSS BOX MODEL*/
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

		#dateBox input[name=dateName] {
			width: 100%;
			padding: 12px 2px;
			margin: 8px 0;
			display: inline-block;
			min-width: 48.8%;
			border: 1px;
			border-radius: 10px;
			font-size: 90%;
		}

		select {
			width: 100%;
			padding: 12px 2px;
			margin: 8px 0;
			display: inline-block;
			min-width: 48.8%;
			border: 1px;
			border-radius: 10px;
			font-size: 90%;
		}

		option {
			width: 20%;
			margin: 8px 0;
			
			font-size: 70%;
		}


		

	</style>

</head>
<body>
	
<div class="floating">
	<a href="OICMain.php">
		<img border="0" alt="back" src="icons/back.svg" width="450%" height="450%">
	</a>
</div>

<div class="formBox">
		<center><h1><span style="color: #11939a;" ><font size="6"><p>Edit Order</p></font></span></h1></center>

		<form action="editOrder.php" method="post">
			<div id="bigBox">

				Clinic's Name:
				<select id="clinicIDDropDown" name="clinicIDDropDown">
			        <?php
			        echo '<option value="'.$rowUlti[1].'">'.$rowUlti[1].'</option>';

			        $query = "SELECT clinicName FROM clinic WHERE clinicName != '".$rowUlti[1]."' ORDER BY clinicName ASC";
			        $result = @mysqli_query ($con,$query);
			        if ($result) {
			          while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			            echo '<option value="'.$row["clinicName"].'">'.$row["clinicName"].'</option>';
			          }
			        }
			        ?>

			     </select> <br/>

				Item Category:
				<select id="itemCategoryDropDown" name="itemCategoryDropDown" onChange="getType(this.value);">
			        <option selected="true" value="" disabled="disabled">Select Item Category</option>
			        <?php

			        $sql1="SELECT itemCategory FROM items GROUP BY itemCategory";
			        $results=$con->query($sql1); 
			        while($rs=$results->fetch_assoc()) { 
			          ?>
			          <option id="<?php echo $rs["itemCategory"]; ?>" value="<?php echo $rs["itemCategory"]; ?>"><?php echo $rs["itemCategory"]; ?></option>
			          <?php
			        }
			        ?>
      			</select> <br/>

				Item Type:
				<select id="itemTypeDropDown" name="itemTypeDropDown">
			        <?php
			        echo '<option value="'.$rowUlti[2].'">'.$rowUlti[2].'</option>';
			        ?>
			    </select> <br/>

				Item Quantity: 
				<input type="number" min="1" value="<?php echo $rowUlti[3]; ?>" name="txtItemQuantity" id="txtItemQuantity" placeholder="Enter Item Quantity" title="Enter Item Quantity"> <br/>

				<div id="dateBox">
					<b>Date of Delivery</b> <br><input type="date" value="<?php echo $rowUlti[5]; ?>" min="today" name="dateName" id="dateId">
				</div>

				Order Status:
				<select id="orderStatus" name="orderStatus">
			        <?php
			        echo '<option selected="selected" disabled="disabled" value="'.$rowUlti[6].'">'.$rowUlti[6].'</option>';
			        ?>
			        <option value="Pending">Pending</option>
			        <option value="Delivering">Delivering</option>
			        <option value="Received">Received</option>
			    </select>

				Order Details: <input type="text" name="txtOrderDetails" value="<?php echo $rowUlti[7]; ?>" id="txtOrderDetails" placeholder="Enter Order Details" title="Enter Order Details"> <br/>
				
				<input type="submit" value="UPDATE">
			</div>
		</form>
</div>
<script type="text/javascript">
    function getType(val) {
      $.ajax({
        method: 'POST',
        data: 'itemCategory=' + val,
        url: 'categoryData.php',

        success: function(data){
          $("#itemTypeDropDown").html(data);
        }
      });
    }

    var dt = new Date();
    var year = dt.getFullYear();
    var month = dt.getMonth() + 1;
    if(month < 10) {
      month = "0" + month;
    }
    var date = dt.getDate();
    if(date < 10) {
      date = "0" + date;
    }

    today = year+'-'+month+'-'+date;
    document.getElementById("dateId").setAttribute("min", today);

  </script>
			
</body>
</html>
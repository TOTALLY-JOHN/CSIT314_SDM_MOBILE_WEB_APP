<?php
session_start();
$con = @mysqli_connect ('localhost', 'root', '', 'medisupply') OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
?>
<!DOCTYPE html>
<html lang="en" >
<head>
	<meta content='width=device-width, initial-scale=1' name='viewport'/>
	<meta charset="UTF-8">
	<title>MEDISUPPLY</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<link rel="stylesheet" href="./OICMainStyle.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
</head>
<body style="background: linear-gradient(to top, white , #147382);" onload="startTime()">
	<?php
	if (isset($_SESSION["orderIDError"])) {
		echo '<script type="text/javascript">alert("'.$_SESSION["orderIDError"].'")</script>';
		unset($_SESSION["orderIDError"]);
	}
	if (isset($_SESSION['addOrder'])) {
		echo '<script type="text/javascript">alert("'.$_SESSION["addOrder"].'")</script>';
		unset($_SESSION['addOrder']);
	}

	if (isset($_SESSION['editOrder'])) {
		echo '<script type="text/javascript">alert("'.$_SESSION["editOrder"].'")</script>';
		unset($_SESSION['editOrder']);
	}

	if (isset($_SESSION['deleteOrd'])) {
		echo '<script type="text/javascript">alert("'.$_SESSION["deleteOrd"].'")</script>';
		unset($_SESSION['deleteOrd']);
	}

	?>
	<!-- partial:index.partial.html -->
	<link href="https://fonts.googleapis.com/css?family=Work+Sans:400,600,700&display=swap" rel="stylesheet">
	<div>
		<b id="txt" style="font-size: 100%; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; color: white;"><span id="dateToday"></span></b>
		<b style="font-size: 100%; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; color: white;"><span id="dateTime"></span></b>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<b style="color: white"><?php
		if($_SESSION["userName"]) {
			?>
			Welcome <?php echo $_SESSION["userName"];}
			else {
				header("Location:login.php");
			}
			?>
		</b>
	</div>
	<div  class="layout">

		<!-- addOrder TAB -->
		<input name="nav" type="radio" class="nav addOrder-radio" id="addOrder" checked="checked" /> <!-- THE TAB FOR OIC -->
		<div class="page addOrder-page">
			<div>
				<table>
					<form id="form1" method="post" action="addOrder.php">
						<div class="page-contents" id="bigBox">
							<b>Clinic's Name:</b>
							<select id="clinicNameDropDown" name="clinicNameDropDown" required>
								<option selected="true" value="" disabled="disabled">Select Clinic's Name</option>
								<?php
								$query = "SELECT clinicName FROM clinic WHERE clinicOICName	= '{$_SESSION['userName']}' ORDER BY clinicName ASC";
								$result = @mysqli_query ($con,$query);
								if ($result) {
									$num = mysqli_num_rows($result);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
										echo '<option value="'.$row["clinicName"].'">'.$row["clinicName"].'</option>';
									}
								}
								?>
							</select> <br/><br/>

							<b>Item Category:</b>
							<select id="itemCategoryDropDown" name="itemCategoryDropDown" onChange="getType(this.value);" required>
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
							</select> <br/><br/>

							<b>Item Type:</b>
							<select id="itemTypeDropDown" name="itemTypeDropDown" required>
								<option selected="true" value="" disabled="disabled">Select Item Type</option>


							</select> <br/><br/>

							<b>Item Quantity:</b> <input type="number" min="1" name="txtItemQuantity" id="txtItemQuantity" placeholder="Enter Item Quantity" title="Enter Item Quantity" required> <br/><br/>

							<div id="dateBox">
								<b>Date of Delivery</b><input type="date" name="dateName" id="dateId" required>
							</div><br/>

							<b>Order Details:</b> <input type="text" name="txtOrderDetails" id="txtOrderDetails" placeholder="Enter Order Details" title="Enter Order Details"> <br/><br><br/>

							<input type="submit" value="ADD"> <br/><br/><br/><br/><br/><br/>
						</div>
					</form>

				</table>
			</div>
		</div>
		<label class="nav" for="addOrder">
			<span>
				<img class="icons" src="icons/addOrder.svg"  />

				<div class="a">Add Order</div>
			</span> <!-- THIS IS USED FOR DISPLAYING THE FONT AND TAB NICELY WITH ICONS-->
		</label>

		<!-- editOrder TAB -->
		<input name="nav" type="radio" class="editOrder-radio" id="editOrder" />
		<div class="page editOrder-page">
			<div style="position:absolute; top:0px;">
				<table style="margin:0px auto 0px auto;">
					<form method="post" action="searchOrder.php">
						<div class="page-contents" id="bigBox">

							<b>Order ID:</b> <input type="text" name="orderID" id="orderID" placeholder="Enter Order ID" title="Enter Order ID" required> <br/><br/>
							<input class="search" type="submit" value="SEARCH">

						</div>
					</form>

				</table>
			</div>
		</div>
		<label class="nav" for="editOrder">
			<span>
				<img class="icons" src="icons/editOrder.svg"  />
				<div class="a">Edit Order</div>
			</span>
		</label>

		<!-- deleteOrder TAB -->
		<input name="nav" type="radio" class="deleteOrder-radio" id="deleteOrder" />
		<div class="page deleteOrder-page">
			<div style="position:absolute; top:0px;">
				<table style="margin:0px auto 0px auto;">
					<form method="post" action="deleteOrder.php">
						<div class="page-contents" id="bigBox">
							<b>Order ID:</b> <input type="text" name="txtOrderID" id="txtOrderID" placeholder="Enter Order ID" 
							title="Enter Order ID" required> <br/><br/>
							<!--<b>Clinic Name:</b> <input type="text" name="txtClinicName" id="txtName" placeholder="Enter Clinic Name" title="Enter Clinic Name" required> <br/><br/>
							-->
							<input class="delete" type="submit" value="DELETE"><br/><br/><br/><br/><br/><br/><br/><br/>

						</div>
					</form>

				</table>

			</div>
		</div>
		<label class="nav" for="deleteOrder">
			<span>
				<img class="icons" src="icons/deleteOrder.svg" />
				<div class="a">Delete Order</div>
			</span>
		</label>

		<!-- viewOrder -->
		<input name="nav" type="radio" class="viewOrder-radio" id="viewOrder"/>
		<div class="page viewOrder-page">
			<div class="outer" id="orderViewDataTable" style="top:0px;">
				<?php
				$q = "SELECT * FROM orders JOIN clinic ON orders.clinicName = clinic.clinicName WHERE clinicOICName = '{$_SESSION['userName']}' ORDER BY orderID ASC";
				$r = @mysqli_query ($con, $q);
				if ($r) {
					
					$num = mysqli_num_rows($r);
					if($num > 0)
					{
						echo '<table style="border-collapse: collapse;" border="1">
						<thead><tr>
						<td><b>Order ID</b></td>
						<td><b>Item Type</b></td>
						<td><b>Item Quantity</b></td>
						<td><b>Order Date</b></td>
						<td><b>Delivery Date</b></td>
						<td><b>Order Status</b></td>
						<td><b>Order Details</b></td>
						</tr><thead><tbody>';
					}
					else {
						echo '<p style="text-align: center; font-weight:bold;">There are no records to receive.<br>Please Contact Admin or OIC<br>to Assign Orders to your Clinic.</p>';
					}
					while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						echo '<tr><td>' . $row['orderID'] . '</td><td>'
						. $row['itemType'] . '</td><td>' . $row['itemQuantity'] . '</td><td>' 
						. $row['orderDate'] . '</td><td>' . $row['deliveryDate'] . '</td><td>' 
						. $row['orderStatus'] . '</td><td>' . $row['orderDetails'] . '</td></tr>';
					}
					echo '</tbody></table>';
					mysqli_free_result ($r);
				}
				?>

			</div>
		</div>
		<label class="nav" for="viewOrder">
			<span>
				<img class="icons" src="icons/viewOrder.svg" />
				<div class="a">View Order</div>
			</span>
		</label>

		<input name="nav" type="radio" class="logout-radio" id="logout" />
		<div class="page logout-page">
			<div class="page-contents" style="align-content: center; position: absolute; top:0px;">
				<h2 style="text-align: center;">Are you sure you want to Logout? <br> <br> 
					<a style="text-align: center; width: 80%;" class="btn btn-danger" href="logout.php">Yes</a></h2>
			</div>
		</div>
		<label class="nav" for="logout">
			<span>
				<img class="icons" src="icons/logout.svg" />
				<div class="a">LogOut </div>
			</span>
		</label>
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

		function startTime() {
			var today = new Date();
			var h = today.getHours();
			var m = today.getMinutes();
			var s = today.getSeconds();
			m = checkTime(m);
			s = checkTime(s);
			document.getElementById('dateTime').innerHTML =
			h + ":" + m + ":" + s;
			var t = setTimeout(startTime, 500);
		}

		function checkTime(i) {
	  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
	  return i;
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
	document.getElementById("dateToday").innerHTML = year + "-" + month + "-" + date + " ";

	today = year+'-'+month+'-'+date;
	document.getElementById("dateId").setAttribute("min", today);

</script>

</body>
</html>
<?php
mysqli_close($con);
?>

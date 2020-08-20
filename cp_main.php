<?php
session_start();
    // Connect to the database
$dbc = @mysqli_connect ('localhost', 'root', '', 'medisupply') OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
?>

<!DOCTYPE html>
<html lang="en" >
<head>
	<meta content='width=device-width, initial-scale=1' name='viewport'/>
	<meta charset="UTF-8">
	<title>MEDISUPPLY</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<link rel="stylesheet" href="./cp_main_style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
</head>
<body style="background: linear-gradient(to top, white , #147382);" onload="startTime()">
	<?php
	if (isset($_SESSION["changePassDone"]))
		echo '<script type="text/javascript">alert("Password were updated successfully.")</script>';
	unset($_SESSION["changePassDone"]);
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
			} ?>
		</b>
	</div>
	<div class="layout">

		<!-- editOrder TAB -->
		<input name="nav" type="radio" class="nav editOrder-radio" id="editOrder" checked="checked" /> <!-- THE TAB FOR OIC -->
		<div class="page editOrder-page">
			<div class="page-contents">
				<table style="margin:0px auto 0px auto;">
					<form id="form1" method="post" action="cpUpdateOrderAction.php">
						<div class="page-contents" id="bigBox1">
							<?php
	// RETRIEVE ALL DATA OF THE CLINIC OF THIS CP
							$q = "SELECT clinicID, clinicName, clinicAddress, clinicArea, clinicTelephone, clinicDetails FROM clinic 
							      WHERE clinicName = (SELECT clinicName FROM contact_person WHERE cpName = '{$_SESSION['userName']}')";
							$r = @mysqli_query ($dbc, $q);
							if (mysqli_num_rows($r) >= 1) {
								$row = mysqli_fetch_array ($r, MYSQLI_NUM);
								?>

								<b>Clinic ID:</b> <input type="text" name="clinicID" id="clinicID" value="<?php echo $row[0]; ?>" readonly> <br/><br/>

								<b>Clinic Name:</b> <input type="text" name="clinicName" id="clinicName" value="<?php echo $row[1]; ?>" readonly> <br/><br/>

								<b>Clinic Address:</b> <input type="text" name="clinicAddress" id="clinicAddress" placeholder="Enter clinic address" title="Enter clinic address" value="<?php echo $row[2]; ?>" required> <br/><br/>

								<b>Clinic Area:</b> <input type="text" name="clinicArea" id="clinicArea" placeholder="Enter clinic area" title="Enter clinic area" value="<?php echo $row[3]; ?>" required> <br/><br>

								<b>Clinic Contact No:</b> <input type="text" name="clinicTelephone" id="clinicTelephone" placeholder="Enter clinic contact no" title="Enter clinic contact no" value="<?php echo $row[4]; ?>" required> <br/><br/>

								<b>Clinic Details:</b> <input type="text" name="clinicDetails" id="clinicDetails" placeholder="Enter clinic details" title="Enter clinic details" value="<?php echo $row[5]; ?>"> <br/><br/><br/>
								<?php
							}
							?>
							<input type="submit" value="UPDATE"> <br/><br/><br/><br/><br/><br/>
						</div>
					</form>
				</table>
			</div>
		</div>
		<label class="nav" for="editOrder">
			<span>
				<img class="icons" src="icons/updateClinic.svg"  />

				<div class="a">Update Clinic</div>
			</span> <!-- THIS IS USED FOR DISPLAYING THE FONT AND TAB NICELY WITH ICONS-->
		</label>

		<!-- editContact TAB -->
		<input name="nav" type="radio" class="editContact-radio" id="editContact" />
		<div class="page editContact-page">
			<div style="position:absolute; top:0px;">
				<table style="margin:0px auto 0px auto;">
					<div class="page-contents" id="bigBox2">
						<form method="post" action="cpUpdateContactAction.php">

							<?php
	// RETRIEVE CP DETAILS OF THIS CP
							$q = "SELECT cpID, contactNo, alternativeContactNo FROM contact_person WHERE cpName = '{$_SESSION['userName']}'";
							$r = @mysqli_query ($dbc, $q);
							if (mysqli_num_rows($r) >= 1) {
								$row = mysqli_fetch_array ($r, MYSQLI_NUM);
								?>

								<b>Contact Person ID:</b> <input type="text" name="cpID" id="cpID" placeholder="Enter Contact Person ID" title="Enter Contact Person ID" value="<?php echo $row[0]; ?>" readonly> <br/><br/>

								<b>Contact No:</b> <input type="text" name="contactNo" id="contactNo" placeholder="Enter Contact No" title="Enter Contact No" value="<?php echo $row[1]; ?>" required> <br/><br/>

								<b>Alternative Contact No:</b> <input type="text" name="alternativeContactNo" id="alternativeContactNo" placeholder="Enter Alternative Contact No" title="Enter Alternative Contact No" value="<?php echo $row[2]; ?>" required> <br/><br/>

								<input type="submit" class="update" value="UPDATE"> <br/><br/><br/><br/><br/><br/>

								<?php
							}
							?>

						</form>
					</div>
				</table>
			</div>
		</div>
		<label class="nav" for="editContact">
			<span>
				<img  class="icons" src="icons/updateContact.svg"/>
				<div class="a">Update Contact</div>
			</span>
		</label>

		<!-- viewOrder TAB -->
		<input name="nav" type="radio" class="viewOrder-radio" id="viewOrder" />
		<div class="page viewOrder-page">
			<div class="outer" id="orderViewDataTable" style="top:0px;"> 

				<?php
				$q = "SELECT * FROM orders JOIN contact_person ON orders.clinicName = contact_person.clinicName WHERE contact_person.cpName = '{$_SESSION['userName']}' ORDER BY orderID ASC";
				$r = @mysqli_query ($dbc, $q);
				if ($r) {
					
					$num = mysqli_num_rows($r);
					if($num > 0) {
						
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
					
					
					while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						echo '<tr><td>' . $row['orderID'] . '</td><td>'
						. $row['itemType'] . '</td><td>' . $row['itemQuantity'] . '</td><td>' 
						. $row['orderDate'] . '</td><td>' . $row['deliveryDate'] . '</td><td>' 
						. $row['orderStatus'] . '</td><td>' . $row['orderDetails'] . '</td></tr>';
					}
					echo '</tbody></table>';
					
						mysqli_free_result ($r);
					}
					else {
						echo '<p style="text-align: center; font-weight:bold;" class="error">There are no records to receive.<br>Please Contact Admin or OIC<br>to Assign Orders to your Clinic.</p>';
						
					}
				}
				?>

			</div>
		</div>
		<label class="nav" for="viewOrder">
			<span>
				<img class="icons" src="icons/viewOrder.svg" />
				<div class="a">View Orders</div>
			</span>
		</label>

		<!-- editOrderStatus -->
		<input name="nav" type="radio" class="editOrderStatus-radio" id="editOrderStatus" />
		<div class="page editOrderStatus-page">
			<div class="page-contents" style="position:absolute; top:0px;">
				<table style="margin:0px auto 0px auto;">
					<form method="post" action="cpUpdateOrderStatusAction.php">
						<div class="page-contents" id="bigBox3">

							<b>Order ID:</b> 
							<input type="text" name="orderID" id="orderID" placeholder="Enter Order ID" title="Enter Order ID" required> 
							<br/><br/>

							<b>Order Status:</b>
							<select name="orderStatus" id="orderStatus" required>
								<option selected="true" value="" disabled="disabled">Select Order Status</option>
								<option value="Delivering">Delivering</option>
							    <option value="Pending">Pending</option>
							    <option value="Received">Received</option>
								
							</select>
							<!--<input type="text" name="orderStatus" id="orderStatus" placeholder="Enter order status" title="Enter order status" required>--> 
							<br/><br/>

							<b>The number of missing items:</b> <input type="number" name="missingQuantity" id="missingQuantity" placeholder="Enter the number of missing items" title="Enter the number of missing items"> <br/><br/>

							<b>Order Details:</b> <input type="text" name="orderDetails" id="orderDetails" placeholder="Enter order details" title="Enter order details" > <br/><br/><br/>

							<input type="submit" class="update" value="UPDATE"> <br/><br/><br/><br/><br/><br/>
						</div>
					</form>
				</table>


			</div>
		</div>
		<label class="nav" for="editOrderStatus">
			<span>
				<img class="icons" src="icons/editOrder.svg" />
				<div class="a">Update Order</div>
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
				<div class="a">LogOut</div>
			</span>
		</label>

	</div>


	<!-- partial -->

	<script type="text/javascript">

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


</script>

</body>
</html>
<?php
mysqli_close($dbc);
?>

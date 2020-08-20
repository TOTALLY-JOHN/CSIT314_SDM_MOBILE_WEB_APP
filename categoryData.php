<?php
session_start();
if(!$_SESSION["userName"]) {
	header("Location:login.php");
}

$con = mysqli_connect('localhost','root','','medisupply') or die('Unable To connect');

$vale = mysqli_real_escape_string($con, trim($_POST['itemCategory']));

$query ="SELECT itemName FROM items WHERE itemCategory LIKE '%$vale%'";
$results = $con->query($query);

?>
<option selected="true" value="" disabled="disabled">Select Item Type</option>
<?php

while($rs=$results->fetch_assoc()) {
	?>
	<option value="<?php echo $rs["itemName"]; ?>"><?php echo $rs["itemName"]; ?></option>
	<?php
}
mysqli_close($con);

?>
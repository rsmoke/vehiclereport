<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

	$adminnotes = $_POST['adminnotes'];
	$mileageReturn  = $_POST['mileageReturn'];
	$program = $_POST['program'];
	$parking = $_POST['parking'];
	$fuelReturn = $_POST['fuelReturn'];


	$adminnotes = htmlentities($adminnotes);

	$id = $_POST['hiddenID'];

  	$sql = "UPDATE transportation_vf SET adminnotes='$adminnotes', mileageReturn='$mileageReturn', parking='$parking', program='$program', fuelReturn='$fuelReturn' WHERE IDvf = '$id'";

	if ($db->query($sql) === true) {
		echo "Record updated successfully";
	}//if
	else {
		die('There was an error running the query [' . $db->error . ']');
	}//else

	$db->close();






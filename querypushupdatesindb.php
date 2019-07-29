<?php
	//include_once("connect.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
	require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');
	
	$mileageReturn = $_POST['mileageReturn'];
	$fuelReturn = $_POST['fuelReturn'];
	$parking = $_POST['parking'];
	$notes = $_POST['notes'];
	$id = $_POST['hiddenID'];
	$mod_on = date('Y-m-d H:i:s');
	
	
	$sql = "UPDATE transportation_vf SET mileageReturn='$mileageReturn', fuelReturn='$fuelReturn', parking='$parking', notes='$notes', mod_on='$mod_on' ";
	$sql = uploadAndProcessImageFile("imagefrontsite", $sql);
	$sql = uploadAndProcessImageFile("imagedriversite", $sql);
	$sql = uploadAndProcessImageFile("imagepassengersite", $sql);
	$sql = uploadAndProcessImageFile("imagebacksite", $sql);
	$sql = uploadAndProcessImageFile("imagedamagesite", $sql);
	
	$sql = uploadAndProcessImageFile("imagefrontend", $sql);
	$sql = uploadAndProcessImageFile("imagedriverend", $sql);
	$sql = uploadAndProcessImageFile("imagepassengerend", $sql);
	$sql = uploadAndProcessImageFile("imagebackend", $sql);
	$sql = uploadAndProcessImageFile("imagedamageend", $sql);
	
	$sql .= " WHERE IDvf = '$id'";

 	
	if ($db->query($sql) === true) {
		echo "Record updated successfully";
	}//if 
	else {
		die('There was an error running the query [' . $db->error . ']');
	}//else
	
	$db->close();
	
	
function uploadAndProcessImageFile($image, $sql) {
	
		$vehiclenum = $_POST['vehiclenum'];
	
		$file_name = $_FILES[$image]['name'];
		$file_size = $_FILES[$image]['size'];
		$file_tmp = $_FILES[$image]['tmp_name'];
		$file_type = $_FILES[$image]['type'];
		
		$todayDate = date(Ymd);
		$temp = explode(".", $_FILES[$image]["name"]);
		$newfilename = $todayDate . $vehiclenum . $uniquename . $image . round(microtime(true)) . '.' . end($temp);
		move_uploaded_file($_FILES[$image]["tmp_name"], "admin/uploads/" . $newfilename);

		
		if ( $file_name != "" && $image == "imagefrontsite") {
			$sql .= ", imagefrontsitefilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagedriversite") {
			$sql .= ", imagedriversitefilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagepassengersite") {
			$sql .= ", imagepassengersitefilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagebacksite") {
			$sql .= ", imagebacksitefilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagedamagesite") {
			$sql .= ", imagedamagesitefilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagefrontend") {
			$sql .= ", imagefrontendfilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagedriverend") {
			$sql .= ", imagedriverendfilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagepassengerend") {
			$sql .= ", imagepassengerendfilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagebackend") {
			$sql .= ", imagebackendfilename='$newfilename' ";
		}//if
		if ( $file_name != "" && $image == "imagedamageend") {
			$sql .= ", imagedamageendfilename='$newfilename' ";
		}//if
		
	return $sql;
}//function uploadAndProcessImageFile

?>
 

  

  

<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basic_lib.php');
?>

<!DOCTYPE html>
<html lang="en">
    <?php include("_head.php"); ?>

  <body role="document">
    <?php include("_navbar.php");?>

    <div class="container" role="main">
        <h1 id="title"><?php echo "$siteTitle";?></h1>
    <style>
        #title {
            font-size:2rem;
            color: #00274c;
            font-weight:400;
            margin-bottom: 1rem;
        }
    </style>


  <?php 
  if ($today == "") {
    	$today = date("m")."/".date("d")."/".date("Y");
  }//today

  $errors = ""; //Clean out errors

	//*****************************
	// Check for required fields
	//***************************
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$thereIsADamageImage = false;

		$uniquename = $_POST['uniquename'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$firstandlastname = $_POST['firstandlastname'];
		$driveruniquename = $_POST['driveruniquename'];
		$driverfirstandlastname = $_POST['driverfirstandlastname'];
		$phone = $_POST['phone'];
		$vehiclenum = $_POST['vehiclenum'];
		$program = $_POST['program'];
		$dateEvent = $_POST['datetimepickerToday'];
		$mileageDepart = $_POST['mileageDepart'];
		$fuelDepart = $_POST['fuelDepart'];
		$notes = $_POST['notes'];
			
		$lastKnownLocation = $_POST['lastKnownLocation2'];
		
		
		if (empty($_POST["uniquename"])) {
			$errors .= "Your uniqname<br>";
		}//if
		if (empty($_POST["driveruniquename"])) {
			$errors .= "Driver's uniqname<br>";
		}//if
		if (empty($_POST["driverfirstandlastname"])) {
			$errors .= "Driver's first and last name<br>";
		}//if
		if (empty($_POST["phone"])) {
			$errors .= "Phone number<br>";
		}//if
			if (empty($_POST["vehiclenum"])) {
			$errors .= "Vehicle Number<br>";
		}//if
		if (empty($_POST["datetimepickerToday"])) {
			$errors .= "Date<br>";
		}//if
		if (empty($_POST["mileageDepart"])) {
			$errors .= "Mileage<br>";
		}//if
		if ($_POST["fuelDepart"] == 0 ) {
			$errors .= "Fuel<br>";
		}//if
		
		 if( $errors != "" ) {
			$errors = "<div class=\"alert alert-danger\" role=\"alert\"><h4>The following fields are required: </h4>".$errors."</div>";
			echo $errors;
		 }//if
		 
		$errorsFilesString = "";
		$errorsFiles = "";
		 
		$errorsFiles = processImageFile("imagefrontstart", $errorsFiles);
		$errorsFiles = processImageFile("imagedriverstart", $errorsFiles);
		$errorsFiles = processImageFile("imagepassengerstart", $errorsFiles);
		$errorsFiles = processImageFile("imagebackstart", $errorsFiles);
	
}//if POST

if ($errorsFiles != "") {
	$errorsFilesString = "<div class=\"alert alert-danger\" role=\"alert\"><h4>The following errors occurred with file upload:</h4>". $errorsFiles . "</div>";
	echo $errorsFilesString;
}//if
else {
	//Upload files if no errors
	if( $errors == "" ) {	
		$imagefrontstartfilename = uploadImageFile("imagefrontstart", $vehiclenum, $uniquename);
		$imagedriverstartfilename = uploadImageFile("imagedriverstart", $vehiclenum, $uniquename);
		$imagepassengerstartfilename = uploadImageFile("imagepassengerstart", $vehiclenum, $uniquename);
		$imagebackstartfilename = uploadImageFile("imagebackstart", $vehiclenum, $uniquename);
		if ( $_FILES['imagedamagestart']['name'] != "" ) {
			$imagedamagestartfilename = uploadImageFile("imagedamagestart", $vehiclenum, $uniquename);
			$thereIsADamageImage = false;
		}//if
		else {
			$thereIsADamageImage = true;
		}
	}//if
}//else

    
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $errors == "" && $errorsFilesString == "")  {
						
			$dateEvent = strtotime($dateEvent);
			$dateEventStamp = date("Y-m-d H:i:s", $dateEvent);
			
// 			$mysqli = new mysqli('localhost', $connectionUserText, $connectionsUserPassword, $db);

// 			if($mysqli ->connect_errno > 0) 
// 			{ 
// 				die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
// 			}//if
			
			if ( $thereIsADamageImage == false ) {
				$sql = "INSERT INTO transportation_vf (uniquename, firstname, lastname, firstandlastname, driveruniquename, driverfirstandlastname, program, dateEvent, mileageDepart, fuelDepart, notes, phone, vehiclenum, imagefrontstartfilename, imagedriverstartfilename, imagepassengerstartfilename, imagebackstartfilename, imagedamagestartfilename) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	
			}//if
			else {
				$sql = "INSERT INTO transportation_vf (uniquename, firstname, lastname, firstandlastname, driveruniquename, driverfirstandlastname, program, dateEvent, mileageDepart, fuelDepart, notes, phone, vehiclenum, imagefrontstartfilename, imagedriverstartfilename, imagepassengerstartfilename, imagebackstartfilename) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	
			}//else
				
// 			date_default_timezone_set("America/New_York");
			//$mysqltime = date ("Y-m-d H:i:s");

			$stmt = $db->stmt_init();
			$stmt->prepare($sql);
			if ( $thereIsADamageImage == false ) {
				$stmt->bind_param('ssssssssssssssssss', $uniquename, $firstname, $lastname, $firstandlastname, $driveruniquename, $driverfirstandlastname, $program, $dateEventStamp, $mileageDepart, $fuelDepart, $notes, $phone, $vehiclenum, $imagefrontstartfilename, $imagedriverstartfilename, $imagepassengerstartfilename, $imagebackstartfilename, $imagedamagestartfilename);
			}//if
			else {
				$stmt->bind_param('sssssssssssssssss', $uniquename, $firstname, $lastname, $firstandlastname, $driveruniquename, $driverfirstandlastname, $program, $dateEventStamp, $mileageDepart, $fuelDepart, $notes, $phone, $vehiclenum, $imagefrontstartfilename, $imagedriverstartfilename, $imagepassengerstartfilename, $imagebackstartfilename);
			}//else
						
			$stmt->execute();
			
			echo $stmt->error;
			
			$stmt->close();
			
			$db->close();
			
			echo "<div class=\"alert alert-success\"><strong>Your information has been submitted.</strong> Upon return, please use the \"Update Form\" link to enter return milage, fuel level, and parking location.<br><br>
			
			You may close this window.</div>";
						
		}//if
 else  {
			$name = ldapGleaner($uniquename);
  ?>
	<form method="post" id="formdirectory" name="formdirectory"  enctype="multipart/form-data">

		<fieldset>

		<div class="form-group row">
			<label class="col-sm-2">Date</label>
			<div class="col-sm-4">
				<input type="text" id="datetimepickerToday" name="datetimepickerToday" value="<?php echo $today ?>">
		   </div>
		</div>
			
		  <div class="form-group row">
			<label for="uniquename" class="col-sm-2 form-control-label">Your uniqname</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="uniquename" name="uniquename" value="<?php echo $uniquename ?>">
			</div>
		  </div>
		  
		<input type="hidden" class="form-control" id="firstname" name="firstname" placeholder="First Name"  value="<?php echo $name[0] ?>">
		<input type="hidden" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $name[1] ?>">

		<div class="form-group row">
			<label for="firstandlastname" class="col-sm-2 form-control-label">Your first and last name</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="firstandlastname" name="firstandlastname" placeholder="Your First and Last Name" value="<?php echo $name[0]." ".$name[1] ?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="checkboxareyoudriver" class="col-sm-2 form-control-label"></label>
			<div class="col-sm-4">
				<input class="form-check-input" type="checkbox" id="checkboxareyoudriver" name="checkboxareyoudriver" <?php if(isset($_POST['checkboxareyoudriver'])) echo "checked='checked'"; ?>>
				<label class="form-check-label" for="checkboxareyoudriver">Check if you are the driver</label>
			</div>
		</div>
		
		 <div class="form-group row">
			<label for="driveruniquename" class="col-sm-2 form-control-label">Driver's uniqname</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="driveruniquename" name="driveruniquename" value="<?php echo $driveruniquename ?>">
			</div>
		  </div>
		  
		<div class="form-group row">
			<label for="driverfirstandlastname" class="col-sm-2 form-control-label">Driver's first and last name</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="driverfirstandlastname" name="driverfirstandlastname" value="<?php echo $driverfirstandlastname ?>">
			</div>
		</div>
		
		
		<div class="form-group row">
			<label for="lastname" class="col-sm-2 form-control-label">Phone</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="phone" name="phone" placeholder="(734) 555-5555" value="<?php echo $phone ?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="vehiclenumber" class="col-sm-2 form-control-label">Vehicle Number</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="vehiclenum" name="vehiclenum" placeholder="Type in vehicle number to locate vehicle" value="<?php echo $vehiclenum ?>" onkeyup="findVehicle(this.value)">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="Location" class="col-sm-2 form-control-label">Location</label>
			<div class="col-sm-4">
				<div id="lastKnownLocation"></div>
			</div>
		</div>
		
		
		  <div class="form-group row">
			<label class="col-sm-2">Course Number / Program</label>
			<div class="col-sm-4">
			   <textarea class="form-control" rows="4" id="program" name="program" placeholder="Course Number / Program"></textarea>
			  </div>
		  </div>

		<div class="form-group row">
			<label for="mileageDepart" class="col-sm-2 form-control-label">Mileage (Depart)</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="mileageDepart" name="mileageDepart"  min="0" placeholder="Mileage (Depart)" value="<?php echo $mileageDepart ?>">
			</div>
		  </div>
		  
		  <!--fuel depart-->
		  <div class="form-group row">
		  <label for="fuel-gauge-controlDepart" class="col-sm-2 form-control-label" >Fuel (Depart)</label>
			  <div class="col-md-5">
				   <div class="col-md-offset-3"><div id="fuel-gaugeDepart" name="fuelGaugeDepart"></div></div>
				  <br><br>
				  <div id="fuel-gauge-controlDepart"></div>
			  </div>
			  <input type="hidden" name="fuelDepart" id="fuelDepart" value="">
		</div>
		
		<br><br>
		
		<div class="form-group row">
			<label for="imagescar" class="col-sm-2 form-control-label">Upload car images</label>
			<div class="col-sm-10">
				<label for="imagefrontstart" class="col-sm-2 form-control-label">Front</label><input type="file" id="imagefrontstart" name="imagefrontstart" class="btn btn-info" >
				<label for="imagedriverstart" class="col-sm-2 form-control-label">Driver's Side</label><input type="file" id="imagedriverstart" name="imagedriverstart" class="btn btn-info">
				<label for="imagepassengerstart" class="col-sm-2 form-control-label">Passenger's Side</label><input type="file" id="imagepassengerstart" name="imagepassengerstart" class="btn btn-info">
				<label for="imagebackstart" class="col-sm-2 form-control-label">Back</label><input type="file" id="imagebackstart" name="imagebackstart" class="btn btn-info">
				<label for="imagebackstart" class="col-sm-2 form-control-label">Damage</label><input type="file" id="imagedamagestart" name="imagedamagestart" class="btn btn-info">
			</div>
		  </div>

		 <div class="form-group row">
			<label for="notes" class="col-sm-2 form-control-label">Notes (optional)</label>
			<div class="col-sm-5">
			  <textarea class="form-control" rows="4" id="notes" name="notes" placeholder="Notes"></textarea>
			</div>
		  </div>
		  	  
		 <div class="col-md-7">
			<div class="alert alert-danger text-center" role="alert" ><h4>If you see any new damage to the vehicle, please notify us at <a href="mailto:<?php echo "$addressEmail";?>"><?php echo "$addressEmail";?></a>.</h4></div>
	  </div>
		
		 <div class="col-md-12 col-md-offset-3">
			<input type="submit" value="Submit" id="submitbutton" class="btn btn-primary">
		</div>
				
		</fieldset>
 </form>
 <br>
	
				
			<br><br>
			
<?php }//else ?>
    </div> <!-- /container -->
	
<?php


function processImageFile($image, $errorsFiles) {

	$vehiclenum = $_POST['vehiclenum'];
	$uniquename = $_POST['uniquename'];

	$file_name = $_FILES[$image]['name'];
    $file_size = $_FILES[$image]['size'];
    $file_tmp = $_FILES[$image]['tmp_name'];
    $file_type = $_FILES[$image]['type'];
	
	if ($_FILES[$image]["name"] == "") { 

			switch($image) {
				case "imagefrontstart":
					$errorsFiles .= "Picture of front of the vehicle is required.<br>";
				break;
				case "imagedriverstart":
					$errorsFiles .= "Picture of driver's side of the vehicle is required.<br>";
				break;
				case "imagepassengerstart":
					$errorsFiles .= "Picture of passenger's side of the vehicle is required.<br>";
				break;
				case "imagebackstart":
					$errorsFiles .= "Picture of back of the vehicle is required.<br>";
				break;
				
		}//switch
	}//if
	 
	if ( $file_size > 20971520 ) {
        $errorsFiles .= "File size must be under 20 MB.<br>";
    }//if
	
return $errorsFiles;
}//function processImageFile


function uploadImageFile($image, $vehiclenum, $uniquename) {

		$todayDate = date(Ymd);
		$temp = explode(".", $_FILES[$image]["name"]);
		$newfilename = $todayDate . $vehiclenum . $uniquename . $image . round(microtime(true)) . '.' . end($temp);
		move_uploaded_file($_FILES[$image]["tmp_name"], "admin/uploads/" . $newfilename);

	return $newfilename;
}//function uploadImageFile

?>
	
    <?php include("_footer.php"); ?>

<script>
$(window).load(function() {
   var vehicleToFindOnLoad = document.getElementById('vehiclenum').value; 
   findVehicle(vehicleToFindOnLoad);
});


function findVehicle(vehicleNum) {

	console.log(vehicleNum);

	$.ajax({
	url: 'findVehicleLocation.php',
    type: "GET",
	data: {
		vehicleNum: vehicleNum
	},
	success: function(response) {
		document.getElementById('lastKnownLocation').innerHTML=response; 
	},
	error: function(xhr) {
    console.log("error");
  }
});

}

</script>


<script>
var $myFuelGaugeDepart;

$( function () {
  $myFuelGaugeDepart = $("div#fuel-gaugeDepart").dynameter({
    width: 200,
    label: 'fuel %',
    value: 0,
    min: 0.0,
    max: 100.0,
    unit: '%',
    regions: { // Value-keys and color-refs
      0: 'error',
      51: 'normal'
    }
  });
  

  // jQuery UI slider widget
  $('div#fuel-gauge-controlDepart').slider({
	width: 400,
    min: 0.0,
    max: 100.0,
    value: 0,
    step: 12.5,
    slide: function (evt, ui) {
      $myFuelGaugeDepart.changeValue((ui.value).toFixed(1));
	  $("#fuelDepart").val(ui.value);
    }
  });

});
</script>

<script>

  $("#checkboxareyoudriver").click(function() {
     // If change is confirmed this checks if the checkbox is checked
        if ($(this).prop("checked")) {
			$("#driveruniquename").val($("#uniquename").val());
			$("#driverfirstandlastname").val($("#firstandlastname").val());
    }
  });

</script>


  </body>
</html>


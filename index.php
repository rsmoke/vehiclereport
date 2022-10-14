<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');
require_once('library/HTMLPurifier.auto.php');
$purifier = new HTMLPurifier();

?>

<!DOCTYPE html>
<html lang="en">
<script type="text/javascript" src="js/dist/purify.min.js"></script>

	<script>
		function validate_uniqname(str, name_id) {
                        var str = DOMPurify.sanitize(str);
                        var div_id = name_id + "_error";
                    if (name_id == "driverfirstandlastname") {
			document.getElementById('checkboxareyoudriver').checked = false;
                    }
		    if (str == "") {
		        return;
		    } else {
		        if (window.XMLHttpRequest) {
		            // code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp = new XMLHttpRequest();
		        } else {
		            // code for IE6, IE5
		            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        xmlhttp.onreadystatechange = function() {
		            if (this.readyState == 4 && this.status == 200) {
		              if (this.responseText == "FALSE") {
		              		document.getElementById(name_id).value = "";
		                   if (!$('#'+div_id).hasClass('uniqname_error_error')) {
			                   $('#'+div_id).addClass('uniqname_error_error');
			                   $('#'+div_id).css("font-weight", "bold");
			                   $('#'+div_id).prepend('<span style="color:red;margin-left:5px;">uniqname is not valid</span>');
		              			}
		           		} else {
		                 $('#'+div_id).removeClass('uniqname_error_error');
		                 $('#'+div_id).empty();
		                 document.getElementById(name_id).value = this.responseText;
		              }
		            }
		        };
		        xmlhttp.open("GET","ldapGleaner2.php?uniqname="+str,true);

		        xmlhttp.send();
		    }
		}
	</script>

    <?php include("_head.php"); ?>

  <body role="document">
    <?php include("_navbar.php");?>

    <div class="container" role="main">
    	<h2 id="title"><?php echo "$siteTitle";?></h2>
	<?php
	$today = "";
  if ($today == "") {
    	$today = date("m")."/".date("d")."/".date("Y");
  }//today

	$errors = ""; //Clean out errors

	//*****************************
	// Check for required fields
	//***************************
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$thereIsADamageImage = false;

		$uniquename = $purifier->purify($_POST['uniquename']);
		$firstname = $purifier->purify($_POST['firstname']);
		$lastname = $purifier->purify($_POST['lastname']);
		$firstandlastname = $purifier->purify($_POST['firstandlastname']);
		$driveruniquename = $purifier->purify($_POST['driveruniquename']);
		$driverfirstandlastname = $purifier->purify($_POST['driverfirstandlastname']);
		$driveruniquename2 = $purifier->purify($_POST['driveruniquename2']);
		$driverfirstandlastname2 = $purifier->purify($_POST['driverfirstandlastname2']);
		$phone = $purifier->purify($_POST['phone']);
		$vehiclenum = $purifier->purify($_POST['vehiclenum']);
		$program = $purifier->purify($_POST['program']);
		$dateEvent = $purifier->purify($_POST['datetimepickerToday']);
		$mileageDepart = $purifier->purify($_POST['mileageDepart']);
		$fuelDepart = $purifier->purify($_POST['fuelDepart']);
		$notes = $purifier->purify($_POST['notes']);

		// $lastKnownLocation = $_POST['lastKnownLocation'];


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

	// }//if POST
		if ($errorsFiles != "") {
			$errorsFilesString = "<div class=\"alert alert-danger\" role=\"alert\"><h4>The following errors occurred with file upload:</h4>". $errorsFiles . "</div>";
			echo $errorsFilesString;
		} else {
			//Upload files if no errors
			if( ($errors == "") && (isset($vehiclenum))) {
				$imagefrontstartfilename = uploadImageFile("imagefrontstart", $vehiclenum, $uniquename);
				$imagedriverstartfilename = uploadImageFile("imagedriverstart", $vehiclenum, $uniquename);
				$imagepassengerstartfilename = uploadImageFile("imagepassengerstart", $vehiclenum, $uniquename);
				$imagebackstartfilename = uploadImageFile("imagebackstart", $vehiclenum, $uniquename);
				if ( $_FILES['imagedamagestart']['name'] != "" ) {
					$imagedamagestartfilename = uploadImageFile("imagedamagestart", $vehiclenum, $uniquename);
					$thereIsADamageImage = false;
				} else {
					$thereIsADamageImage = true;
				}
			}//if
		}//else
	}


  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $errors == "" && $errorsFilesString == "")  {

			$dateEvent = strtotime($dateEvent);
			$dateEventStamp = date("Y-m-d H:i:s", $dateEvent);

			if ( $thereIsADamageImage == false ) {
				$sql = "INSERT INTO transportation_vf (uniquename, firstname, lastname, firstandlastname, driveruniquename, driverfirstandlastname, driveruniquename2, driverfirstandlastname2, program, dateEvent, mileageDepart, fuelDepart, notes, phone, vehiclenum, imagefrontstartfilename, imagedriverstartfilename, imagepassengerstartfilename, imagebackstartfilename, imagedamagestartfilename) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			} else {
				$sql = "INSERT INTO transportation_vf (uniquename, firstname, lastname, firstandlastname, driveruniquename, driverfirstandlastname, driveruniquename2, driverfirstandlastname2, program, dateEvent, mileageDepart, fuelDepart, notes, phone, vehiclenum, imagefrontstartfilename, imagedriverstartfilename, imagepassengerstartfilename, imagebackstartfilename) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			}//else

			$stmt = $db->stmt_init();
			$stmt->prepare($sql);
			if ( $thereIsADamageImage == false ) {
				$stmt->bind_param('ssssssssssssssssssss', $uniquename, $firstname, $lastname, $firstandlastname, $driveruniquename, $driverfirstandlastname, $driveruniquename2, $driverfirstandlastname2, $program, $dateEventStamp, $mileageDepart, $fuelDepart, $notes, $phone, $vehiclenum, $imagefrontstartfilename, $imagedriverstartfilename, $imagepassengerstartfilename, $imagebackstartfilename, $imagedamagestartfilename);
			} else {
				$stmt->bind_param('sssssssssssssssssss', $uniquename, $firstname, $lastname, $firstandlastname, $driveruniquename, $driverfirstandlastname, $driveruniquename2, $driverfirstandlastname2, $program, $dateEventStamp, $mileageDepart, $fuelDepart, $notes, $phone, $vehiclenum, $imagefrontstartfilename, $imagedriverstartfilename, $imagepassengerstartfilename, $imagebackstartfilename);
			}//else
			$stmt->execute();

			echo $stmt->error;

			$stmt->close();

			$db->close();

			echo "<div class=\"alert alert-warning\">Thank you for submitting vehicle departure information.<strong>Your form is in progress.</strong>
			Upon return to campus, please go to the \"Update Form\" page to upload images and record parking structure and floor number to complete the form.</div>";
		} else  {
			$name = ldapGleaner($uniquename);
  ?>
	<form method="post" id="formdirectory" name="formdirectory"  enctype="multipart/form-data">

		<fieldset>
<div><p> <strong><font color="blue">If you are providing updated information on a current reservation, such as updating on-site images, return images, and/or parking information,
 please click into the menu on the top right corner of your screen, then select "Update Form."</font> <font color="red">DO NOT use the form on this screen for providing
  updated information on a current reservation.</font> <font color="blue">This form is for new reservation departure information ONLY.</font></strong></p>
</div>
			<div class="form-group row">
				<label for= "datetimepickerToday">Date</label>
				<input type="text" class="form-control" id="datetimepickerToday" name="datetimepickerToday" value="<?php echo $today ?>">
			</div>

			<div class="form-group row">
				<label for="uniquename">Your uniqname</label>
				<input type="text" class="form-control" id="uniquename" name="uniquename" value="<?php echo (isset($uniquename))? $uniquename : "";?>">
			</div>

			<input type="hidden" class="form-control" id="firstname" name="firstname" placeholder="First Name"  value="<?php echo $name[0] ?>">
			<input type="hidden" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $name[1] ?>">

			<div class="form-group row">
				<label for="firstandlastname">Your first and last name</label>
				<input type="text" class="form-control" id="firstandlastname" name="firstandlastname" placeholder="Your First and Last Name" value="<?php echo $name[0]." ".$name[1] ?>">
			</div>

			<div class="form-group form-check row">
				<input class="form-check-input" type="checkbox" id="checkboxareyoudriver" name="checkboxareyoudriver" <?php if(isset($_POST['checkboxareyoudriver'])) echo "checked='checked'"; ?>>
				<label class="form-check-label" for="checkboxareyoudriver">Check if you are the driver</label>
			</div>

			<div id='driverfirstandlastname_error'></div>
			<div class="form-group row">
				<label for="driveruniquename">Driver's uniqname</label>
				<input onchange="validate_uniqname(this.value, 'driverfirstandlastname')" type="text" class="form-control" id="driveruniquename" name="driveruniquename" value="<?php echo (isset($driveruniquename))? $driveruniquename : "";?>">
			</div>

			<div class="form-group row">
				<label for="driverfirstandlastname">Driver's first and last name</label>
				<input type="text" class="form-control" id="driverfirstandlastname" name="driverfirstandlastname" value="<?php echo (isset($driverfirstandlastname))? $driverfirstandlastname : "";?>">
			</div>

			<div id='driverfirstandlastname2_error'></div>
			<div class="form-group row">
				<label for="driveruniquename2">Second Driver's uniqname</label>
				<input onchange="validate_uniqname(this.value, 'driverfirstandlastname2')" type="text" class="form-control" id="driveruniquename2" name="driveruniquename2" value="<?php echo (isset($driveruniquename2))? $driveruniquename2 : "";?>">
			</div>

			<div class="form-group row">
				<label for="driverfirstandlastname2">Second Driver's first and last name</label>
				<input type="text" class="form-control" id="driverfirstandlastname2" name="driverfirstandlastname2" value="<?php echo (isset($driverfirstandlastname2))? $driverfirstandlastname2 : "";?>">
			</div>

			<div class="form-group row">
				<label for="lastname" >Phone</label>
				<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="<?php echo (isset($phone))? $phone : "";?>">
			</div>

			<div class="form-group row">
				<label for="vehiclenumber">Vehicle Number</label>
				<input type="text" class="form-control" id="vehiclenum" name="vehiclenum" placeholder="Type in vehicle number to locate vehicle" value="<?php echo (isset($vehiclenum))? $vehiclenum : ""; ?>" onkeyup="findVehicle(this.value)">
			</div>

			<div class="form-group row">
				<label for="Location">Location</label>
				<div id="lastKnownLocation"></div>
			</div>

			<div class="form-group row">
				<label>Course Number / Program</label>
				<textarea class="form-control" rows="4" id="program" name="program" placeholder="Course Number / Program"><?php echo (isset($program))? $program : ""; ?></textarea>
			</div>

			<div class="form-group row">
				<label for="mileageDepart">Mileage (Depart)</label>
				<input type="text" class="form-control" id="mileageDepart" name="mileageDepart"  min="0" placeholder="000.00" value="<?php echo (isset($mileageDepart))? $mileageDepart : ""; ?>" pattern="\d*\.?\d*" title="Enter a decimal number: 000.00">
			</div>

			<!--fuel depart-->
			<div class="form-group row">
				<label for="fuel-gauge-controlDepart">Fuel (Depart)<br>tap bar to move</label>
					<div class="ml-3 col-5">
						<div id="fuel-gaugeDepart" name="fuelGaugeDepart"></div>
						<br>
						<div id="fuel-gauge-controlDepart"></div>
					</div>
				<input type="hidden" name="fuelDepart" id="fuelDepart" value="<?php echo (isset($fuelDepart))? $fuelDepart : ""; ?>">
			</div>

			<br><br>
			<h5 class="bg-light">Upload car images</h5>
			<div class="form-group row justify-content-center">
				<div class="col-10">
					<div class="input-group mt-3">
						<div class="custom-file">
							<input type="file" id="imagefrontstart" name="imagefrontstart" class="custom-file-input">
							<label for="imagefrontstart" class="custom-file-label">Front</label>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row justify-content-center">
				<div class="col-10">
					<div class="input-group mt-3">
						<div class="custom-file">
							<input type="file" id="imagedriverstart" name="imagedriverstart" class="custom-file-input">
							<label for="imagedriverstart" class="custom-file-label">Driver's Side</label>
							</div>
					</div>
				</div>
			</div>
			<div class="form-group row justify-content-center">
				<div class="col-10">
					<div class="input-group mt-3">
						<div class="custom-file">
							<input type="file" id="imagepassengerstart" name="imagepassengerstart" class="custom-file-input">
							<label for="imagepassengerstart" class="custom-file-label">Passenger's Side</label>
							</div>
					</div>
				</div>
			</div>
			<div class="form-group row justify-content-center">
				<div class="col-10">
					<div class="input-group mt-3">
						<div class="custom-file">
							<input type="file" id="imagebackstart" name="imagebackstart" class="custom-file-input">
							<label for="imagebackstart" class="custom-file-label">Back</label>
							</div>
					</div>
				</div>
			</div>
			<div class="form-group row justify-content-center">
				<div class="col-10">
					<div class="input-group mt-3">
						<div class="custom-file">
							<input type="file" id="imagedamagestart" name="imagedamagestart" class="custom-file-input">
							<label for="imagedamagestart" class="custom-file-label">Damage</label>
							</div>
					</div>
				</div>
			</div>
			<hr>


			<div class="form-group row">
				<label for="notes" >Notes (optional)</label>
				<textarea class="form-control" rows="4" id="notes" name="notes" placeholder="Notes"><?php echo (isset($notes))? $notes : ""; ?></textarea>
			</div>

			<div class="form-group row justify-content-center">
				<div class="col-md-7">
					<div class="alert alert-danger text-center" role="alert" >
						<h4>If you see any new damage to the vehicle, please notify us at <a href="mailto:<?php echo "$addressEmail";?>"><?php echo "$addressEmail";?></a>.</h4>
					</div>
				</div>
			</div>

			<div class="form-group row justify-content-center">
				<input type="submit" value="Submit" id="submitbutton" class="btn btn-primary">
			</div>

		</fieldset>
 	</form>

			<br><br>

<?php }//else ?>
    </div> <!-- /container -->

<?php


function processImageFile($image, $errorsFiles) {
	$purifier = new HTMLPurifier();
	$vehiclenum = $purifier->purify($_POST['vehiclenum']);
	$uniquename = $purifier->purify($_POST['uniquename']);

	$file_name = $_FILES[$image]['name'];
	$file_size = $_FILES[$image]['size'];
	$file_tmp = $_FILES[$image]['tmp_name'];
	$file_type = $_FILES[$image]['type'];

	if (($file_name == "") || ($file_size < 1)) {

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

		$todayDate = date('Ymd');
		$temp = explode(".", $_FILES[$image]["name"]);
		$newfilename = $todayDate . $vehiclenum . $uniquename . $image . round(microtime(true)) . '.' . end($temp);
		$newsizefilename = "admin/uploads/" . $newfilename;
   // resize image before uploading 
                $sourcefile = $_FILES[$image]["tmp_name"];
                $max_width = 800;
                $max_height = 800;
                $type = $_FILES[$image]['type'];
                ResizeImageFile($sourcefile, $max_width, $max_height, $newsizefilename, $type);
//		move_uploaded_file($_FILES[$image]["tmp_name"], "admin/uploads/" . $newfilename);
// 		rename($endfile, "admin/uploads/" . $newfilename);

	return $newfilename;
}//function uploadImageFile

function ResizeImageFile($sourcefile, $max_width, $max_height, $endfile, $type) {
   // Load image and get image size.
   switch($type){
        case'image/png':
                $img = imagecreatefrompng($sourcefile);
                break;
                case'image/jpeg':
                $img = imagecreatefromjpeg($sourcefile);
                break;
                case'image/gif':
                $img = imagecreatefromgif($sourcefile);
                break;
                default :
                return 'Un supported format';
    }
    $img= ImageFixOrientation($img, $sourcefile);
    $width = imagesx($img);
    $height = imagesy($img);
  // count new width and height
    if ($width > $height) {
        if ($width < $max_width) {
                $newwidth = $width;
        }
        else {
            $newwidth = $max_width;
        }
        $divisor = $width / $newwidth;
        $newheight = floor( $height / $divisor);
   }
   else {
         if ($height < $max_height) {
             $newheight = $height;
         }
         else {
             $newheight =  $max_height;
         }
         $divisor = $height / $newheight;
         $newwidth = floor( $width / $divisor );
   }
   // Create a new temporary image.
   $tmpimg = imagecreatetruecolor( $newwidth, $newheight );
    imagealphablending($tmpimg, false);
    imagesavealpha($tmpimg, true);
    // Copy and resize old image into new image.
    imagecopyresampled($tmpimg, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    //compressing the file
    switch($type){
        case'image/png':
                imagepng($tmpimg, $endfile, 0);
                break;
        case'image/jpeg':
                imagejpeg($tmpimg, $endfile, 100);
                break;
        case'image/gif':
                imagegif($tmpimg, $endfile, 0);
                break;
     }
   // release the memory
   imagedestroy($tmpimg);
   imagedestroy($img);
   return $endfile;
} // function ResizeImageFile
function ImageFixOrientation($image, $filename) {
    $exif = exif_read_data($filename);
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            default:
                return $image;
        }
        return $image;
    }
    return $image;
} //ImageFixOrientation
?>

    <?php include("_footer.php"); ?>

<script>
// $(window).load(function() {
$(document).ready(function() {
   var vehicleToFindOnLoad = document.getElementById('vehiclenum').value;
   findVehicle(vehicleToFindOnLoad);
});


function findVehicle(vehicleNum) {

//	console.log(vehicleNum);

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
 var fuel = "<?php if (isset($_POST['fuelDepart'])) {echo $purifier->purify($_POST['fuelDepart']);} else { echo 'hell';} ?>";
  if (fuel == "hell"){
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
console.log(ui.value);
      $myFuelGaugeDepart.changeValue((ui.value).toFixed(1));
	  $("#fuelDepart").val(ui.value);
    }
  });
  }
  else {
  $myFuelGaugeDepart = $("div#fuel-gaugeDepart").dynameter({
    width: 200,
    label: 'fuel %',
    value: fuel,
    min: 0.0,
    max: 100.0,
    unit: '%',
    regions: { // Value-keys and color-refs
      0: 'error',
      51: 'normal'
    }
  });


   $('div#fuel-gauge-controlDepart').slider({
        width: 400,
    min: 0.0,
    max: 100.0,
    value: fuel,
    step: 12.5,
    slide: function (evt, ui) {
console.log(ui.value);
      $myFuelGaugeDepart.changeValue((ui.value).toFixed(1));
          $("#fuelDepart").val(ui.value);
    }
  });

  }

});
</script>

<script>

  $("#checkboxareyoudriver").click(function() {
     // If change is confirmed this checks if the checkbox is checked
        if ($(this).prop("checked")) {
        	$("#driverfirstandlastname_error").html('');
			$("#driveruniquename").val($("#uniquename").val());
			$("#driverfirstandlastname").val($("#firstandlastname").val());
    }
  });

</script>


  </body>
</html>


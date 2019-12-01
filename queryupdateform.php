<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');
require_once('library/HTMLPurifier.auto.php');
$purifier = new HTMLPurifier();
?>
<!DOCTYPE html>
<html lang="en">
     <?php include("_head.php"); 
           $not_auth = "";
      ?>
<script type="text/javascript" src="js/dist/purify.min.js"></script>
        <script>
                function validate_uniqname(str, name_id) {
                        var str = DOMPurify.sanitize(str);
                        var div_id = name_id + "_error";
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

  <body role="document">

    <?php include("_navbar.php"); ?>

    <div class="container" role="main">

        <h1>Updating Vehicle</h1>


<?php
	$id = trim($_GET['id']);

  if ($id != "") {
   if ($isAdmin) {
    $sql = "SELECT * from transportation_vf WHERE IDvf = '$id'";
  }
  else {
    $sql = "SELECT * from transportation_vf WHERE IDvf = '$id' AND (uniquename = '$uniquename' OR driveruniquename = '$uniquename' OR driveruniquename2 = '$uniquename')";
  }
?>



<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$phone = $purifier->purify($_POST['phone']);
	$driveruniquename2 = $purifier->purify($_POST['driveruniquename2']);
	$driverfirstandlastname2 = $purifier->purify($_POST['driverfirstandlastname2']);
	$mileageReturn = $purifier->purify($_POST['mileageReturn']);
	$fuelReturn = $purifier->purify($_POST['fuelReturn']);
	$parking = $purifier->purify($_POST['parking']);
	$notes = $purifier->purify($_POST['notes']);
	$adminnotes = $purifier->purify($_POST['adminnotes']);
	$id = $purifier->purify($_POST['hiddenID']);
	$mod_on = date('Y-m-d H:i:s');

	$sql = "UPDATE transportation_vf SET phone='$phone', driverfirstandlastname2='$driverfirstandlastname2', driveruniquename2='$driveruniquename2', mileageReturn='$mileageReturn', fuelReturn='$fuelReturn', parking='$parking', notes='$notes', adminnotes='$adminnotes', mod_on='$mod_on'";

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

	//Uncomment for troubleshooting
	if ($db->query($sql) === true) {
		//echo "Record updated successfully";
	}//if
	else {
		//die('There was an error running the query [' . $mysqli->error . ']');
	}//else

	$db->close();


	echo "<div class=\"alert alert-success\"><strong>Vehicle information has been updated. </strong><br><br>


	You may close this window or go back to the <a href=\"updatevf.php\">update form page</a>.</div>";
}//if
else {
	?>

  <form method="post" id="formEdit" name="formEdit" enctype="multipart/form-data">
    <?php

 	if(!$result = $db->query($sql))
	{
		die('There was an error running the query [' . $db->error . ']');
	}//if

	$queryResult = array();
     if ($result->num_rows < 1) {
      $not_auth = "Not Authorized";
      echo "<h2>" . $not_auth . "</h2>";
     }
     else {   
	while($row = $result->fetch_assoc())
	{
		$queryResult[] = $row;
	}//while

	foreach ($queryResult as $value)
	{
	?>

	<fieldset>

		<div class="form-group row">
			<label class="col-sm-2">Vehicle</label>
			<div class="col-sm-4">
				<?php echo  $value["vehiclenum"]; ?>
		   </div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2">Driver</label>
			<div class="col-sm-4">
				<?php echo $value["driverfirstandlastname"]." (". $value["driveruniquename"]. ")"; ?>
		   </div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2">Date</label>
			<div class="col-sm-4">
				<?php echo  $value["dateEvent"]; ?>
		   </div>
		</div>

		 <div class="form-group row">
			<label for="phone">Phone</label>
			<input type="text" class="form-control" id="phone" name="phone"  min="0" placeholder="xxx-xxx-xxxx" value="<?php echo $value["phone"]; ?>">

		 </div>

                 <div id='driverfirstandlastname2_error'></div>
		 <div class="form-group row">
			<label for="phone">Second Driver's uniqname</label>
			<input onchange="validate_uniqname(this.value, 'driverfirstandlastname2')" type="text" class="form-control" id="driveruniquename2" name="driveruniquename2"  min="0" value="<?php echo $value["driveruniquename2"]; ?>">

		 </div>

		 <div class="form-group row">
			<label for="phone">Second Driver's Name</label>
			<input type="text" class="form-control" id="driverfirstandlastname2" name="driverfirstandlastname2"  min="0" value="<?php echo $value["driverfirstandlastname2"]; ?>">

		 </div>

		 <div class="form-group row">
			<label for="mileageDepart">Mileage (Return)</label>
			<input type="text" class="form-control" id="mileageReturn" name="mileageReturn"  min="0" placeholder="Mileage (Return)" value="<?php echo $value["mileageReturn"]; ?>">

		 </div>

		 <div class="form-group row">
		  <label for="fuel-gauge-controlReturn">Fuel (Return)</label>
			  <div class="ml-3 col-5">
				  <div id="fuel-gaugeReturn" name="fuelGaugeReturn"></div>
					<br>
					<div id="fuel-gauge-controlReturn"></div>
			  </div>
			<input type="hidden" name="fuelReturn" id="fuelReturn" value="<?php echo $value["fuelReturn"]; ?>">
		</div>

		<div id="fuelIsTooLow" class="alert alert-danger" role="alert"><h4>You need to go to Transportation Services (1213 Kipke Dr, Ann Arbor, MI 48109) to refuel this vehicle immediately.</h4></div>


		 <div class="form-group row">
			<label for="mileageDepart">Parking structure & Floor Number</label>
			<textarea class="form-control" rows="3" id="parking" name="parking" placeholder="Parking Location"><?php echo $value["parking"]; ?></textarea>
		 </div>

		<br><br>
		<h5 class="bg-light">Please upload car images below.</h5>
<!--
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
           <table class="table table-striped table-bordered">
                        <tr>
                                <td>Parking Garage (start)</td>
                        <td>

                                <?php if  ( $value["imagefrontstartfilename"] != "" ) { ?>
                                <a href="admin/uploads\<?php echo $value["imagefrontstartfilename"] ?>" target="_blank">
                                <img src="admin/processimage.php?image=<?php echo $value["imagefrontstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>


                                <?php if  ( $value["imagedriverstartfilename"] != "" ) { ?>
                                <a href="admin/uploads\<?php echo $value["imagedriverstartfilename"] ?>" target="_blank">
                                <img src="admin/processimage.php?image=<?php echo $value["imagedriverstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

                                <?php if  ( $value["imagepassengerstartfilename"] != "" ) { ?>
<a href="admin/uploads\<?php echo $value["imagepassengerstartfilename"] ?>" target="_blank">
                                <img src="admin/processimage.php?image=<?php echo $value["imagepassengerstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

                                <?php if  ( $value["imagebackstartfilename"] != "" ) { ?>
                                <a href="admin/uploads\<?php echo $value["imagebackstartfilename"] ?>" target="_blank">
                                <img src="admin/processimage.php?image=<?php echo $value["imagebackstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

                                <?php if  ( $value["imagedamagestartfilename"] != "" ) { ?>
                                <a href="admin/uploads\<?php echo $value["imagedamagestartfilename"] ?>" target="_blank">
                                <img src="admin/processimage.php?image=<?php echo $value["imagedamagestartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

                </td>
</tr>
<tr>
                                <td>At Site</td>
                                <td>
					<?php
						if ( $value["imagefrontsitefilename"] == "" ) {

							echo '<div class="custom-file">';
							echo '<input type="file" id="imagefrontsite" name="imagefrontsite" class="custom-file-input" >';
							echo '<label for="imagefrontsite" class="custom-file-label">Front (site)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Front (site)<br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagefrontsitefilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagefrontsitefilename"] .'" style="width:100px;height:100px;hspace:10;"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
				<?php
						if ( $value["imagedriversitefilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagedriversite" name="imagedriversite" class="custom-file-input" >';
							echo '<label for="imagedriversite" class="custom-file-label">Driver (site)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Driver (site)<br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagedriversitefilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagedriversitefilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
					<?php
						if ( $value["imagepassengersitefilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagepassengersite" name="imagepassengersite" class="custom-file-input" >';
							echo '<label for="imagepassengersite" class="custom-file-label">Passenger (site)</label>';
         						echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Passenger (site)<br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagepassengersitefilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagepassengersitefilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
					<?php
						if ( $value["imagebacksitefilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagebacksite" name="imagebacksite" class="custom-file-input" >';
							echo '<label for="imagebacksite" class="custom-file-label">Back (site)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Back (site)<br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagebacksitefilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagebacksitefilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
					<?php
						if ( $value["imagedamagesitefilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagedamagesite" name="imagedamagesite" class="custom-file-input" >';
							echo '<label for="imagedamagesite" class="custom-file-label">Damage (site)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Damage (site)<br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagedamagesitefilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagedamagesitefilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
-->

                                </td>
                        </tr>
		<hr>
<!--
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
                        <tr>
                                <td>Parking Garage (return)</td>

<td>
					<?php
						if ( $value["imagefrontendfilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagefrontend" name="imagefrontend" class="custom-file-input" >';
							echo '<label for="imagefrontend" class="custom-file-label">Front(returned)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Front <br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagefrontendfilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagefrontendfilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
					<?php
						if ( $value["imagedriverendfilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagedriverend" name="imagedriverend" class="custom-file-input" >';
							echo '<label for="imagedriverend" class="custom-file-label">Driver (returned)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Driver <br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagedriverendfilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagedriverendfilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
					<?php
						if ( $value["imagepassengerendfilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagepassengerend" name="imagepassengerend" class="custom-file-input" >';
							echo '<label for="imagepassengerend" class="custom-file-label">Passenger (returned)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Passenger <br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagepassengerendfilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagepassengerendfilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
					<?php
						if ( $value["imagebackendfilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagebackend" name="imagebackend" class="custom-file-input" >';
							echo '<label for="imagebackend" class="custom-file-label">Back (returned)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Back <br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagebackendfilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagebackendfilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>

<!--
				</div>
			</div>
		</div>
		<div class="form-group row justify-content-center">
			<div class="col-10">
				<div class="input-group mt-3">
-->
					<?php
						if ( $value["imagedamageendfilename"] == "" ) {
							echo '<div class="custom-file">';
							echo '<input type="file" id="imagedamageend" name="imagedamageend" class="custom-file-input" >';
							echo '<label for="imagedamageend" class="custom-file-label">Damage (returned)</label>';
							echo '</div>';
						} else {
echo '<div style="float:left;">';
							echo 'Damage <br> ';
                                                        echo '<a href="admin/uploads/'. $value["imagedamageendfilename"] .'" target="_blank">';
                                                        echo '<img src="admin/processimage.php?image=' . $value["imagedamageendfilename"] .'" width="100" height ="100" hspace="5"></a>';
echo "</div>";
						}
					?>
<!--
				</div>
			</div>
		</div>
-->
                                </td>
                        </tr>
</table>

		<hr>


			<div class="form-group row">
				<label for="notes">Notes</label>
				<textarea class="form-control" rows="4" id="notes" name="notes" placeholder="Notes"><?php echo $value["notes"]; ?></textarea>
			</div>
<?php
if ($isAdmin) {
?>
			<div class="form-group row">
				<label for="adminnotes">Admin Notes</label>
				<textarea class="form-control" rows="4" id="adminnotes" name="adminnotes" placeholder="Admin Notes"><?php echo $value["adminnotes"]; ?></textarea>
			</div>
<?php
}
?>
			<div class="form-group row justify-content-center">
				<div class="col-7">
					<div class="alert alert-danger text-center" role="alert" ><h4>If you see any new damage to the vehicle, please notify us at <a href="mailto:<?php echo "$addressEmail";?>"><?php echo "$addressEmail";?></a>.</h4></div>
				</div>
			</div>

			<input type="hidden" name="hiddenID" id="hiddenID" value="<?php echo $value["IDvf"]; ?>" />
			<input type="hidden" name="vehiclenum" id="vehiclenum" value="<?php echo $value["vehiclenum"]; ?>" />

			<div class="form-group row justify-content-center">
				<input type="submit" value="Submit" id="submitbutton" class="btn btn-primary">
			</div>

	   	</fieldset>


	   <?php
	}//foreach
      } // empty  result
	$result->free();

  ?>

  </form>
<?php
}//else for POST

  }
?>



<?php
function uploadAndProcessImageFile($image, $sql) {
		global $uniquename;
	  $purifier = new HTMLPurifier();
		$vehiclenum = $purifier->purify($_POST['vehiclenum']);

		$file_name = $_FILES[$image]['name'];
		$file_size = $_FILES[$image]['size'];
		$file_tmp = $_FILES[$image]['tmp_name'];
		$file_type = $_FILES[$image]['type'];

                $max_width = 800;
                $max_height = 800;

		$todayDate = date('Ymd');
		$temp = explode(".", $_FILES[$image]["name"]);
		$newfilename = $todayDate . $vehiclenum . $uniquename . $image . round(microtime(true)) . '.' . end($temp);
                $newsizefilename = "admin/uploads/" . $newfilename;
                ResizeImageFile($file_tmp, $max_width, $max_height, $newsizefilename, $file_type);

//		move_uploaded_file($_FILES[$image]["tmp_name"], "admin/uploads/" . $newfilename);

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
                $img = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            default:
                return $image;
        }
        return $img;
    }
    return $image;
} //ImageFixOrientation
?>

<?php include("_footer.php"); ?>
<script>

$(function() {

	 $("#fuelIsTooLow").hide();
});


var $myFuelGaugeDepart;
var $getFuelValue;

$getFuelValue = $("#fuelReturn").val();

if ($getFuelValue == "" ) {
	$getFuelValue = 0;
}



$( function () {
  $myFuelGaugeReturn = $("div#fuel-gaugeReturn").dynameter({
    width: 200,
    label: 'fuel %',
    value: $getFuelValue,
    min: 0.0,
    max: 100.0,
    unit: '%',
    regions: { // Value-keys and color-refs
      0: 'error',
      50: 'normal'
    }
  });

  // jQuery UI slider widget
  $('div#fuel-gauge-controlReturn').slider({
	width: 400,
    min: 0.0,
    max: 100.0,
    value: $getFuelValue,
    step: 12.5,
    slide: function (evt, ui) {

      $myFuelGaugeReturn.changeValue((ui.value).toFixed(1));
	  $("#fuelReturn").val(ui.value);

    },
	stop: function (evt, ui) {

	var $fuelAmount =  parseFloat($("#fuelReturn").val());
	   if ( $fuelAmount <=  50) {
		$("#fuelIsTooLow").show();
	   }//if

	    if ( $fuelAmount >  50) {
		$("#fuelIsTooLow").hide();
	   }//if
	}
  });

});
</script>

</body>
</html>

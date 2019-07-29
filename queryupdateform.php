<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basic_lib.php');
?>
<!DOCTYPE html>
<html lang="en">
     <?php include("_head.php"); ?>

  <body role="document">
  
    <?php include("_navbar.php"); ?>
	  
    <div class="container theme-showcase" role="main">

        <h1>Updating</h1>


<?php
	$id = trim($_GET['id']);

  if ($id != "") {
    $sql = "SELECT * from transportation_vf WHERE IDvf = \"$id\"";
?>



<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//include "querypushupdatesindb.php";
	
	//require_once($_SERVER["DOCUMENT_ROOT"]."/../support/connect_tran_vf.php");
	
	//global $db;
	//$db = new mysqli('localhost', $connectionUserText, $connectionsUserPassword, $db);

	/*if($db->connect_errno > 0) 
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}//if
	
	*/
	
	$mileageReturn = $_POST['mileageReturn'];
	$fuelReturn = $_POST['fuelReturn'];
	$parking = $_POST['parking'];
	$notes = $_POST['notes'];
	$id = $_POST['hiddenID'];
	$mod_on = date('Y-m-d H:i:s');
	
	$sql = "UPDATE transportation_vf SET mileageReturn='$mileageReturn', fuelReturn='$fuelReturn', parking='$parking', notes='$notes', mod_on='$mod_on'";
	
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
	
	$sql .= " WHERE IDvf = \"$id\"";
	
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
			<label class="col-sm-2">Date</label>
			<div class="col-sm-4">
				<?php echo  $value["dateEvent"]; ?>
		   </div>
		</div>
		
		 <div class="form-group row">
			<label for="mileageDepart" class="col-sm-2 form-control-label">Mileage (Return)</label>
			<div class="col-sm-4">
			  <input type="text" class="form-control" id="mileageReturn" name="mileageReturn"  min="0" placeholder="Mileage (Return)" value="<?php echo $value["mileageReturn"]; ?>">
			</div>
		 </div>
		  
		 <div class="form-group row">
		  <label for="fuel-gauge-controlReturn" class="col-sm-2 form-control-label" >Fuel (Return)</label>
			  <div class="col-md-5">
				   <div class="col-md-offset-3"><div id="fuel-gaugeReturn" name="fuelGaugeReturn"></div></div>
					<br><br>
					<div id="fuel-gauge-controlReturn"></div>
			  </div>
			  <input type="hidden" name="fuelReturn" id="fuelReturn" value="<?php echo $value["fuelReturn"]; ?>">
		</div>
				
		<div id="fuelIsTooLow" class="alert alert-danger" role="alert"><h4>You need to go to Transportation Services (1213 Kipke Dr, Ann Arbor, MI 48109) to refuel this vehicle immediately.</h4></div>
				
				
		 <div class="form-group row">
			<label for="mileageDepart" class="col-sm-2 form-control-label">Parking Location</label>
			<div class="col-sm-4">
			  <textarea class="form-control" rows="3" id="parking" name="parking" placeholder="Parking Location"><?php echo $value["parking"]; ?></textarea>
			</div>
		 </div>
		 
		 <div class="form-group row">
			<label for="mileageDepart" class="col-sm-4 form-control-label"><strong>Please upload car images below.</strong></label>
		</div>
	
	
		<div class="col-sm-10">
				<div class="form-group row">
				<label for="imagefrontsite" class="col-sm-2 form-control-label">Front (site)</label>	
					<?php 
					if ( $value["imagefrontsitefilename"] == "" ) { ?>
						<input type="file" id="imagefrontsite" name="imagefrontsite" class="btn btn-info" >
					<?php }//if
					else { ?>
						<span class="label label-success">Image uploaded</span>
					<?php 
					}// else ?>
				
				</div>
				
				<div class="form-group row">
				<label for="imagedriversite" class="col-sm-2 form-control-label">Driver's (site)</label>	
					<?php 
					if ( $value["imagedriversitefilename"] == "" ) { ?>
						<input type="file" id="imagedriversite" name="imagedriversite" class="btn btn-info" >
					<?php }//if
					else { ?>
						<span class="label label-success">Image uploaded</span>
					<?php 
					}// else ?>
				</div>
				
				<div class="form-group row">
				<label for="imagepassengersite" class="col-sm-2 form-control-label">Passenger's (site)</label>	
					<?php 
					if ( $value["imagepassengersitefilename"] == "" ) { ?>
						<input type="file" id="imagepassengersite" name="imagepassengersite" class="btn btn-info" >
					<?php }//if
					else { ?>
						<span class="label label-success">Image uploaded</span>
					<?php 
					}// else ?>
				</div>
				
				<div class="form-group row">
				<label for="imagebacksite" class="col-sm-2 form-control-label">Back (site)</label>	
					<?php 
					if ( $value["imagebacksitefilename"] == "" ) { ?>
						<input type="file" id="imagebacksite" name="imagebacksite" class="btn btn-info" >
					<?php }//if
					else { ?>
						<span class="label label-success">Image uploaded</span>
					<?php 
					}// else ?>
				</div>
				
				<div class="form-group row">
				<label for="imagebackdamage" class="col-sm-2 form-control-label">Damage (site)</label>	
					<?php 
					if ( $value["imagedamagesitefilename"] == "" ) { ?>
						<input type="file" id="imagedamagesite" name="imagedamagesite" class="btn btn-info" >
					<?php }//if
					else { ?>
						<span class="label label-success">Image uploaded</span>
					<?php 
					}// else ?>
				</div>
				
				<div class="form-group row">
				<label for="imagefrontend" class="col-sm-2 form-control-label">Front (returned)</label>	
				<?php 
					if ( $value["imagefrontendfilename"] == "" ) { ?>
						<input type="file" id="imagefrontend" name="imagefrontend" class="btn btn-info" >
					<?php }//if
					else { ?>
						<span class="label label-success">Image uploaded</span>
					<?php 
					}// else ?>
				</div>
				
				<div class="form-group row">
				<label for="imagedriversend" class="col-sm-2 form-control-label">Driver's (returned)</label>	
								<?php 
					if ( $value["imagedriverendfilename"] == "" ) { ?>
						<input type="file" id="imagedriverend" name="imagedriverend" class="btn btn-info" >
					<?php }//if
					else { ?>
						<span class="label label-success">Image uploaded</span>
					<?php 
					}// else ?>
				</div>
				
				<div class="form-group row">
					<label for="imagepassengerend" class="col-sm-2 form-control-label">Passenger's (returned)</label>	
						<?php 
						if ( $value["imagepassengerendfilename"] == "" ) { ?>
							<input type="file" id="imagepassengerend" name="imagepassengerend" class="btn btn-info" >
						<?php }//if
						else { ?>
							<span class="label label-success">Image uploaded</span>
						<?php 
						}// else ?>
				</div>
				
				<div class="form-group row">
					<label for="imagebackend" class="col-sm-2 form-control-label">Back (returned)</label>	
						<?php 
						if ( $value["imagebackendfilename"] == "" ) { ?>
							<input type="file" id="imagebackend" name="imagebackend" class="btn btn-info" >
						<?php }//if
						else { ?>
							<span class="label label-success">Image uploaded</span>
						<?php 
						}// else ?>
				</div>
				
				<div class="form-group row">
					<label for="imagedamageend" class="col-sm-2 form-control-label">Damage (returned)</label>	
						<?php 
						if ( $value["imagedamageendfilename"] == "" ) { ?>
							<input type="file" id="imagedamageend" name="imagedamageend" class="btn btn-info" >
						<?php }//if
						else { ?>
							<span class="label label-success">Image uploaded</span>
						<?php 
						}// else ?>
				</div>
	
		
		<div class="form-group row">
			<label for="notes" class="col-sm-2 form-control-label">Notes</label>
			<div class="col-sm-5">
			  <textarea class="form-control" rows="4" id="notes" name="notes" placeholder="Notes"><?php echo $value["notes"]; ?></textarea> 
			</div>
		 </div>
		 
		 <div class="col-md-7">
			<div class="alert alert-danger text-center" role="alert" ><h4>If you see any new damage to the vehicle, please notify us at <a href="mailto:<?php echo "$addressEmail";?>"><?php echo "$addressEmail";?></a>.</h4></div>
	  </div>
	  

		 
		 
	</div>
		
			<input type="hidden" name="hiddenID" id="hiddenID" value="<?php echo $value["IDvf"]; ?>" />
			<input type="hidden" name="vehiclenum" id="vehiclenum" value="<?php echo $value["vehiclenum"]; ?>" />
			
		<div class="col-md-12 col-md-offset-3">
			<input type="submit" value="Submit" id="submitbutton" class="btn btn-primary">
		</div>
	   
	   	</fieldset>
	    
	   
	   <?php
	}//foreach

	$result->free();
	
  ?>
  
  </form>
<?php
}//else for POST

  }    
?>



<?php
function uploadAndProcessImageFile($image, $sql) {
	
		$vehiclenum = $_POST['vehiclenum'];
		//$uniquename = $_SERVER['REMOTE_USER'];
		
		$uniquename = $_SERVER['REDIRECT_REMOTE_USER'];
	
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

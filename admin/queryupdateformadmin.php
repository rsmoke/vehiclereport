<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

	$id = trim($_GET['id']);

  if ($id != "") {
    $sql = "SELECT * from transportation_vf WHERE IDvf = \"$id\"";

    include("../_head.php");
?>

 <form id="formEdit">
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
	   <table class="table table-striped table-bordered">
			<tr>
				<td class="text-right">Date</td>
				<td> <?php echo $value["dateEvent"]; ?></td>
			</tr>

			<tr>
				<td class="text-right">Name </td>
				<td> <?php echo $value["firstname"]." ".$value["lastname"]. " (".$value["uniquename"].")"; ?></td>
			</tr>

			<tr>
				<td class="text-right">Created</td>
				<td> <?php echo $value["created_on"]; ?></td>
			</tr>

			<tr>
				<td class="text-right">Mileage (Return) </td>
				<td><input type="text" class="form-control" id="mileageReturn" name="mileageReturn"  min="0" placeholder="Mileage (Return)" value="<?php echo $value["mileageReturn"]; ?>"></td>
			</tr>
			<tr>
				<td class="text-right">Course # </td>
				<td><input type="text" class="form-control" id="program" name="program" placeholder="Course #" value="<?php echo $value["program"]; ?>"></td>
			</tr>


			<tr>
			  <td colspan=2>
			  <label for="fuel-gauge-controlReturn">Fuel (Return)</label>
				  <div class="ml-3 col-5">
					  <div id="fuel-gaugeReturn" name="fuelGaugeReturn"></div>
						<br>
						<div id="fuel-gauge-controlReturn"></div>
				  </div>
				<input type="hidden" name="fuelReturn" id="fuelReturn" value="<?php echo $value["fuelReturn"]; ?>">
				</td>
			</tr>

			<tr>
			  <td class="text-right">Parking</td>
			  <td><input type="text" class="form-control" id="parking" name="parking"  value="<?php echo $value["parking"]; ?>"></td>


			</tr>
			<tr>
				<td class="text-right">Notes</td>
				<td> <?php echo $value["notes"]; ?></td>
			</tr>

			<tr>
				<td class="text-right">Admin Notes</td>
				<td> <textarea class="form-control" rows="4" id="adminnotes" name="adminnotes" placeholder="Admin Notes"><?php echo $value["adminnotes"]; ?></textarea> </td>
			</tr>

			<tr>
				<td colspan="2">Click on an image to open in new window.</td>
			</tr>

			<tr>
				<td>Parking Garage (start)</td>
			<td>

				<?php if  ( $value["imagefrontstartfilename"] != "" ) { ?>
				<a href="uploads\<?php echo $value["imagefrontstartfilename"] ?>" target="_blank">
				<img src="processimage.php?image=<?php echo $value["imagefrontstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>


				<?php if  ( $value["imagedriverstartfilename"] != "" ) { ?>
				<a href="uploads\<?php echo $value["imagedriverstartfilename"] ?>" target="_blank">
				<img src="processimage.php?image=<?php echo $value["imagedriverstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

				<?php if  ( $value["imagepassengerstartfilename"] != "" ) { ?>
				<a href="uploads\<?php echo $value["imagepassengerstartfilename"] ?>" target="_blank">
				<img src="processimage.php?image=<?php echo $value["imagepassengerstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

				<?php if  ( $value["imagebackstartfilename"] != "" ) { ?>
				<a href="uploads\<?php echo $value["imagebackstartfilename"] ?>" target="_blank">
				<img src="processimage.php?image=<?php echo $value["imagebackstartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

				<?php if  ( $value["imagedamagestartfilename"] != "" ) { ?>
				<a href="uploads\<?php echo $value["imagedamagestartfilename"] ?>" target="_blank">
				<img src="processimage.php?image=<?php echo $value["imagedamagestartfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

		</td>
		</tr>

			<tr>
				<td>At Site</td>
				<td>
					<?php if  ( $value["imagefrontsitefilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagefrontsitefilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagefrontsitefilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagedriversitefilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagedriversitefilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagedriversitefilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagepassengersitefilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagepassengersitefilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagepassengersitefilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagebacksitefilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagebacksitefilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagebacksitefilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagedamagesitefilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagedamagesitefilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagedamagesitefilename"] ?>" width="100" height ="100"></a><?php }//if ?>
				</td>
			</tr>

			<tr>
				<td>Parking Garage (return)</td>
				<td>
					<?php if  ( $value["imagefrontendfilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagefrontendfilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagefrontendfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagedriverendfilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagedriverendfilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagedriverendfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagepassengerendfilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagepassengerendfilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagepassengerendfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagebackendfilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagebackendfilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagebackendfilename"] ?>" width="100" height ="100"></a><?php }//if ?>

					<?php if  ( $value["imagedamageendfilename"] != "" ) { ?>
					<a href="uploads\<?php echo $value["imagedamageendfilename"] ?>" target="_blank">
					<img src="processimage.php?image=<?php echo $value["imagedamageendfilename"] ?>" width="100" height ="100"></a><?php }//if ?>
				</td>
			</tr>


			<input type="hidden" name="hiddenID" id="hiddenID" value="<?php echo $value["IDvf"]; ?>" />



	   </table>
	   <?php
	}//foreach

	$result->free();
	$db->close();

  ?>

  </form>
<?php
  }//foreach

?>

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


<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');
?>
<!DOCTYPE html>
<html lang="en">
    <?php include("_head.php"); ?>

  <body role="document">

    <?php include("_navbar.php"); ?>
	<div class="container" role="main">
        <h2 id="title">Update <?php echo "$siteTitle";?></h2>
	
	<div id="popup"></div>

	<div id="tabledata">
	<?php
		
//		$sql = "SELECT * FROM transportation_vf WHERE uniquename = '$uniquename' ORDER BY dateEvent DESC, IDvf DESC";	
           if ($isAdmin) {
		$sql = "SELECT * FROM transportation_vf ORDER BY dateEvent DESC, IDvf DESC";	
           }
           else {
		$sql = "SELECT * FROM transportation_vf WHERE uniquename = '$uniquename' OR driveruniquename = '$uniquename' OR driveruniquename2 = '$uniquename' ORDER BY dateEvent DESC, IDvf DESC";	
           }
		
		if(!$result = $db->query($sql))
		{
			die('There was an error running the query [' . $db->error . ']');
		}//if

		$queryResult = array();

		while($row = $result->fetch_assoc())
		{
			$queryResult[] = $row;
		}//while
		?>
		
		<table class="table table-striped table-bordered">
          <thead>
              <tr>
				<th></th>
				<th>Vehicle #</th>
				<th>Status</th>
				<th>Date</th>
				<th>ID</th>
              </tr>
            </thead>
            <tbody>	
		<?php

		foreach ($queryResult as $value)
		{
		
		?>
			<tr>
			
				<td><a class="updateForm" href='queryupdateform.php?id=<?php echo $value["IDvf"]; ?>'>Update</a> </td>
				
				<td> <?php echo $value["vehiclenum"]; ?> </td>
				
				<td>
					<?php
					if (($value["imagefrontsitefilename"] == "" ) || ($value["imagedriversitefilename"] == "") || ($value["imagepassengersitefilename"] == "") || ($value["imagebacksitefilename"] == "")) { ?>
						<span class="label label-warning">Site Not Completed</span>
					<?php	
					}//if
					else { ?>
						<span class="label label-success">Site Completed</span>
					<?php			
					}//else
							
					if (($value["mileageReturn"] == 0) || ($value["fuelReturn"] == 0) || ($value["parking"] == "") || ($value["imagefrontendfilename"] == "") 
					|| ($value["imagedriverendfilename"] == "") || ($value["imagepassengerendfilename"] == "") || ($value["imagebackendfilename"] == "")) { ?>
					<span class="label label-warning">Return Not Completed</span>
					<?php	
					}//if
					else { ?>
						<span class="label label-success">Return Completed</span>
					<?php			
					}//else
					?>
				</td>
				
				<td> <?php echo $value["dateEvent"]; ?> </td>
				
				<td> <?php echo $value['IDvf']; ?> </td>
			</tr>
	
		  <?php
		}//foreach

		$result->free();
		$db->close(); ?>
		
		</tbody>	
		</table>

		</div><!--table data-->
    </div> <!-- /container -->
	

<?php include("_footer.php"); ?>


  </body>
</html>


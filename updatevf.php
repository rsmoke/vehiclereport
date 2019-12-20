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
		
                <table class="table table-sm table-responsive table-striped table-bordered">

          <thead>
              <tr>
                                <th scope="col">Edit</th>
                                <th scope="col">Vehicle #</th>
                                <th scope="col">Student Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Course #</th>
                                <th scope="col">Mileage Driven</th>
                                <th scope="col">Mileage (Depart)</th>
                                <th scope="col">Fuel (Depart)</th>
                                <th scope="col">Mileage (Return)</th>
                                <th scope="col">Fuel (Return)</th>
                                <th scope="col">Parking</th>
                                <th scope="col">Admin Status</th>
                                <th scope="col">ID</th>

              </tr>
            </thead>
            <tbody>	
		<?php

		foreach ($queryResult as $value)
		{
		
		?>
			<tr>
			
				<td><a class="updateForm" href='queryupdateform.php?id=<?php echo $value["IDvf"]; ?>'>Update</a><br> 
<?php
if ($isAdmin) {
?>
				<a class="deleteForm" href='querydeleteform.php?id=<?php echo $value["IDvf"]; ?>' onclick="return confirm('Are you sure you want to delete this item?');">Delete</a> </td>
<?php
}
?>
				
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
<td> <?php echo $value["firstname"]." ".$value["lastname"]; ?> </td>
 <td> <?php echo $value["program"]; ?> </td>

                                <td>
                                <?php
                                        $mileageDriven = ((isset($value["mileageReturn"]))? $value["mileageReturn"] : $value["mileageDepart"]) - $value["mileageDepart"];
                                        echo $mileageDriven;
                                ?></td>

                                <td> <?php echo $value["mileageDepart"]; ?> </td>

                                <td> <?php echo $value["fuelDepart"]; ?> </td>

                                <td> <?php echo $value["mileageReturn"]; ?> </td>

                                <td> <?php echo $value["fuelReturn"]; ?> </td>

                                <td> <?php echo $value["parking"]; ?> </td>
<td>
                                <?php
                                        if (($value["adminnotes"] == "")) { ?>
                                                <span class="label label-warning">Not Completed</span>
                                        <?php
                                        }//if
                                        else { ?>
                                                <span class="label label-success">Completed</span>
                                        <?php
                                        }//else
                                        ?>
                                </td>


				
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


<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

if ($isAdmin){
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../_head.php"); ?>


  <body role="document">

<?php include("../_navbar.php");?>
	
    <div class="container theme-showcase" role="main">
        <h2>Admin Update/Review CEAL Vehicle Report</h2>

	
	<div id="popup"></div>

	<div id="tabledata">
	<?php
		
		$sql = "SELECT * FROM transportation_vf ORDER BY dateEvent DESC, IDvf DESC LIMIT 500";	
		
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
				<th scope="col">Date</th>
				<th scope="col">Name</th>
				<th scope="col">Admin Status</th>
				<th scope="col">Vehicle #</th>
				<th scope="col">Mileage Driven</th>
				<th scope="col">Mileage (Depart)</th>
				<th scope="col">Fuel (Depart)</th>
				<th scope="col">Mileage (Return)</th>
				<th scope="col">Fuel (Return)</th>
				<th scope="col">Parking</th>
				<th scope="col">Student Status</th>
				<th scope="col">ID</th>
              </tr>
            </thead>
            <tbody>	
		<?php

			foreach ($queryResult as $value)
		{
		
		?>
			<tr>
			
				<td><a class="updateFormAdmin" href='#' data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-sm fa-edit text-primary"></i></a> <a class="deleteForm" href='#' data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-sm fa-trash text-danger"></i></a></td>
				
				<td> <?php echo $value["dateEvent"]; ?> </td>
				
				<td> <?php echo $value["firstname"]." ".$value["lastname"]; ?> </td>
				
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
				
				<td> <?php echo $value["vehiclenum"]; ?> </td>
				
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
					if (($value["mileageReturn"] == 0) || ($value["fuelReturn"] == 0) || ($value["parking"] == "")) { ?>
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
	

<?php include("../_footer.php"); ?>
<script type="text/javascript" src="js/editformadmin.js"></script>


 </body>
</html>
<?php
}else{
	redirect_to();
}
?>

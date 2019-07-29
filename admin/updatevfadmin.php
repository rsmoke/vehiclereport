<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basic_lib.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../_head.php"); ?>


  <body role="document">

<?php include("../_navbar.php");?>
	
    <div class="container theme-showcase" role="main">
        <h1>Admin Update/Review CEAL Vehicle Report</h1>

	
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
		
		<table class="table table-striped table-bordered">
          <thead>
              <tr>
				<th></th>
				<th>Date</th>
				<th>Name</th>
				<th>Admin Status</th>
				<th>Vehicle #</th>
				<th>Mileage Driven</th>
				<th>Mileage (Depart)</th>
				<th>Fuel (Depart)</th>
				<th>Mileage (Return)</th>
				<th>Fuel (Return)</th>
				<th>Parking</th>
				<th>Student Status</th>
				<th>ID</th>
              </tr>
            </thead>
            <tbody>	
		<?php

			foreach ($queryResult as $value)
		{
		
		?>
			<tr>
			
				<td><a class="updateFormAdmin" href='#'>Update</a> | <a class="deleteForm" href='#'>Delete</a> </td>
				
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
					$mileageDriven = $value["mileageReturn"] - $value["mileageDepart"];	
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


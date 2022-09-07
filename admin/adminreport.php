<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

if ($isAdmin){
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../_head.php"); ?>

<?php

$termStartDate = "";
$termEndDate = "";

$previousCar = "";
$mileageTotal = "";
?>

  <body role="document">
<?php include("../_navbar.php");?>
	
    <div class="container theme-showcase" role="main">
      <h2>Admin Report
				<small class="text-muted">
					<a href='csvdownload.php' role='button'data-toggle="tooltip" data-placement="top" title="Download as CSV">
						<i class="fa fa-sm fa-download text-success"></i>
					</a>
				</small>
			</h2>


<?php	
		$termToUse = (isset($_POST['term']))? $_POST['term'] : 2260;
			
		//$sqlTerm = "SELECT * FROM term ORDER BY term ASC LIMIT 10";
		$sqlTerm = "SELECT * FROM term ORDER BY term DESC";
		
		if(!$resultTerm = $db->query($sqlTerm))
		{
			die('There was an error running the query [' . $db->error . ']');
		}//if
		
		$queryResultTerm = array();
		
		while($rowTerm = $resultTerm->fetch_assoc())
		{
			$queryResultTerm[] = $rowTerm;
		}//while

		?>
		
		<h4>Select a specific term below to view those records</h4>
		<label for="term">Select a Term:</label><form method="post" id="formterms" name="formterms"><select name="term">
			<?php foreach ($queryResultTerm as $valueTerm )
				{
				?>
					<option value="<?php echo $valueTerm["term"]; ?>" <?php if ($termToUse == $valueTerm["term"]) { echo " selected "; } ?> > <?php echo trim($valueTerm["term_descr"]);?></option>

				<?php
				}//foreach ?>
			</select>
			<input type="hidden" name="datadownload" value="false">
			<input type="submit" name="Submit" value="Submit">
		</form>
		
<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
?>
<br>
<div id="tabledata">
		<table class="table table-striped table-bordered">
          <thead>
              <tr>
				<th>Account</th>
				<th>Vehicle #</th>
				<th>Day 1</th>
				<th>Total/Term</th>
				<th>DI Miles</th>
				<th>PO Miles</th>
				<th>DI %</th>
				<th>PO %</th>
              </tr>
            </thead>
            <tbody>	

<?php



// ******************
// Show data
// ******************

		$sqlGetDates = "SELECT * FROM term WHERE term = '$termToUse'";	
		
		if(!$resultGetDates = $db->query($sqlGetDates))
		{
			die('There was an error running the query [' . $db->error . ']');
		}//if
		
		$queryResultGetDates = array();
		
		while($rowGetDates = $resultGetDates->fetch_assoc())
		{
			$queryResultGetDates[] = $rowGetDates;
		}//while
		
		foreach ($queryResultGetDates as $value) {
			$termStartDate = $value["term_begin_dt"];
			$termEndDate = $value["term_end_dt"];
		}//foreach
	
		$sql = "SELECT vehiclenum, COUNT(vehiclenum) as totalCars FROM transportation_vf WHERE (dateEvent >= '$termStartDate' AND dateEvent <= '$termEndDate') GROUP BY vehiclenum ORDER BY vehiclenum DESC";

		if(!$result = $db->query($sql))
		{
			die('There was an error running the query [' . $db->error . ']');
		}//if

		$listOfCars = array();

		while($row = $result->fetch_assoc())
		{
			$listOfCars[] = $row["vehiclenum"];		
		}//while


		foreach ($listOfCars as $value)
		{
			$sql = "SELECT * FROM transportation_vf WHERE vehiclenum = '$value'";
			
			if(!$result = $db->query($sql))
			{
				die('There was an error running the query [' . $db->error . ']');
			}//if
			
			unset($queryResultCar); //clean out array
			
			while($rowCar = $result->fetch_assoc())
			{
				$queryResultCar[] = $rowCar;
			}//while
			
			/**************************/
			// Indiv. Car Information
			/*************************/
			
			$firstDay = $termStartDate;
			$TotalMilesTerm = 0;
			$milesDriven = 0;
			$DIMiles = 0;
			$POMiles = 0;
			$DIPercent = 0;
			$POPercent = 0;
			$DICarCount = 0;
			$POCarCount = 0;
			$account = "";
			
			foreach ($queryResultCar as $value)
			{
				$milesDriven = 0; //reset values
				
				if ( $value["mileageReturn"] != "" ) {
					$milesDriven = $value["mileageReturn"] - $value["mileageDepart"];
				}//if
				
				$TotalMilesTerm = $TotalMilesTerm + $milesDriven;
				
				if ( ($value["program"] == "Detroit Initiative") && ($value["mileageReturn"] != "") ) {
					$DIMiles = $DIMiles + $milesDriven;
					$DICarCount++;
				}//if
				
				if ( ($value["program"] == "Project Outreach") && ($value["mileageReturn"] != "") ) {
					$POMiles = $POMiles + $milesDriven;
					$POCarCount++;
				}//if
			}//foreach - indiv. cars.
			
			
			if ( $DICarCount != 0 && $TotalMilesTerm != 0 ) {
				$DIPercent = ( $DIMiles / $TotalMilesTerm) * 100;
				$DIPercent = round($DIPercent)."%";
			}//if
			
			if ( $POCarCount != 0 && $TotalMilesTerm != 0 ) {
				$POPercent = ( $POMiles / $TotalMilesTerm) * 100;
				$POPercent = round($POPercent)."%";
			}//if
			
			if ( $DIMiles > $POMiles ) {
				$account = "Detroit Initiative";
			}//if
			else if ( $DIMiles < $POMiles ) {
				$account = "Project Outreach";
			}//else if
			else {
				$account = "Project Outreach / Detroit Initiative";
			}//else
			
			?>
			<tr>
				<td><?php echo $account; ?></td>
				<td> <?php echo $value["vehiclenum"]; ?> </td>
				<td> <?php echo $termStartDate; ?> </td>
				<td> <?php echo $TotalMilesTerm; ?> </td>
				<td> <?php echo $DIMiles ?> </td>
				<td> <?php echo $POMiles ?> </td>
				<td> <?php echo $DIPercent ?> </td>
				<td> <?php echo $POPercent ?> </td>
			</tr>


		<?php
		}//foreach

		$result->free();
		$db->close(); ?>
		
		</tbody>	
		</table>

		</div><!--table data-->
		
<?php } ?>

    </div> <!-- /container -->
	

<?php include("../_footer.php"); ?>

 </body>
</html>
<?php
}else{
	redirect_to();
}
?>

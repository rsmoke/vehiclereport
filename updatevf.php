<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css/index.css" type="text/css">

    <?php include("_head.php"); ?>

  <body role="document">

    <?php include("_navbar.php"); ?>
	<div class="container" role="main">
        <h2 id="title">Update <?php echo "$siteTitle";?></h2>
	
	<div id="popup"></div>

	<div id="tabledata">
	<?php
	$limit = 20;
	$sql = 'SELECT * FROM transportation_vf';
	if ($result=mysqli_query($db,$sql)) {
  		$total=mysqli_num_rows($result);
	  }
	  else {
		die('There was an error running the query ');
	  }
// How many pages will there be
if ($total == 0) {
	echo ("There are no records to display");
	exit;
}
else {
	$totalpages = ceil($total / $limit);
}
// get the current offset or set a default
if (isset($_REQUEST['start']) && is_numeric($_REQUEST['start'])) {
	$pageStart = (int) $_REQUEST['start'];
 } else {
	$pageStart = 0;
 }
 // current page
 $page = ceil($pageStart / $limit) +1;

 $prevPage = ($pageStart - $limit < 0) ? 0 : $pageStart - $limit;
 $nextPage = ($pageStart + $limit > $total) ? $pageStart: $pageStart + $limit;
 $nextRowLast = (floor(($total / $limit)) * $limit);


// Some information to display to the user
$start = $pageStart + 1;
$end = min(($pageStart + $limit), $total);

echo "<div class='floatleft'>";
echo '<form name="formp" method="post" action="updatevf.php">';
echo "<input type='hidden' name='start' value='0'>"  ;
    echo "<input type='submit' name='Submit' value='First'>";
echo '</form></div>';
echo "<div class='floatleft'>";
echo '<form name="formp" method="post" action="updatevf.php">';
echo "&nbsp;<input type='hidden' name='start' value='" . $prevPage . "'>"  ;
if ($prevPage == 0) {
    echo "<input type='submit' name='Submit' value='Prev' disabled>";
}
else {
    echo "<input type='submit' name='Submit' value='Prev'>";
}
echo '</form></div>';
echo '<div class="floatleft">';
echo '<form name="formn" method="post" action="updatevf.php">';
echo "&nbsp;<input type='hidden' name='start' value='" . $nextPage . "'>"  ;
if ($page == $totalpages) {
	echo "<input type='submit' name='Submit' value='Next' disabled>";
}
else {
	echo "<input type='submit' name='Submit' value='Next'>";
}
echo '</form></div>';
echo '<div class="floatleft">';
echo '<form name="formp" method="post" action="updatevf.php">';
echo "&nbsp;<input type='hidden' name='start' value='" . $nextRowLast . "'>"  ;
    echo "<input type='submit' name='Submit' value='Last'>";
echo '</form></div>';
// Display the paging information
echo '&nbsp;&nbsp;', $prevlink, ' Page ', $page, ' of ', $totalpages, ' pages, displaying ', $start, '-', $end, ' of ',
$total, ' results ', $nextlink;
echo '<br><br></div>';
		
//		$sql = "SELECT * FROM transportation_vf WHERE uniquename = '$uniquename' ORDER BY dateEvent DESC, IDvf DESC";	
           if ($isAdmin) {
		$sql = "SELECT * FROM transportation_vf ORDER BY dateEvent DESC, IDvf DESC";	
           }
           else {
		$sql = "SELECT * FROM transportation_vf WHERE uniquename = '$uniquename' OR driveruniquename = '$uniquename' OR driveruniquename2 = '$uniquename' ORDER BY dateEvent DESC, IDvf DESC";	
		   }
		$sql .= " LIMIT " . $pageStart . ", " . $limit;

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
                                <th scope="col">Notes</th>
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
                                <td> <?php echo $value["notes"]; ?> </td>
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
<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
	require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');
	
	$vehicleNum = $_REQUEST['vehicleNum'];
	$foundLocation = "";


	if ( $vehicleNum == "" ) 
	{
		$foundLocation = "&nbsp; - Enter vehicle number above to locate vehicle.";
	
	}//if
	else {
	
		$sql = "SELECT parking FROM transportation_vf WHERE vehiclenum = '$vehicleNum' ORDER BY mod_on DESC LIMIT 1 ";

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
			$foundLocation =  "&nbsp; - " . $value['parking'];	 
		}//foreach
		
		if ( $foundLocation == "")
		{
			$foundLocation = "&nbsp; - No location found for this vehicle.";
		}//if
	}//else

	$db->close();


	echo $foundLocation;
?>
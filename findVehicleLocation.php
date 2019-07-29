<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/../support/connect_tran_vf.php");
	
	global $db;
	$db = new mysqli('localhost', $connectionUserText, $connectionsUserPassword , $db);

	if($db->connect_errno > 0) 
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}//if
	
	$vehicleNum = $_REQUEST['vehicleNum'];
	
	if ( $vehicleNum == "" ) 
	{
		$foundLocation = "Enter vehicle number above to locate vehicle.";
	
	}//if
	else {
	
		$sql = "SELECT parking FROM transportation_vf WHERE vehiclenum = \"$vehicleNum\" ORDER BY mod_on DESC LIMIT 1 ";
		
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
			$foundLocation =  $value['parking'];	 
		}//foreach
		
		if ( $foundLocation == "")
		{
			$foundLocation = "No location found for this vehicle.";
		}//if
	}//else

	$db->close();


	echo $foundLocation;
?>
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

	$id = $_REQUEST['id'];
	
	$sql = "DELETE FROM transportation_vf WHERE IDvf = '$id'";
	
 	if(!$result = $db->query($sql))
	{
		die('There was an error running the query [' . $db->error . ']');
	}//if
        else {
	   $db->close();
           header("Location:updatevf.php");
           exit();
        }
?>

<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basic_lib.php');

// $file = 'export_blueeecard';

 
// $result = mysql_query("SELECT * FROM transportation_vf");

// /*if (mysql_num_rows($result) > 0) {
// 	while ($row = mysql_fetch_assoc($result)) {
// 		$csv_output .= $row['Field'].", ";
// 		$i++;
// 	}//WHILE
// }//IF*/

// //num of Fields
// $i = 15;
 
// $values = mysql_query("SELECT * FROM transportation_vf");

// //headers
// $csv_output .= "id, uniquename, First Name, Last Name, Program, Phone, Vehicle, Date, Mileage (Depart), Mileage (Return), Fuel (Depart), Fuel (Return), Parking, Notes, Admin Notes";

// $csv_output .= "\n";


// while ($rowr = mysql_fetch_row($values)) {
// 	for ($j=0; $j<$i; $j++) {
// 		$csv_output .= $rowr[$j].", ";
// 	}//for
// 	$csv_output .= "\n";
// }//while
 
// $filename = "export_blueeecard_".date("Y-m-d_H-i",time());

// header("Content-type: application/vnd.ms-excel");

// header("Content-disposition: csv" . date("Y-m-d") . ".csv");
// header("Content-disposition: filename=".$filename.".csv");

// print $csv_output;

// exit;
// 



// $isAdmin = false;
// $_SESSION['isAdmin'] = false;
// $sqlSelect = <<< _SQL
//     SELECT *
//     FROM tbl_contestadmin
//     WHERE uniqname = '$login_name'
//     ORDER BY uniqname
// _SQL;
// if (!$resAdmin = $db->query($sqlSelect)) {
//     db_fatal_error("data insert issue", $db->error, $sqlSelect, $login_name);
// exit;
// }
// if ($resAdmin->num_rows > 0) {
//     $isAdmin = true;
//     $_SESSION['isAdmin'] = true;
// }

if ($isAdmin) {
	$filename = "export_blueeecard_".date("Y-m-d_H-i",time());

    // output headers so that the file is downloaded rather than displayed
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$filename.'.csv');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // output the column headings
    fputcsv($output, array('id', 'uniquename', 'First Name', 'Last Name', 'Program', 'Phone', 'Vehicle', 'Date', 'Mileage (Depart)', 'Mileage (Return)', 'Fuel (Depart)', 'Fuel (Return)', 'Parking', 'Notes', 'Admin Notes'));

                $sqlSelect2 = <<<SQL
			SELECT * FROM transportation_vf
SQL;

    if (!$result = $db->query($sqlSelect2)) {
        db_fatal_error("data select issue", $db->error);
        exit;
    }

    // loop over the rows, outputting them
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    echo "You are not allowed to view this stuff!";
}

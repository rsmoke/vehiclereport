<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

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
			SELECT IDvf, uniquename, firstname, lastname, program, phone, vehiclenum, dateEvent, mileageDepart, mileageReturn, fuelDepart, fuelReturn, parking, notes, adminnotes FROM transportation_vf
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

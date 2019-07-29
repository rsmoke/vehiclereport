<?php
header('Content-Type: image/jpeg');

$imagedamagesitefilename = $_GET['image']; 

$src = "uploads/".$imagedamagesitefilename;

$img = imagecreatefromstring(file_get_contents($src));

imagejpeg($img, NULL, 10);

?>
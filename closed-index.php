<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/ceal_config.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/basicLib.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?php echo "$siteTitle";?> Opens Soon!</title>

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="CEAL Vehicle Report">
  <meta name="keywords" content="CEAL, Vehicle, Report, UniversityofMichigan">
  <meta name="author" content="jadarga">
  <link rel="icon" href="img/favicon.ico">
  <style>
    html {
    background: url(images/maintainImage.png) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='images/maintainImage.png', sizingMethod='scale');
    -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='images/maintainImage.png', sizingMethod='scale')";
  }
  .text-center {
      color: blue;
      font-weight: bold;
      text-align: center;
  }
  footer {
    font-size: .8rem;
      position: fixed;
      bottom: 10px;
      width: 100%;
  }
  a {
    background-color: white;
  }
  </style>
</head>

<body>

  <div>
    <h1 class="text-center">The CEAL Vehicle Reports will be opening again soon.<br>Please
    check back.</h1>
  </div>
<footer>
  <div class="text-center" >
    <address>
      <h3>Department of <?php echo $deptLngName;?></h3>
      <a href="mailto:<?php echo strtolower($addressEmail);?>"><?php echo strtolower($addressEmail);?></a>
      <br><?php echo $addressBldg;?>, <?php echo $address2;?>
      <br><?php echo $addressStreet;?>
      <br>Ann Arbor, MI
      <br><?php echo $addressZip;?>
      <br>P: <?php echo $addressPhone;?>
      <br>F: <?php echo $addressFax;?>
    </address>
      
  </div>

  <div class="text-center">
    <a href="http://www.regents.umich.edu">© 2019 Regents of the University of Michigan</a>
  </div>
</footer>
</body>
</html>

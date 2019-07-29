    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href=<?php echo URL;?>>CEAL Vehicle Report</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link" href=<?php echo URL . "index.php";?>>New Form<span class="sr-only">(current)</span></a>
          <a class="nav-item nav-link" href=<?php echo URL . "updatevf.php";?>>Update Form</a>
        <?php 
            if ($isAdmin) {
                $html = '';
                $html .= '<a class="nav-item nav-link" href='. URL . 'admin/updatevfadmin.php>Admin Review/Edit Forms</span></a>';
				$html .= '<a class="nav-item nav-link" href='. URL . 'admin/adminreport.php>Admin Report</a>';
				echo $html;
            }
		?>
        </div>
      </div>
    </nav>
    
      <div class="page-header">
		<img class="img-fluid" src="../images/CEAL_logo.jpg" alt="LSA CEAL Logo" style="width:70%; margin:1rem; min-width: 300px; max-width:700px;" >
	  </div>
	  <style>
	      .bg-dark {
            background-color: #00274c !important;
            }
	  </style>
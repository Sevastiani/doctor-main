
<!DOCTYPE html>
<html lang="en">
<?php
// Start the session
session_start();
if (isset($_SESSION["DoctorName"])==false)
{
  echo("<script>location.href = 'index.php';</script>");
}
?>
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Patients  <!-- Main Sidebar Container --></title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
 <script>
  function logout() {
    var form_data = {logout: "logout"};
    $.ajax({
            type: "POST",
            url: "login_page.php",
            data: form_data,
            async:false,
            success: function(response)
            {
				console.log('test');
				console.log(response);
                if(response == 'logout'){
					window.location.href = 'http://localhost:8888/realms/medapp/protocol/openid-connect/logout?client_id=fhir-client&post_logout_redirect_uri=http%3A%2F%2Flocalhost%2Fdoctor-main%2F';
                }
                else
				{
					console.log("error logout")
					window.location.href = 'http://localhost:80/doctor-main/ErrorPage.php';
				}
			}
        });
	}
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">

<div>

<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- SEARCH FORM -->
    <form class="form-inline ml-3" name='search_form' method="post" action="PatientDetails.php">
      <div class="input-group input-group-sm">
        <p><b>Patiet ID: </b></p>
        <input class="form-control form-control-navbar" id="patientID" name="patientID" type="search" placeholder="121212" aria-label="Search">

        <p><b>Patiet Phone Number: </b></p>
        <input class="form-control form-control-navbar" id="Phone" name="Phone" type="search" placeholder="96969696" aria-label="Search">
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Search</button>
          </div>
      </div>
    </form>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="Doctor_MainPage.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Doctor</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
          <?php
              echo $_SESSION['DoctorName'];
            ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Doctor:
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="DoctorDetails.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Personal Details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="DoctorCalendar.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Calendar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Patient
                <i class="right fas fa-angle-left"></i>
                <span class="badge badge-info right">5</span>
              </p>
            </a>
            <ul>
              <li class="nav-item">
                <a href="PatientDetails.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Personal Details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="Calendar.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Set Appointment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="PatientHistory.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Appointments History</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="CreateNewPatient.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                  <p> New Patient </p>
              </a>
              </li>
			  <li class="nav-item">
                <a href="PatientList.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                  <p>Patients List</p>
              </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
                <i class="far fa-circle nav-icon"></i><a href="javascript:logout();">Logout</a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

<!--LISTING -->


  <div class="container my-4">
    <p class="font-weight-bold">Patient List:</p>

    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
  <thead>
    <tr>

      <th class="th-sm">ID</th>
      <th class="th-sm">Name</th>
	  <th class="th-sm">Surname</th>
      <th class="th-sm">Date of Birth</th>
      <th class="th-sm">Phone</th>
      <th class="th-sm">Email</th>
    </tr>
  </thead>
  <tbody>
  <?php
	$url="http://localhost:80/doctor-main/BackEnd/Patient/search_Allpatient.php";
	$data=file_get_contents($url);
    $patient=json_decode($data, true);
    $total=$patient['total'];

    for ($j=0;$j<$total;$j++)
	{
      $Patient_Name=$patient['entry'][$j]['resource']['name'][0]['given'][0];
      $Patient_Surname=$patient['entry'][$j]['resource']['name'][0]['family'];
      $Patient_id=$patient['entry'][$j]['resource']['identifier'][0]['value'];
      $Patient_DateOfBirth=$patient['entry'][$j]['resource']['birthDate'];
      for ($i=0;$i<sizeof($patient['entry'][$j]['resource']['telecom']);$i++)
      {
        if ($patient['entry'][$j]['resource']['telecom'][$i]['system']=="phone")
        {
          $Patient_Phone=$patient['entry'][$j]['resource']['telecom'][$i]['value'];
        }
        elseif ($patient['entry'][$j]['resource']['telecom'][$i]['system']=="email"){
          $Patient_Email=$patient['entry'][$j]['resource']['telecom'][$i]['value'];
        }
      }
		echo "<tr>
		  <td>".$Patient_id."</td>
		  <td>".$Patient_Name."</td>
		  <td>".$Patient_Surname."</td>
		  <td>".$Patient_DateOfBirth."</td>
		  <td>".$Patient_Phone."</td>
		  <td>".$Patient_Email."</td>
		</tr>";
    }

  ?>
  </tfoot>
</table>
<script>
	$(document).ready(function () {
  $('#dtBasicExample').DataTable();
  $('.dataTables_length').addClass('bs-select');
});
</script>
  </div>
<!--LISTING -->


    <!-- Main content -->
    <div  class="container">

    </div>
    <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>FHIR SERVER</strong>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap.min.js"></script>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="dist/js/demo.js"></script>
<script src="dist/js/pages/dashboard3.js"></script>
</body>
</html>

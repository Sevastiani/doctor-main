<?php
// Start the session
session_start();
if (isset($_SESSION["DoctorName"])==false)
{
  echo("<script>location.href = 'index.php';</script>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Patient History</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
<body class="hold-transition sidebar-mini">
<div class="wrapper">

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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Patient History</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
      <form class="form-inline ml-3" name='search_form' method="post" action="PatientHistory.php">
        <div class="input-group input-group-sm">
          <p><b>Patiet ID: </b></p>
          <input class="form-control form-control-navbar" id="patientID" name="patientID" type="search" placeholder="121212" aria-label="Search">

          <p><b>Patiet Phone Number: </b></p>
          <input class="form-control form-control-navbar" id="Phone" name="Phone" type="search" placeholder="96969696" aria-label="Search">
          <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Search History</button>
            </div>
        </div>
      </form>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                    <?php
                         $patientID=$_POST['patientID'];
                         $Phone=$_POST['Phone'];
                         if ($patientID!=NULL or $Phone!=NULL or $patientID!="" or $Phone!="")
                         {
                             $url="http://localhost:80/doctor-main/BackEnd/Patient/search_patient.php?patientID=".$patientID."&Phone=".$Phone;
                             $data=file_get_contents($url);
                             $patient=json_decode($data, true);
                             $total=$patient['total'];

                             if ($total>0)
                             {
                                $Patient_Name=$patient['entry'][0]['resource']['name'][0]['given'][0];
                                $Patient_Surname=$patient['entry'][0]['resource']['name'][0]['family'];
                                $FullName=$Patient_Name.' '.$Patient_Surname;
                                $Patient_id=$patient['entry'][0]['resource']['identifier'][0]['value'];
                                for ($i=0;$i<sizeof($patient['entry'][0]['resource']['telecom']);$i++)
                                {
                                    if ($patient['entry'][0]['resource']['telecom'][$i]['system']=="phone")
                                    {
                                      $Patient_Phone=$patient['entry'][0]['resource']['telecom'][$i]['value'];
                                    }
                                }
                             }
                         }
                    ?>
                <h3 class="card-title"><b>History of <?php echo $FullName;?>:</b> <br><b>Patient Phone:</b> <?php echo $Patient_Phone;?> <br><b>Patient id:</b><?php echo $Patient_id;?>  </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Appointment ID</th>
                    <th>Date/Time</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Result</th>
                    <th>Doctor</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php

                    if ($total>0)
                    {
                        $FHIRPatientID=$patient['entry'][0]['resource']['id'];
                        $url="http://localhost:80/doctor-main/BackEnd/Calendar/AppointmentHistory.php?FHIRpatientID=".$FHIRPatientID;
                        $data=file_get_contents($url);
                        $appointments=json_decode($data, true);
                        $total=$appointments['total'];
                        if ($total==0)
                        {
                          echo "<tr>
                                  <td colspan='6'>No Results found</td>
                                </tr>" ;
                        }
                        else
                        {
                            for ($i=0;$i<$total;$i++)
                            {

                              $appointmentID=$appointments['entry'][$i]['resource']['id'];
                              $date=$appointments['entry'][$i]['resource']['start'];
                              $Status=$appointments['entry'][$i]['resource']['status'];
                              $description=$appointments['entry'][$i]['resource']['description'];

							  if(strpos($description, ':') !== false){
								  $splitresult=explode (":",$description);
								  $reason=$splitresult[0];
								  $Result=$splitresult[1];
							  }
							  else{
								  $reason=$description;
								  $Result=NULL;
							  }
                              $participant=$appointments['entry'][$i]['resource']['participant'];

                              for($j=0;$j<sizeof($participant);$j++)
                              {
                                $member=$participant[$j]['actor']['reference'];
                                if (strpos($member, 'Practitioner/') !== false)
                                {
                                  $doctor_name=$participant[$j]['actor']['display'];
                                }
                              }

                              echo "<tr>
                                      <td>".$appointmentID."</td>
                                      <td>".$date."</td>
                                      <td>".$Status."</td>
                                      <td>".$reason."</td>
                                      <td>".$Result."</td>
                                      <td>".$doctor_name."</td>
                                    </tr>";
                            }
                        }
                    }
                    else
                    {
                      echo "<tr>
                              <td colspan='6'>No Results found</td>
                            </tr>" ;
                    }

                    ?>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>FHIR Server.</strong>


  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
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

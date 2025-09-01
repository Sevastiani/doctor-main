<?php
// Start the session
session_start();
if (isset($_SESSION["DoctorName"])==false)
{
  echo("<script>location.href = 'index.php';</script>");
}
$DoctorID=$_SESSION['Doctor_id'];
$url="http://localhost:80/doctor-main/BackEnd/Practitioner/search_Practitioner.php?doctor_id=".$DoctorID;
$data=file_get_contents($url);
$Practitioner=json_decode($data, true);

$total=$Practitioner['total'];
if ($total>0)
{
    $Name=$Practitioner['entry'][0]['resource']['name'][0]['given'][0];
    $Surname=$Practitioner['entry'][0]['resource']['name'][0]['family'];
    $Prefix=$Practitioner['entry'][0]['resource']['name'][0]['prefix'][0];
    $id=$Practitioner['entry'][0]['resource']['identifier'][0]['value'];

    for ($i=0;$i<sizeof($Practitioner['entry'][0]['resource']['telecom']);$i++)
    {
      if ($Practitioner['entry'][0]['resource']['telecom'][$i]['system']=="phone")
      {
        $Phone=$Practitioner['entry'][0]['resource']['telecom'][$i]['value'];
      }
      elseif ($Practitioner['entry'][0]['resource']['telecom'][$i]['system']=="email"){
        $Email=$Practitioner['entry'][0]['resource']['telecom'][$i]['value'];
      }
    }
    $Gender=$Practitioner['entry'][0]['resource']['gender'];
    $City=$Practitioner['entry'][0]['resource']['address'][0]['city'];
    $PostalCode=$Practitioner['entry'][0]['resource']['address'][0]['postalCode'];
    $Address=$Practitioner['entry'][0]['resource']['address'][0]['district'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Doctor Main Page</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/JavaScript">
  function EditForm()
  {
	  var nametextbox = document.getElementById('txtname');
	  if (nametextbox.disabled)
      {
        //edit button
        document.getElementById("txtPrefix").disabled = false;
        document.getElementById("txtname").disabled = false;
        document.getElementById("txtsurname").disabled = false;
        document.getElementById("txtemail").disabled = false;
        document.getElementById("txtphone").disabled = false;
        document.getElementById("txtCity").disabled = false;
        document.getElementById("txtAddress").disabled = false;
        document.getElementById("txtPostalCode").disabled = false;
        document.getElementById("txtgender").disabled = false;
        document.getElementById("btnsave").hidden = false;
        document.getElementById("btnedit").hidden = true;
		  }
		  else
		  {
          //save button
        var Prefix=document.forms['Edit_Form']['txtPrefix'].value;
        var Name=document.forms['Edit_Form']['txtname'].value;
        var surname=document.forms['Edit_Form']['txtsurname'].value;
        var DoctorID=document.forms['Edit_Form']['DoctorID'].value;
        var email=document.forms['Edit_Form']['txtemail'].value;
        var phone=document.forms['Edit_Form']['txtphone'].value;
        var Address=document.forms['Edit_Form']['txtAddress'].value;
        var PostalCode=document.forms['Edit_Form']['txtPostalCode'].value;
        var City=document.forms['Edit_Form']['txtCity'].value;
        var gender=document.forms['Edit_Form']['txtgender'].value;

        //alert(Name + " "+surname+ " "+patientID+ " "+email+ " "+patientID+ " "+birth+ " "+phone+ " "+Address+ " "+gender+ " "+PostalCode+ " "+City);
        document.getElementById("txtPrefix").disabled = true;
        document.getElementById("txtname").disabled = true;
        document.getElementById("txtsurname").disabled = true;
        document.getElementById("DoctorID").disabled = true;
        document.getElementById("txtemail").disabled = true;
        document.getElementById("txtphone").disabled = true;
        document.getElementById("txtAddress").disabled = true;
        document.getElementById("txtPostalCode").disabled = true;
        document.getElementById("txtCity").disabled = true;
        document.getElementById("txtgender").disabled = true;
        document.getElementById("btnsave").hidden = true;
        document.getElementById("btnedit").hidden = false;
        //call ajax to save
        $(document).ready(function(){
        $.ajax({
          url: "http://localhost:80/doctor-main/BackEnd/Doctor/save_DoctorDetails.php?DoctorID="+DoctorID+"&Prefix="+Prefix+"&name="+Name+"&surname="+surname+"&email="+email+"&phone="+phone+"&gender="+gender+"&Address="+Address+"&PostalCode="+PostalCode+"&City="+City,
          type: "GET",
          success: function (response){
            var k=response;
            alert(k);
            if(k.indexOf("OK") != -1)
            {
              alert('Saved');
              return false;
            }
            else{
              alert('Please try again!');
              return false;
            }
          },
          error: function (error){
            alert('Please Try Again!');
            return false;
          }
          })
        })
		}
	  return false;
  }
  </script>

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
</head>

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
              echo $_SESSION['Refreshtoken'];
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
            <ul>
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
            <ul class="nav nav-treeview">
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

    <!-- Main content -->
    <div class="content">
  <!-- general form elements disabled -->

  <div class="card card-warning">
    <div class="card-header">
      <h3 class="card-title">General Details</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <form  name='Edit_Form' method="post" onsubmit="return EditForm()">
      <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>ID</label>
              <input type="text" id='DoctorID' name='DoctorID' class="form-control" value="<?php echo $id; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Prefix</label>
              <input type="text" id='txtPrefix' name='txtPrefix'class="form-control" value="<?php echo $Prefix; ?>"placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>Name</label>
              <input type="text" id='txtname' name='txtname' class="form-control" value="<?php echo $Name; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Surname</label>
              <input type="text" id='txtsurname' name='txtsurname'class="form-control" value="<?php echo $Surname; ?>"placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Email</label>
              <input type="text" id='txtemail' name='txtemail' class="form-control" value="<?php echo $Email; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Phone</label>
              <input type="text" id='txtphone' name='txtphone' class="form-control" value="<?php echo $Phone; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>Gender</label>
			  <?php
				  if ($Gender=='male')
				  {
					  $options='<option value="female">female</option>
					  <option value="male" selected>male</option>';
				  }else{
					  $options='<option value="female" selected>female</option>
					  <option value="male">male</option>';
				  }
			  ?>
			  <select id="txtgender" name="txtgender" class="form-control" disabled>
				 <?php
					echo $options;
				 ?>
				</select>

            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>City</label>
              <input type="text" id='txtCity' name='txtCity' class="form-control" value="<?php echo $City; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>Postal Code</label>
              <input type="text" id='txtPostalCode' name='txtPostalCode' value="<?php echo $PostalCode; ?>" class="form-control" placeholder="Enter 1..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Address</label>
              <input type="text" id='txtAddress' name='txtAddress' class="form-control" value="<?php echo $Address; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <button type="submit" id='btnedit' name='btnedit' class="btn btn-primary btn-block">Edit</button>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <button type="submit" id='btnsave' name='btnsave'class="btn btn-primary btn-block"  hidden="hidden">Save</button>
            </div>
          </div>
        </div>
    </form>
 </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

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

<?php
// Start the session
session_start();
if (!isset($_SESSION["DoctorName"])) {
    header("Location: index.php");
    exit;
}

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Initialize patient variables
$Patient_Name = $Patient_Surname = $Patient_id = $Patient_DateOfBirth =
$Patient_Phone = $Patient_Gender = $Patient_City = $Patient_PostalCode =
$Patient_Address = $Patient_Email = '';

$patientID = isset($_POST['patientID']) ? trim($_POST['patientID']) : '';
$Phone     = isset($_POST['Phone']) ? trim($_POST['Phone']) : '';

if ($patientID !== '' || $Phone !== '') {
    $url = "http://localhost/doctor-main/BackEnd/Patient/search_patient.php?patientID="
         . rawurlencode($patientID) . "&Phone=" . rawurlencode($Phone);

    $data = @file_get_contents($url);
    if ($data !== false) {
        $patient = json_decode($data, true);
    } else {
        $patient = null;
    }

    $total = is_array($patient) && isset($patient['total']) ? (int)$patient['total'] : 0;

    if ($total > 0) {
        $entry = $patient['entry'][0]['resource'] ?? [];
        $Patient_Name        = $entry['name'][0]['given'][0]   ?? '';
        $Patient_Surname     = $entry['name'][0]['family']     ?? '';
        $Patient_id          = $entry['identifier'][0]['value']?? '';
        $Patient_DateOfBirth = $entry['birthDate']             ?? '';
        $Patient_Gender      = $entry['gender']                ?? '';
        $Patient_City        = $entry['address'][0]['city']    ?? '';
        $Patient_PostalCode  = $entry['address'][0]['postalCode'] ?? '';
        $Patient_Address     = $entry['address'][0]['line'][0] ?? ($entry['address'][0]['district'] ?? '');

        if (!empty($entry['telecom']) && is_array($entry['telecom'])) {
            foreach ($entry['telecom'] as $t) {
                if (($t['system'] ?? '') === 'phone' && empty($Patient_Phone)) {
                    $Patient_Phone = $t['value'] ?? '';
                }
                if (($t['system'] ?? '') === 'email' && empty($Patient_Email)) {
                    $Patient_Email = $t['value'] ?? '';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor Main Page</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script>
  function logout() {
    $.ajax({
      type: "POST",
      url: "login_page.php",
      data: {logout: "logout"},
      async: false,
      success: function(response) {
        if (response === 'logout') {
          window.location.href = 'http://localhost:8888/realms/medapp/protocol/openid-connect/logout?client_id=fhir-client&post_logout_redirect_uri=http%3A%2F%2Flocalhost%2Fdoctor-main%2F';
        } else {
          window.location.href = 'http://localhost:80/doctor-main/ErrorPage.php';
        }
      },
      error: function() {
        alert("Logout failed");
      }
    });
  }

  function EditForm() {
    var nameBox = document.getElementById('txtname');
    if (nameBox.disabled) {
      // Switch to edit mode
      ['txtname','txtsurname','txtemail','txtbirth','txtphone','txtCity','txtAddress','txtPostalCode','txtgender']
        .forEach(function(id){ document.getElementById(id).disabled = false; });
      document.getElementById('btnsave').hidden = false;
      document.getElementById('btnedit').hidden = true;
      return false;
    } else {
      // Save via AJAX
      var payload = {
        patientID:  document.getElementById('txtid').value,
        name:       document.getElementById('txtname').value,
        surname:    document.getElementById('txtsurname').value,
        email:      document.getElementById('txtemail').value,
        birth:      document.getElementById('txtbirth').value,
        phone:      document.getElementById('txtphone').value,
        gender:     document.getElementById('txtgender').value,
        Address:    document.getElementById('txtAddress').value,
        PostalCode: document.getElementById('txtPostalCode').value,
        City:       document.getElementById('txtCity').value
      };

      $.ajax({
        url: "http://localhost:80/doctor-main/BackEnd/Patient/save_patientDetails.php",
        type: "POST",
        data: payload,
        success: function (response) {
          if (typeof response === 'string' && response.indexOf("OK") !== -1) {
            alert('Saved');
          } else {
            alert('Please try again! Test');
          }
        },
        error: function () {
          alert('Please Try Again!');
        },
        complete: function () {
          // Back to view mode
          ['txtname','txtsurname','txtid','txtemail','txtbirth','txtphone','txtAddress','txtPostalCode','txtCity','txtgender']
            .forEach(function(id){ document.getElementById(id).disabled = true; });
          document.getElementById('btnsave').hidden = true;
          document.getElementById('btnedit').hidden = false;
        }
      });
      return false;
    }
  }
  </script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <form class="form-inline ml-3" method="post" action="PatientDetails.php">
      <div class="input-group input-group-sm">
        <p><b>Patient ID: </b></p>
        <input class="form-control form-control-navbar" id="patientID" name="patientID" type="search" placeholder="121212">
        <p><b>Patient Phone Number: </b></p>
        <input class="form-control form-control-navbar" id="Phone" name="Phone" type="search" placeholder="96969696">
        <div class="col-4">
          <button type="submit" class="btn btn-primary btn-block">Search</button>
        </div>
      </div>
    </form>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="Doctor_MainPage.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
      <span class="brand-text font-weight-light">Doctor</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo h($_SESSION['DoctorName']); ?></a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-copy"></i><p>Doctor<i class="fas fa-angle-left right"></i></p></a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="DoctorDetails.php" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Personal Details</p></a></li>
              <li class="nav-item"><a href="DoctorCalendar.php" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Calendar</p></a></li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-chart-pie"></i><p>Patient<i class="right fas fa-angle-left"></i></p></a>
            <ul>
              <li class="nav-item"><a href="PatientDetails.php" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Personal Details</p></a></li>
              <li class="nav-item"><a href="Calendar.php" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Set Appointment</p></a></li>
              <li class="nav-item"><a href="PatientHistory.php" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Appointments History</p></a></li>
              <li class="nav-item"><a href="CreateNewPatient.php" class="nav-link"><i class="far fa-circle nav-icon"></i><p>New Patient</p></a></li>
              <li class="nav-item"><a href="PatientList.php" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Patients List</p></a></li>
            </ul>
          </li>
          <li class="nav-item"><i class="far fa-circle nav-icon"></i><a href="javascript:logout();">Logout</a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content">
      <div class="card card-warning">
        <div class="card-header"><h3 class="card-title">General Details</h3></div>
        <div class="card-body">
          <form name="Edit_Form" method="post" onsubmit="return EditForm()">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group"><label>Name</label>
                  <input type="text" id="txtname" name="txtname" class="form-control" value="<?php echo h($Patient_Name); ?>" disabled>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group"><label>Surname</label>
                  <input type="text" id="txtsurname" name="txtsurname" class="form-control" value="<?php echo h($Patient_Surname); ?>" disabled>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6"><div class="form-group"><label>ID</label>
                <input type="text" id="txtid" name="txtid" class="form-control" value="<?php echo h($Patient_id); ?>" disabled></div></div>
              <div class="col-sm-6"><div class="form-group"><label>Email</label>
                <input type="text" id="txtemail" name="txtemail" class="form-control" value="<?php echo h($Patient_Email); ?>" disabled></div></div>
            </div>

            <div class="row">
              <div class="col-sm-6"><div class="form-group"><label>Date of Birth</label>
                <input type="date" id="txtbirth" name="txtbirth" class="form-control" value="<?php echo h($Patient_DateOfBirth); ?>" disabled></div></div>
              <div class="col-sm-6"><div class="form-group"><label>Phone</label>
                <input type="text" id="txtphone" name="txtphone" class="form-control" value="<?php echo h($Patient_Phone); ?>" disabled></div></div>
            </div>

            <div class="row">
              <div class="col-sm-6"><div class="form-group"><label>Gender</label>
                <?php $g = strtolower((string)$Patient_Gender); ?>
                <select id="txtgender" name="txtgender" class="form-control" disabled>
                  <option value="" <?php echo $g === '' ? 'selected' : ''; ?>>—</option>
                  <option value="female" <?php echo $g === 'female' ? 'selected' : ''; ?>>female</option>
                  <option value="male" <?php echo $g === 'male' ? 'selected' : ''; ?>>male</option>
                </select>
              </div></div>
              <div class="col-sm-6"><div class="form-group"><label>City</label>
                <input type="text" id="txtCity" name="txtCity" class="form-control" value="<?php echo h($Patient_City); ?>" disabled></div></div>
            </div>

            <div class="row">
              <div class="col-sm-6"><div class="form-group"><label>Postal Code</label>
                <input type="text" id="txtPostalCode" name="txtPostalCode" class="form-control" value="<?php echo h($Patient_PostalCode); ?>" disabled></div></div>
              <div class="col-sm-6"><div class="form-group"><label>Address</label>
                <input type="text" id="txtAddress" name="txtAddress" class="form-control" value="<?php echo h($Patient_Address); ?>" disabled></div></div>
            </div>

            <div class="row">
              <div class="col-sm-4"><button type="submit" id="btnedit" name="btnedit" class="btn btn-primary btn-block">Edit</button></div>
              <div class="col-sm-4"><button type="submit" id="btnsave" name="btnsave" class="btn btn-primary btn-block" hidden>Save</button></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer class="main-footer"><strong>FHIR Server.</strong></footer>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>
</body>
</html>

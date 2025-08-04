<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'jobseeker') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="text-center mb-4">Welcome, <?php echo $_SESSION['first_name']; ?>!</h2>

  <div class="row">
    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <i class="bi bi-search display-4 text-primary"></i>
          <h5 class="mt-3">Search Jobs</h5>
          <a href="#" class="btn btn-outline-primary btn-sm mt-2">Explore</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <i class="bi bi-file-earmark-text display-4 text-success"></i>
          <h5 class="mt-3">My Applications</h5>
          <a href="#" class="btn btn-outline-success btn-sm mt-2">View</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <i class="bi bi-person-lines-fill display-4 text-warning"></i>
          <h5 class="mt-3">Update Profile</h5>
          <a href="#" class="btn btn-outline-warning btn-sm mt-2">Update</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <i class="bi bi-bell display-4 text-danger"></i>
          <h5 class="mt-3">Job Alerts</h5>
          <a href="#" class="btn btn-outline-danger btn-sm mt-2">Manage</a>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>

</body>
</html>

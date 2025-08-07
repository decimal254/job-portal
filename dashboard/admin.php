<?php
session_start();
require_once '../config/db.php';




$totalUsersStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $totalUsersStmt->fetchColumn();


$totalJobsStmt = $pdo->query("SELECT COUNT(*) FROM jobs");
$totalJobs = $totalJobsStmt->fetchColumn();


$totalAppsStmt = $pdo->query("SELECT COUNT(*) FROM job_applications");
$totalApplications = $totalAppsStmt->fetchColumn();

$usersStmt = $pdo->query("SELECT first_name, last_name, email, role FROM users ORDER BY user_id DESC LIMIT 5");
$recentUsers = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">



  
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="card shadow-sm text-center p-4">
        <i class="bi bi-people display-4 text-primary"></i>
        <h5 class="mt-2">Total Users</h5>
        <p class="display-6"><?= $totalUsers ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm text-center p-4">
        <i class="bi bi-briefcase display-4 text-success"></i>
        <h5 class="mt-2">Total Jobs</h5>
        <p class="display-6"><?= $totalJobs ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm text-center p-4">
        <i class="bi bi-file-earmark-text display-4 text-warning"></i>
        <h5 class="mt-2">Total Applications</h5>
        <p class="display-6"><?= $totalApplications ?></p>
      </div>
    </div>
  </div>

  
  <h4 class="mb-3">Recent Users</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($recentUsers): ?>
          <?php foreach ($recentUsers as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= ucfirst(htmlspecialchars($user['role'])) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No recent users found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

 
  <div class="text-center mt-4">
    <a href="../auth/logout.php" class="btn btn-danger px-4">Logout</a>
  </div>
</div>

</body>
</html>

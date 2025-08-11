<?php
session_start();
require_once '../config/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}


$adminName = $_SESSION['first_name'] ?? 'Admin';


try {
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalJobs = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
    $totalApplications = $pdo->query("SELECT COUNT(*) FROM job_applications")->fetchColumn();

    $stmt = $pdo->query("SELECT first_name, last_name, email, role FROM users ORDER BY user_id DESC LIMIT 5");
    $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
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

<div class="container mt-5">
  
  <div class="text-center mb-4">
    <h2>Welcome, <?= htmlspecialchars($adminName) ?> (Admin)</h2>
    <p class="text-muted">You are viewing the admin dashboard</p>
  </div>

  
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
    <table class="table table-dark table-striped">
      <thead>
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

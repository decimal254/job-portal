<?php
session_start();


if (
    empty($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'jobseeker'
) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jobseeker Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="text-center mb-4">
    Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?>!
  </h2>
  
  <div class="row text-center">
    <?php foreach ([
      ['bi-search', 'Search Jobs', 'Explore'],
      ['bi-file-earmark-text', 'My Applications', 'View'],
      ['bi-person-lines-fill', 'Update Profile', 'Update'],
      ['bi-bell', 'Job Alerts', 'Manage']
    ] as $card): ?>
    <div class="col-md-3 mb-3">
      <div class="card shadow">
        <div class="card-body">
          <i class="bi <?= $card[0] ?> display-4"></i>
          <h5 class="mt-3"><?= $card[1] ?></h5>
          <a href="#" class="btn btn-outline-primary btn-sm mt-2"><?= $card[2] ?></a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="text-center mt-4">
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>
</body>
</html>

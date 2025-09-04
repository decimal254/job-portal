<?php
session_start();
require_once '../config/db.php';

$application_id = $_GET['id'] ?? null;

if (!$application_id) {
    die("Invalid application.");
}

try {
    $sql = "SELECT a.application_id, a.applied_at, a.status, a.cv_path,
                   u.first_name, u.last_name, u.email, u.mobile_number,
                   j.title AS job_title
            FROM job_applications a
            JOIN users u ON a.user_id = u.user_id
            JOIN jobs j ON a.job_id = j.job_id
            WHERE a.application_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$application_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$application) {
        die("Invalid application.");
    }
} catch (PDOException $e) {
    die("Error fetching application: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Application Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow p-4">
    <h2 class="mb-4">Application Details</h2>

    <p><strong>Job Title:</strong> <?= htmlspecialchars($application['job_title']) ?></p>
    <p><strong>Applicant:</strong> <?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($application['email']) ?></p>
    <p><strong>Mobile:</strong> <?= htmlspecialchars($application['mobile_number']) ?></p>
    <p><strong>Applied At:</strong> <?= htmlspecialchars($application['applied_at']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($application['status']) ?></p>

    <?php if (!empty($application['cv_path'])): ?>
      <p><strong>Resume:</strong> 
        <a href="../<?= htmlspecialchars($application['cv_path']) ?>" target="_blank" class="btn btn-sm btn-primary">
          View Resume
        </a>
      </p>
    <?php else: ?>
      <p class="text-danger"><em>No CV uploaded.</em></p>
    <?php endif; ?>

    <a href="view_employer.php" class="btn btn-secondary mt-3">Back to Applications</a>
  </div>
</div>
</body>
</html>

<?php
session_start();
require_once '../config/db.php';


$user_id = $_SESSION['user_id'] ?? null;
$role    = $_SESSION['role'] ?? null;

if (!$user_id || $role !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}


$application_id = (int)($_GET['id'] ?? 0);
$app = null;
$error = '';

if ($application_id === 0) {
    $error = "Invalid application.";
} else {
    try {
        
        $sql = "SELECT ja.application_id, ja.applied_at, ja.status, ja.cv_path,
                       j.title AS job_title,
                       u.first_name, u.last_name, u.email
                FROM job_applications ja
                JOIN jobs j ON ja.job_id = j.job_id
                JOIN users u ON ja.user_id = u.user_id
                WHERE ja.application_id = ? AND j.employer_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$application_id, $user_id]);
        $app = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$app) {
            $error = "Application not found or you are not authorized to view it.";
        }
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <a href="list.php" class="btn btn-secondary mt-3">Back to Applications</a>
            <?php else: ?>
                <div class="card p-4 shadow-sm">
                    <h2 class="mb-4">Application Details</h2>

                    <p><strong>Job:</strong> <?= htmlspecialchars($app['job_title']) ?></p>
                    <p><strong>Applicant:</strong> <?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($app['email']) ?></p>
                    <p><strong>Applied On:</strong> <?= date('M d, Y', strtotime($app['applied_at'])) ?></p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-primary"><?= ucfirst($app['status']) ?></span>
                    </p>

                    <?php if (!empty($app['cv_path'])): ?>
                        <p><strong>Resume:</strong> 
                            <a href="../<?= htmlspecialchars($app['cv_path']) ?>" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">View Resume</a>
                        </p>
                    <?php else: ?>
                        <p class="text-muted">No resume uploaded.</p>
                    <?php endif; ?>
                </div>

                <a href="list.php" class="btn btn-secondary mt-3">Back to Applications</a>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>

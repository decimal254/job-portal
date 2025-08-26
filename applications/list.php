<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] === 'employer') {
    
    $stmt = $pdo->prepare("
        SELECT ja.*, j.title, u.first_name, u.last_name
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.job_id
        JOIN users u ON ja.user_id = u.user_id
        WHERE j.employer_id = ?
        ORDER BY ja.applied_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    
    $stmt = $pdo->prepare("
        SELECT ja.*, j.title, u.first_name, u.last_name
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.job_id
        JOIN users u ON j.employer_id = u.user_id
        WHERE ja.user_id = ?
        ORDER BY ja.applied_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4 text-primary">Job Applications</h2>

    <?php if ($applications): ?>
        <div class="row g-4">
            <?php foreach ($applications as $app): ?>
                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($app['title']) ?></h5>
                        <p class="text-muted mb-1">
                            <strong>Applicant:</strong> <?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?>
                        </p>
                        <p class="text-muted mb-1">
                            <strong>Status:</strong> <?= htmlspecialchars($app['status']) ?>
                        </p>
                        <p class="text-muted mb-1">
                            <strong>Applied At:</strong> <?= htmlspecialchars($app['applied_at']) ?>
                        </p>
                        <?php if (!empty($app['cover_letter'])): ?>
                            <p class="mt-2"><strong>Cover Letter:</strong> <?= nl2br(htmlspecialchars($app['cover_letter'])) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($app['resume_link'])): ?>
                            <p><strong>Resume:</strong> <a href="<?= htmlspecialchars($app['resume_link']) ?>" target="_blank">View Resume</a></p>
                        <?php endif; ?>

                        <?php if ($_SESSION['role'] === 'employer'): ?>
                            <div class="mt-2">
                                <a href="update_status.php?id=<?= $app['application_id'] ?>&status=shortlisted" class="btn btn-success btn-sm">Shortlist</a>
                                <a href="update_status.php?id=<?= $app['application_id'] ?>&status=rejected" class="btn btn-danger btn-sm">Reject</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">No applications found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once '../config/db.php';

$job_id = isset($_GET['job_id']) ? (int) $_GET['job_id'] : 0;
if ($job_id <= 0) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT jobs.*, users.first_name, users.last_name, users.position
         FROM jobs
         JOIN users ON jobs.employer_id = users.user_id
         WHERE jobs.job_id = ? AND jobs.is_active = 1
         LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$job_id]);
$jobs = $stmt->fetch(PDO::FETCH_ASSOC);

IF (!$job) {
    header('Location: index.php?error=JobNotFound');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="index.php" class="btn btn-outline-secondary mb-4">&larr; Back to jobs
            <div class="card shadow-sm p-4 bg-white">
                <h2 class="mb-3"><?=htmlspecialchars($job['title']) ?></h2>
                <h5 class="text-muted mb-3"><?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?> - <?= htmlspecialchars($job['position']) ?></h5>
                 <p><strong>Location:</strong> <?=htmlspecialchars($job['Location']) ?></p>
                 <p><strong>Category:</strong> <?=htmlspecialchars($job['Category']) ?></p>
                 <p><strong>Job Type:</strong> <?= ucfirst(str_replace('_', ' ', $job['job_type'])) ?></p>
                 <?php if (!empty($job['salary'])): ?>
            <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary']) ?></p>
        <?php endif; ?>
        <p><strong>Posted On:</strong> <?= date('F j, Y', strtotime($job['posted_at'])) ?></p>

        <hr />

        <h4>Description</h4>
        <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

        <?php if (!empty($job['requirements'])): ?>
            <h4>Requirements</h4>
            <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
            </div>
        </a>
    </div>
    
</body>
</html>


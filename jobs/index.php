<?php
session_start();
require_once '../config/db.php';


$sql = "SELECT jobs.*, users.first_name, users.last_name, users.position 
        FROM jobs 
        JOIN users ON jobs.employer_id = users.user_id
        WHERE jobs.is_active = 1
        ORDER BY jobs.posted_at DESC";
$stmt = $pdo->query($sql);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="text-center mb-4 text-primary">Available Jobs</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">Job posted successfully!</div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if ($jobs): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($job['title']) ?></h5>
                            <p class="card-text text-muted mb-1">
                                <strong>Company:</strong> <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?>
                            </p>
                            <p class="card-text text-muted mb-1">
                                <strong>Location:</strong> <?= htmlspecialchars($job['location']) ?>
                            </p>
                            <p class="card-text">
                                <?= htmlspecialchars(substr($job['description'], 0, 100)) ?>...
                            </p>
                            <a href="view.php?id=<?= $job['job_id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No jobs found at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once '../config/db.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$job_id = $_GET['id'];
$sql = "SELECT jobs.*, users.first_name, users.last_name, users.position 
        FROM jobs 
        JOIN users ON jobs.employer_id = users.user_id
        WHERE jobs.job_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($job['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h2 class="card-title text-primary"><?= htmlspecialchars($job['title']) ?></h2>
            <p class="text-muted mb-2">
                <strong>Company:</strong> <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?>
            </p>
            <p class="text-muted mb-2">
                <strong>Position:</strong> <?= htmlspecialchars($job['position']) ?>
            </p>
            <p class="text-muted mb-2">
                <strong>Location:</strong> <?= htmlspecialchars($job['location']) ?>
            </p>
            <p class="text-muted mb-2">
                <strong>Category:</strong> <?= htmlspecialchars($job['category']) ?>
            </p>
            <p class="text-muted mb-4">
                <strong>Salary Range:</strong> <?= htmlspecialchars($job['salary_range']) ?>
            </p>

            <h5 class="fw-bold">Job Description</h5>
            <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

            <h5 class="fw-bold">Requirements</h5>
            <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-outline-secondary">Back to Jobs</a>
                <a href="apply.php?job_id=<?= $job['job_id'] ?>" class="btn btn-primary">Apply Now</a>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

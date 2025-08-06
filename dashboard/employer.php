<?php
session_start();
require_once '../config/db.php';

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'employer') {
    header("Location: ../auth/login.php");
    exit();
}

$employerId = $_SESSION['user_id'];


$jCount = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE employer_id = ?");
$jCount->execute([$employerId]);
$jCount = $jCount->fetchColumn();

$appRec = $pdo->prepare("
    SELECT COUNT(*) 
    FROM job_applications jo
    JOIN jobs j ON jo.job_id = j.job_id
    WHERE j.employer_id = ?
");
$appRec->execute([$employerId]);
$appRec = $appRec->fetchColumn();


$jobsStmt = $pdo->prepare("SELECT job_id, title, location, posted_at, is_active 
                           FROM jobs WHERE employer_id = ? ORDER BY posted_at DESC");
$jobsStmt->execute([$employerId]);
$jobs = $jobsStmt->fetchAll(PDO::FETCH_ASSOC);


$appStmt = $pdo->prepare("
    SELECT jo.application_id, jo.job_id, jo.applied_at, u.first_name, u.last_name, j.title
    FROM job_applications jo
    JOIN jobs j ON jo.job_id = j.job_id
    JOIN users u ON jo.user_id = u.user_id
    WHERE j.employer_id = ?
    ORDER BY jo.applied_at DESC LIMIT 5
");
$appStmt->execute([$employerId]);
$applications = $appStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?>!</h2>

    <div class="row text-center mb-4">
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5>Total Jobs Posted</h5>
                <p class="display-6"><?= $jCount ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5>Applications Received</h5>
                <p class="display-6"><?= $appRec ?></p>
            </div>
        </div>
    </div>

    
    <div class="text-end mb-3">
        <a href="post_job.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Post a Job
        </a>
    </div>

    
    <h4>Your Jobs</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Location</th>
                <th>Posted At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($jobs): ?>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?= htmlspecialchars($job['title']) ?></td>
                        <td><?= htmlspecialchars($job['location']) ?></td>
                        <td><?= htmlspecialchars($job['posted_at']) ?></td>
                        <td><?= $job['is_active'] ? 'Active' : 'Closed' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No jobs posted yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <h4 class="mt-5">Recent Applications</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Applicant</th>
                <th>Applied At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($applications): ?>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?= htmlspecialchars($app['title']) ?></td>
                        <td><?= htmlspecialchars($app['first_name'] . " " . $app['last_name']) ?></td>
                        <td><?= htmlspecialchars($app['applied_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center">No applications received yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</body>
</html>

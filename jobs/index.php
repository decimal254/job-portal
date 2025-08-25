<?php
session_start();
require_once '../config/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$employer_id = $_SESSION['user_id'];


$sql = "SELECT ja.application_id, ja.status, ja.applied_at,
               j.title AS job_title,
               CONCAT(u.first_name, ' ', u.last_name) AS applicant_name,
               u.email AS applicant_email
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.job_id
        JOIN users u ON ja.user_id = u.user_id
        WHERE j.employer_id = ?
        ORDER BY ja.applied_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$employer_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Job Applications</h2>
    </div>

    <?php if (empty($applications)): ?>
        <div class="alert alert-info">No applications found for your jobs.</div>
    <?php else: ?>
        <div class="table-responsive shadow-sm rounded-4">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Job Title</th>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Date Applied</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?= htmlspecialchars($app['job_title']) ?></td>
                            <td><?= htmlspecialchars($app['applicant_name']) ?></td>
                            <td><?= htmlspecialchars($app['applicant_email']) ?></td>
                            <td>
                                <?php if ($app['status'] === 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($app['status'] === 'shortlisted'): ?>
                                    <span class="badge bg-info text-dark">Shortlisted</span>
                                <?php elseif ($app['status'] === 'rejected'): ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php elseif ($app['status'] === 'hired'): ?>
                                    <span class="badge bg-success">Hired</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($app['status']) ?></span>
                                <?php endif; ?>
                            
                            <td>
                                <a href="view.php?id=<?= $app['application_id'] ?>" class="btn btn-sm btn-primary rounded-pill">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

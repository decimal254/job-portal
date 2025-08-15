<?php
session_start();
require_once '../config/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$employer_id = $_SESSION['user_id'];


$sql = "SELECT ja.application_id, 
               j.title AS job_title, 
               CONCAT(u.first_name, ' ', u.last_name) AS applicant_name,
               ja.status,
               ja.applied_at
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
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="text-primary mb-4">Job Applications</h3>

            <?php if (empty($applications)): ?>
                <div class="alert alert-info text-center rounded-3">
                    No applications found for your jobs.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Job Title</th>
                                <th>Applicant</th>
                                <th>Status</th>
                                <th>Date Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td><?= htmlspecialchars($app['job_title']) ?></td>
                                    <td><?= htmlspecialchars($app['applicant_name']) ?></td>
                                    <td>
                                        <?php
                                            $badgeClass = match($app['status']) {
                                                'pending' => 'bg-warning text-dark',
                                                'shortlisted' => 'bg-info text-dark',
                                                'rejected' => 'bg-danger',
                                                'hired' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($app['status']) ?></span>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>

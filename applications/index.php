<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
    SELECT a.application_id, a.applied_at, a.status,
            j.title AS job_title,
            u.first_name, u.last_name
    FROM applications a
    jOIN jobs j ON a.job_id = j.job_id
    JOIN users u ON a.user_id = u.user_id
    ORDER BY a.applied_at DESC
    ");
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("DB error on applications index: " . $e->getMessage());
    $applications = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Job Portal</a>
        </div>
    </nav>
    
    <div class="container py-5">
        <h2 class ="mb-4">Job Applications</h2>
        <form action="serach.php" method="get" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="job" class="form-control" placeholder="job-title">
            </div>
            <div class="col-md-4">
                <input type="text" name="Applicant" class="form-control" placeholder="Applicant name">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="shortlisted">Shortlisted</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <?php if (!empty($applications)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Job Title</th>
                            <th>Applicant</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Action</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?=htmlspecialchars($app['job_title'])?></td>
                            <td><?=htmlspecialchars($app['first_name'])?></td>
                            <td><?=ucfirst($app['status'])?></td>
                            <td><a href="view.php?id=<?= urlencode($app['application_id']) ?>" class="btn btn-sm btn-info">View</a></td>
                        </tr>
                       <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-info">No applications found.</div>
            <?php endif;?>
    </div>
</body>
</html>
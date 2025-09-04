<?php
session_start();
require_once '../config/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$role    = $_SESSION['role'] ?? null;

if (!$user_id) {
    header("Location: ../auth/login.php");
    exit;
}

$applications = [];

try {
    if ($role === 'jobseeker') {
        // Applications submitted BY this jobseeker
        $sql = "SELECT ja.application_id, ja.applied_at, ja.status, 
                       j.title, j.location
                FROM job_applications ja
                JOIN jobs j ON ja.job_id = j.job_id
                WHERE ja.user_id = ?
                ORDER BY ja.applied_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } elseif ($role === 'employer') {
        // Applications submitted TO this employer’s jobs
        $sql = "SELECT ja.application_id, ja.applied_at, ja.status,
                       u.first_name, u.last_name, j.title
                FROM job_applications ja
                JOIN jobs j ON ja.job_id = j.job_id
                JOIN users u ON ja.user_id = u.user_id
                WHERE j.employer_id = ?
                ORDER BY ja.applied_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Error fetching applications: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">
        <?php echo ($role === 'jobseeker') ? "My Applications" : "Applications to My Jobs"; ?>
    </h2>

    <?php if (empty($applications)): ?>
        <div class="alert alert-info">
            <?php echo ($role === 'jobseeker') 
                ? "You have not applied to any jobs yet." 
                : "No one has applied to your jobs yet."; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <?php if ($role === 'jobseeker'): ?>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Action</th>
                    <?php else: ?>
                        <th>Applicant Name</th>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <?php if ($role === 'jobseeker'): ?>
                            <td><?= htmlspecialchars($app['title']) ?></td>
                            <td><?= htmlspecialchars($app['location']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($app['status']) ?></span></td>
                            <td><?= htmlspecialchars($app['applied_at']) ?></td>
                            <td>
                                <a href="view_employer.php?id=<?= $app['application_id'] ?>" 
                                   class="btn btn-info btn-sm">View</a>
                            </td>
                        <?php else: ?>
                            <td><?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?></td>
                            <td><?= htmlspecialchars($app['title']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($app['status']) ?></span></td>
                            <td><?= htmlspecialchars($app['applied_at']) ?></td>
                            <td>
                                <a href="view_employer.php?id=<?= $app['application_id'] ?>" 
                                   class="btn btn-info btn-sm">View</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

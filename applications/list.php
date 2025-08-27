<?php
require_once "../config/db.php";


$stmt = $pdo->query("
    SELECT 
        ja.application_id,
        ja.applied_at,
        ja.status,
        ja.cover_letter,
        ja.resume_link,
        j.title AS job_title,
        u.first_name,
        u.last_name,
        u.email
    FROM job_applications ja
    JOIN jobs j ON ja.job_id = j.job_id
    JOIN users u ON ja.user_id = u.user_id
    ORDER BY ja.applied_at DESC
");

$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Applications List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>All Applications</h2>

    <?php if (count($applications) > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Job Title</th>
                <th>Applicant</th>
                <th>Email</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Resume</th>
            </tr>
            <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?= $app['application_id'] ?></td>
                    <td><?= htmlspecialchars($app['job_title']) ?></td>
                    <td><?= htmlspecialchars($app['first_name'] . " " . $app['last_name']) ?></td>
                    <td><?= htmlspecialchars($app['email']) ?></td>
                    <td><?= ucfirst($app['status']) ?></td>
                    <td><?= $app['applied_at'] ?></td>
                    <td>
                        <?php if (!empty($app['resume_link'])): ?>
                            <a href="<?= $app['resume_link'] ?>" target="_blank">View Resume</a>
                        <?php else: ?>
                          
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No applications found.</p>
    <?php endif; ?>
</body>
</html>

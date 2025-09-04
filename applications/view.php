<?php

session_start();


require_once '../config/db.php';


$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../auth/login.php");
    exit;
}


$application_id = (int) ($_GET['id'] ?? 0);

$app = null; 
$error = ''; 


if ($application_id === 0) {
    $error = "No application selected. Please go back to the list.";
} else {
    try {
        
        $sql = "SELECT ja.*, j.title AS job_title, u.first_name, u.last_name
                FROM job_applications ja
                JOIN jobs j ON ja.job_id = j.job_id
                JOIN users u ON ja.user_id = u.user_id
                WHERE ja.application_id = ? AND ja.user_id = ?";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([$application_id, $user_id]);
        $app = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if (!$app) {
            $error = "Application not found or you are not authorized to view it.";
        }

    } catch (PDOException $e) {
        
        $error = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <?php if (!empty($error)): ?>
                
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
                <a href="list.php" class="btn btn-secondary mt-3">Back to Applications</a>
            <?php else: ?>
                
                <div class="card p-4">
                    <h2 class="mb-4">Application Details</h2>
                    
                    <p><strong>Job:</strong> <?= htmlspecialchars($app['job_title']) ?></p>
                    <p><strong>Submitted:</strong> <?= date('M d, Y', strtotime($app['applied_at'])) ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-secondary"><?= ucfirst($app['status']) ?></span></p>

                    <h4 class="mt-4">Cover Letter:</h4>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($app['cover_letter'] ?? '')) ?></p>
                    
                    <?php if (!empty($app['resume_link'])): ?>
                        <p class="mt-4">
                            <strong>Resume:</strong> 
                            <a href="<?= htmlspecialchars($app['resume_link']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">View Resume</a>
                        </p>
                    <?php endif; ?>
                </div>

                <a href="list.php" class="btn btn-secondary mt-3">Back to Applications</a>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

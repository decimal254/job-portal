<?php
session_start();
require_once 'config/db.php';

try {
    
    $stmt = $pdo->prepare("
        SELECT job_id, title, location, category, salary_range, job_type, posted_at
        FROM jobs
        WHERE is_active = 1
        ORDER BY posted_at DESC
        LIMIT 6
    ");
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    
    error_log("DB error on homepage featured jobs: " . $e->getMessage());
    $jobs = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Jobportal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"> <a class="nav-link" href="jobs/index.php">Find jobs</a></li>
                <li class="nav-item"> <a class="nav-link" href="auth/login.php">Login</a></li>
                <li class="nav-item"> <a class="nav-link" href="auth/register_jobseeker.php">Sign up</a></li>
            </ul>
        </div>
    </div>
</nav>

<form action="applications/search.php" method="get" class="row g-2">
  <div class="col-md-5">
    <input type="text" name="title" class="form-control" placeholder="Job title or keyword">
  </div>
  <div class="col-md-5">
    <input type="text" name="location" class="form-control" placeholder="Location">
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-primary w-100">Search</button>
  </div>
</form>


<section id="featured-jobs" class="py-5">
    <div class="container">
        <h2 class="mb-4">Featured jobs</h2>

        <?php if (!empty($jobs)): ?>
            <div class="row">
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                                <p class="text-muted mb-1"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                                <p class="mb-1"><strong>Category:</strong> <?= htmlspecialchars($job['category'] ?? '—') ?></p>
                                <p class="mb-1"><strong>Salary:</strong> <?= htmlspecialchars($job['salary_range'] ?? '—') ?></p>
                                <p class="mb-2"><strong>Type:</strong> <?= ucfirst(str_replace('_', ' ', $job['job_type'])) ?></p>
                                <p class="small text-muted mt-auto">Posted on <?= date("F j, Y", strtotime($job['posted_at'])) ?></p>
                                <a href="jobs/view.php?id=<?= urlencode($job['job_id']) ?>" class="btn btn-primary mt-2">View Job</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No featured jobs found.</div>
        <?php endif; ?>
    </div>
</section>

<footer class="bg-dark text-white text-center py-3">
    &copy; <?= date('Y') ?> Jobportal. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

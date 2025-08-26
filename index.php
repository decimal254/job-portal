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
    <title>Job Portal - Find Your Next Job</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .hero {
            height: 65vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero-overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute; top:0; left:0; right:0; bottom:0;
            bottom: 0;
        
        }
        .hero-content {
            position: relative; z-index: 2;
        }
        .category-card {
            transition: 0.3s;
            cursor: pointer;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .job-card { min-height: 280px; }
    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">JobPortal</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="jobs/index.php" class="nav-link">Find Jobs</a></li>
                <li class="nav-item"><a href="auth/login.php" class="nav-link">Login</a></li>
                <li class="nav-item"><a href="auth/register_jobseeker.php" class="btn btn-primary ms-2">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>


<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1 class="display-5 fw-bold">Find Your Dream Job Today</h1>
        <p class="lead mb-4">Search thousands of job listings across all industries</p>
        <form action="applications/search.php" method="get" class="row g-2 justify-content-center">
            <div class="col-md-4">
                <input type="text" name="title" class="form-control form-control-lg" placeholder="Job title or keyword">
            </div>
            <div class="col-md-3">
                <input type="text" name="location" class="form-control form-control-lg" placeholder="Location">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-lg btn-primary w-100">Search</button>
            </div>
        </form>
    </div>
</section>


<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Explore Popular Categories</h2>
        <div class="row g-4">
            <?php 
            $categories = ["IT & Software", "Healthcare", "Finance", "Education", "Engineering", "Marketing"];
            foreach ($categories as $cat): ?>
                <div class="col-md-4 col-lg-2">
                    <div class="card category-card text-center p-3 shadow-sm h-100">
                        <h6 class="fw-bold"><?= htmlspecialchars($cat) ?></h6>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<section class="bg-light py-5">
    <div class="container">
        <h2 class="mb-4">Featured Jobs</h2>
        <div class="row g-4">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-4">
                        <div class="card job-card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                                <p class="text-muted mb-1"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                                <p class="mb-1"><strong>Category:</strong> <?= htmlspecialchars($job['category'] ?? '—') ?></p>
                                <p class="mb-1"><strong>Salary:</strong> <?= htmlspecialchars($job['salary_range'] ?? '—') ?></p>
                                <p class="mb-1"><strong>Type:</strong> <?= ucfirst(str_replace('_', ' ', $job['job_type'])) ?></p>
                                <a href="jobs/view.php?id=<?= urlencode($job['job_id']) ?>" class="btn btn-outline-primary mt-2">View Job</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No jobs available right now.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="py-5 text-center bg-primary text-white">
    <div class="container">
        <h2 class="mb-3">Are You an Employer?</h2>
        <p class="lead mb-4">Post your job and find the best candidates today.</p>
        <a href="jobs/create.php" class="btn btn-light btn-lg">Post a Job</a>
    </div>
</section>


<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p>&copy; <?= date('Y') ?> JobPortal. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

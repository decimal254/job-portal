<?php
session_start();
require_once '../config/db.php';

$title = isset($_GET['title']) ? trim($_GET['title']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$sql = "SELECT j.job_id, j.title, j.location, j.category, j.salary_range, j.description, 
               u.first_name, u.last_name, u.position 
        FROM jobs j
        JOIN users u ON j.employer_id = u.user_id
        WHERE 1=1";

$params = [];


if (!empty($title)) {
    $sql .= " AND (j.title LIKE ? OR j.description LIKE ? OR j.category LIKE ?)";
    $params[] = "%$title%";
    $params[] = "%$title%";
    $params[] = "%$title%";
}


if (!empty($location)) {
    $sql .= " AND j.location LIKE ?";
    $params[] = "%$location%";
}


if (!empty($category)) {
    $sql .= " AND j.category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4">Search Results</h2>

    
    <form action="search.php" method="get" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" class="form-control" placeholder="Job title or keyword">
        </div>
        <div class="col-md-4">
            <input type="text" name="location" value="<?= htmlspecialchars($location) ?>" class="form-control" placeholder="Location">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="IT & Software" <?= $category === "IT & Software" ? "selected" : "" ?>>IT & Software</option>
                <option value="Healthcare" <?= $category === "Healthcare" ? "selected" : "" ?>>Healthcare</option>
                <option value="Finance" <?= $category === "Finance" ? "selected" : "" ?>>Finance</option>
                <option value="Education" <?= $category === "Education" ? "selected" : "" ?>>Education</option>
                <option value="Engineering" <?= $category === "Engineering" ? "selected" : "" ?>>Engineering</option>
                <option value="Marketing" <?= $category === "Marketing" ? "selected" : "" ?>>Marketing</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Go</button>
        </div>
    </form>

    
    <?php if (count($jobs) > 0): ?>
        <?php foreach ($jobs as $job): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= htmlspecialchars($job['title']) ?></h5>
                    <p class="mb-1"><strong>Company:</strong> <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?></p>
                    <p class="mb-1"><strong>Position:</strong> <?= htmlspecialchars($job['position']) ?></p>
                    <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                    <p class="mb-1"><strong>Category:</strong> <?= htmlspecialchars($job['category']) ?></p>
                    <p class="mb-2"><strong>Salary:</strong> <?= htmlspecialchars($job['salary_range']) ?></p>
                    <p class="text-muted"><?= substr(htmlspecialchars($job['description']), 0, 100) ?>...</p>
                    <a href="../jobs/view.php?id=<?= $job['job_id'] ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">No jobs found matching your search.</div>
    <?php endif; ?>
    <div class="mt-4">
        <a href="../jobs/index.php" class="btn btn-secondary">Back to jobs</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

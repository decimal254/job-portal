<?php
session_start();
require_once '../config/db.php';

$title = $_GET['title'] ?? '';
$location = $_GET['location'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "
    SELECT job_id, title, location, category, job_type, posted_at
    FROM jobs
    WHERE is_active = 1
";

$params = [];


if (!empty($title)) {
    $sql .= " AND title LIKE :title";
    $params[':title'] = "%$title%";
}

if (!empty($location)) {
    $sql .= " AND location LIKE :location";
    $params[':location'] = "%$location%";
}

if (!empty($category)) {
    $sql .= " AND category = :category";
    $params[':category'] = $category;
}

$sql .= " ORDER BY posted_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $jobs = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Listings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">
  <h2 class="mb-4 text-center">Job Listings</h2>

 
  <form action="index.php" method="get" class="row g-2 mb-4">
    <div class="col-md-4">
      <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" 
             class="form-control" placeholder="Job title or keyword">
    </div>
    <div class="col-md-3">
      <input type="text" name="location" value="<?= htmlspecialchars($location) ?>" 
             class="form-control" placeholder="Location">
    </div>
    <div class="col-md-3">
      <input type="text" name="category" value="<?= htmlspecialchars($category) ?>" 
             class="form-control" placeholder="Category">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
  </form>

  <div class="row g-4">
    <?php if (!empty($jobs)): ?>
      <?php foreach ($jobs as $job): ?>
        <div class="col-md-4">
          <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
              <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
              <p class="mb-1"><strong>Category:</strong> <?= htmlspecialchars($job['category']) ?></p>
              <p class="mb-1"><strong>Type:</strong> <?= ucfirst(str_replace('_',' ', $job['job_type'])) ?></p>
              <small class="text-muted">Posted: <?= date('M d, Y', strtotime($job['posted_at'])) ?></small>
              <a href="view.php?id=<?= $job['job_id'] ?>" class="btn btn-outline-primary mt-auto">View Job</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No jobs found for your search.</p>
    <?php endif; ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

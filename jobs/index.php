<?php
require_once '../config/db.php';


$limit = 10; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;


$categoryFilter = isset($_GET['category']) && $_GET['category'] != '' ? $_GET['category'] : null;

$sql = "SELECT jobs.job_id, jobs.title, jobs.description, jobs.location, jobs.category, 
               jobs.salary_range, jobs.job_type, jobs.posted_at, jobs.is_active
        FROM jobs
        WHERE jobs.is_active = 1";

$params = [];

if ($categoryFilter) {
    $sql .= " AND jobs.category = ?";
    $params[] = $categoryFilter;
}

$sql .= " ORDER BY jobs.posted_at DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching jobs: " . $e->getMessage());
}


try {
    $categoryStmt = $pdo->query("SELECT DISTINCT category FROM jobs WHERE category IS NOT NULL AND category != ''");
    $categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categories = [];
}


$countSql = "SELECT COUNT(*) FROM jobs WHERE is_active = 1";
$countParams = [];

if ($categoryFilter) {
    $countSql .= " AND category = ?";
    $countParams[] = $categoryFilter;
}

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);
$totalJobs = $countStmt->fetchColumn();
$totalPages = ceil($totalJobs / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jobs - BrighterMonday Style</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4">Find Your Next Job</h2>

    
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $categoryFilter == $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    
    <div class="list-group shadow-sm">
        <?php if (count($jobs) > 0): ?>
            <?php foreach ($jobs as $job): ?>
                <a href="view.php?id=<?= $job['job_id'] ?>" class="list-group-item list-group-item-action">
                    <h5 class="mb-1"><?= htmlspecialchars($job['title']) ?></h5>
                    <p class="mb-1 text-muted">
                        <?= htmlspecialchars($job['location']) ?> | <?= ucfirst($job['job_type']) ?> 
                        <?php if ($job['salary_range']): ?>| <?= htmlspecialchars($job['salary_range']) ?><?php endif; ?>
                    </p>
                    <small>Posted on <?= date("M d, Y", strtotime($job['posted_at'])) ?></small>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item">No jobs found.</div>
        <?php endif; ?>
    </div>

    
    <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&category=<?= urlencode($categoryFilter) ?>">Previous</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&category=<?= urlencode($categoryFilter) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&category=<?= urlencode($categoryFilter) ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>

</div>

</body>
</html>

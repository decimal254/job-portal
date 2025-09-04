<?php
session_start();
require_once '../config/db.php';


$title = trim($_GET['title'] ?? '');
$location = trim($_GET['location'] ?? '');
$category = trim($_GET['category'] ?? '');


try {
    $sql = "SELECT job_id, title, location, category, salary_range, job_type, posted_at
            FROM jobs
            WHERE is_active = 1";
    $params = [];

    if ($title !== '') {
        $sql .= " AND title LIKE ?";
        $params[] = "%$title%";
    }
    if ($location !== '') {
        $sql .= " AND location LIKE ?";
        $params[] = "%$location%";
    }
    if ($category !== '') {
        $sql .= " AND category = ?";
        $params[] = $category;
    }

    $sql .= " ORDER BY posted_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("DB error on jobs list: " . $e->getMessage());
    $jobs = [];
}


$categories = ["IT & Software", "Healthcare", "Finance", "Education", "Engineering", "Marketing"];
?>

<?php include '../includes/header.php'; ?>


<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4 text-center">Find Jobs</h2>

        
        <form method="get" class="row g-2 justify-content-center mb-4">
            <div class="col-md-4">
                <input type="text" name="title" class="form-control" placeholder="Job title or keyword" value="<?= htmlspecialchars($title) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" name="location" class="form-control" placeholder="Location" value="<?= htmlspecialchars($location) ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $category ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
                                <p class="text-muted mb-1"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                                <p class="mb-1"><strong>Category:</strong> <?= htmlspecialchars($job['category'] ?? '—') ?></p>
                                <p class="mb-1"><strong>Salary:</strong> <?= htmlspecialchars($job['salary_range'] ?? '—') ?></p>
                                <p class="mb-1"><strong>Type:</strong> <?= ucfirst(str_replace('_', ' ', $job['job_type'])) ?></p>
                                <a href="../jobs/view.php?id=<?= urlencode($job['job_id']) ?>" class="btn btn-outline-primary mt-auto">View Job</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No jobs found for your search criteria.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

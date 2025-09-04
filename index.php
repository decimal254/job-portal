<?php
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

$categories = ["IT & Software", "Healthcare", "Finance", "Education", "Engineering", "Marketing"];
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="hero">
    <div class="container hero-content">
        <h1 class="display-5 fw-bold">Find Your Dream Job Today</h1>
        <p class="lead mb-4">Search thousands of job listings across all industries.</p>
        
        <form action="jobs/list.php" method="get" class="row g-2 justify-content-center">
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
        <h2 class="mb-4 text-center">Explore Popular Categories</h2>
        <div class="row g-4 justify-content-center">
            <?php foreach ($categories as $cat): ?>
                <div class="col-md-4 col-lg-2">
                    <a href="jobs/list.php?category=<?= urlencode($cat) ?>" class="text-decoration-none">
                        <div class="card category-card text-center p-3 shadow-sm h-100">
                            <h6 class="fw-bold text-dark"><?= htmlspecialchars($cat) ?></h6>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Featured Jobs</h2>
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
                                <a href="jobs/view.php?id=<?= urlencode($job['job_id']) ?>" class="btn btn-outline-primary mt-auto">View Job</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No jobs available right now.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-5 text-center bg-secondary text-white">
    <div class="container">
        <h2 class="mb-3">Upload Your Resume</h2>
        <p class="lead mb-4">Let employers find you faster — upload your resume today and get matched with the best opportunities.</p>
        <a href="profile/upload_resume.php" class="btn btn-light btn-lg">Upload Resume</a>
    </div>
</section>

<section class="py-5 text-center bg-primary text-white">
    <div class="container">
        <h2 class="mb-3">Are You an Employer?</h2>
        <p class="lead mb-4">Post your job and find the best candidates today.</p>
        <a href="jobs/create.php" class="btn btn-light btn-lg">Post a Job</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<?php
session_start();
require_once 'config/db.php';


try {
    $stmt = $pdo->prepare("
        SELECT job_id, title, location, category, job_type, posted_at
        FROM jobs
        WHERE is_active = 1
        ORDER BY posted_at DESC
        LIMIT 6
    ");
    $stmt->execute();
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
  <title>Job Portal - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'includes/navbar.php'; ?>


<section class="bg-primary text-white text-center py-5">
  <div class="container">
    <h1 class="display-4 fw-bold">Find Your Dream Job</h1>
    <p class="lead mb-4">Search and apply to thousands of job listings.</p>

    <form action="jobs/index.php" method="get" class="row g-2 justify-content-center">
      <div class="col-md-4">
        <input type="text" name="title" class="form-control form-control-lg" placeholder="Job title or keyword">
      </div>
      <div class="col-md-3">
        <input type="text" name="location" class="form-control form-control-lg" placeholder="Location">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-light btn-lg w-100">Search</button>
      </div>
    </form>
  </div>
</section>


<section class="container py-5">
  <h2 class="text-center mb-4">Explore Popular Categories</h2>
  <div class="row g-3 text-center">
    <div class="col-6 col-md-3"><div class="border p-3">IT & Software</div></div>
    <div class="col-6 col-md-3"><div class="border p-3">Healthcare</div></div>
    <div class="col-6 col-md-3"><div class="border p-3">Education</div></div>
    <div class="col-6 col-md-3"><div class="border p-3">Engineering</div></div>
    <div class="col-6 col-md-3"><div class="border p-3">Marketing</div></div>
    <div class="col-6 col-md-3"><div class="border p-3">Finance</div></div>
    <div class="col-6 col-md-3"><div class="border p-3">Design</div></div>
    <div class="col-6 col-md-3"><div class="border p-3">Customer Service</div></div>
  </div>
</section>


<section class="container py-5">
  <h2 class="mb-4 text-center">Latest Jobs</h2>
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
              <a href="jobs/view.php?id=<?= $job['job_id'] ?>" class="btn btn-outline-primary mt-auto">View Job</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No jobs available right now.</p>
    <?php endif; ?>
  </div>
</section>


<section class="bg-light py-5 text-center">
  <div class="container">
    <h2 class="mb-4">Get Started</h2>
    <div class="d-flex flex-wrap justify-content-center">
     
      <a href="jobs/index.php" class="btn btn-outline-primary btn-lg m-2">Find Jobs</a>

      
      <a href="dashboard/jobseeker.php#uploadCV" class="btn btn-outline-success btn-lg m-2">Upload Your CV</a>
      <a href="auth/register_jobseeker.php" class="btn btn-success btn-lg m-2">Register as Jobseeker</a>

     
      <a href="jobs/create.php" class="btn btn-outline-warning btn-lg m-2">Post a Job</a>
      <a href="auth/register_employer.php" class="btn btn-warning btn-lg m-2">Register as Employer</a>

     
      <a href="auth/login.php" class="btn btn-outline-dark btn-lg m-2">Login</a>
    </div>
  </div>
</section>


<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

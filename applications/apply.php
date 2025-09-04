<?php
session_start();
require_once '../config/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../auth/login.php");
    exit;
}

$job_id = $_GET['job_id'] ?? null;
if (!$job_id) {
    $error = "No job selected. Please go back to the job listings.";
    $job_title = null;
} else {
    try {
        $sql = "SELECT title FROM jobs WHERE job_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$job_id]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($job) {
            $job_title = $job['title'];
            $error = null;
        } else {
            $error = "Job not found. Please go back to the job listings.";
            $job_title = null;
        }
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
        $job_title = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="mb-4 text-center">Confirm Application</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <a href="../jobs/index.php" class="btn btn-secondary w-100">Go Back to Jobs</a>
                <?php else: ?>
                    <p class="lead text-center">You are about to apply for:</p>
                    <h4 class="mb-4 text-center text-primary"><?= htmlspecialchars($job_title) ?></h4>

                    <form action="submit_applications.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="job_id" value="<?= htmlspecialchars($job_id) ?>">
                        <div class="mb-3">
                            <label for="cv" class="form-label">Upload CV/Resume (PDF, DOC, DOCX)</label>
                            <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                Confirm and Apply
                            </button>
                            <a href="../jobs/index.php" class="btn btn-link mt-2 text-secondary">Cancel</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>

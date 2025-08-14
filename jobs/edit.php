<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}

$job_id = $_GET['id'] ?? null;
if (!$job_id) {
    header("Location: index.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = ? AND employer_id = ?");
$stmt->execute([$job_id, $_SESSION['user_id']]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die("Job not found or you do not have permission to edit this job.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $job_type = trim($_POST['job_type']);

    if (empty($title) || empty($description)) {
        $error = "Title and description are required.";
    } else {
        $update = $pdo->prepare("UPDATE jobs 
            SET title=?, location=?, description=?, category=?, job_type=? 
            WHERE job_id=? AND employer_id=?");
        $update->execute([$title, $location, $description, $category, $job_type, $job_id, $_SESSION['user_id']]);
        $success = "Job updated successfully!";
        
        
        $stmt->execute([$job_id, $_SESSION['user_id']]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="mb-4 text-primary fw-bold">Edit Job Post</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($job['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($job['location']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Job Description</label>
                    <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($job['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="IT" <?= $job['category'] === 'IT' ? 'selected' : '' ?>>IT</option>
                        <option value="Finance" <?= $job['category'] === 'Finance' ? 'selected' : '' ?>>Finance</option>
                        <option value="Marketing" <?= $job['category'] === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                        <option value="HR" <?= $job['category'] === 'HR' ? 'selected' : '' ?>>HR</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Job Type</label>
                    <select name="job_type" class="form-select">
                        <option value="Full-time" <?= $job['job_type'] === 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                        <option value="Part-time" <?= $job['job_type'] === 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                        <option value="Contract" <?= $job['job_type'] === 'Contract' ? 'selected' : '' ?>>Contract</option>
                        <option value="Internship" <?= $job['job_type'] === 'Internship' ? 'selected' : '' ?>>Internship</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary rounded-3">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-3">Update Job</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

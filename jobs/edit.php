<?php
session_start();
require_once '../config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$job_id = (int) $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = ? AND employer_id = ?");
$stmt->execute([$job_id, $_SESSION['user_id']]);
$job = $stmt->fetch();

if (!$job) {
    
    echo "<h3 style='color:red;text-align:center;margin-top:50px;'>Access Denied - You cannot edit this job.</h3>";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    $update_stmt = $pdo->prepare("UPDATE jobs SET title = ?, description = ?, location = ? WHERE job_id = ?");
    $update_stmt->execute([$title, $description, $location, $job_id]);

    header("Location: index.php?success=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Job</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Job Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($job['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Job Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($job['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($job['location']) ?>" required>
        </div>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-secondary rounded-3">Cancel</a>
            <button type="submit" class="btn btn-primary rounded-3">Update Job</button>
        </div>
    </form>
</div>
</body>
</html>

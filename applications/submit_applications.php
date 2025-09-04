<?php
session_start();
require_once '../config/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'] ?? null;

    if (!$job_id) {
        header("Location: list.php?error=missing_job");
        exit;
    }

    
    $checkSql = "SELECT application_id FROM job_applications WHERE user_id = ? AND job_id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$user_id, $job_id]);

    if ($checkStmt->fetch()) {
        header("Location: list.php?error=already_applied");
        exit;
    }

    
    $cv = $_FILES['cv'] ?? null;
    $cv_path = null;

    if ($cv && $cv['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/cvs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = "cv_" . $user_id . "_" . time() . "_" . basename($cv['name']);
        $filePath = $uploadDir . $fileName;
        $dbPath   = "uploads/cvs/" . $fileName;

        if (move_uploaded_file($cv['tmp_name'], $filePath)) {
            $cv_path = $dbPath;
        }
    }

    
    try {
        $sql = "INSERT INTO job_applications (job_id, user_id, applied_at, status, cv_path) 
                VALUES (?, ?, NOW(), 'pending', ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$job_id, $user_id, $cv_path]);

        header("Location: list.php?success=applied");
        exit;
    } catch (PDOException $e) {
        die("Error saving application: " . $e->getMessage());
    }
} else {
    header("Location: ../jobs/index.php");
    exit;
}

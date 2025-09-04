<?php
session_start();
require_once '../config/db.php';


if (!isset($_SESSION['user_id'])) {
    header("location: ../auth/login.php?error=login_required");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cv'])) {
    $allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    $file = $_FILES['cv'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        if (in_array($file['type'], $allowedTypes)) {
            
            $uploadDir = __DIR__ . '/../uploads/cvs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            
            $fileName = "cv_" . $user_id . "_" . time() . "_" . basename($file['name']);
            $filepath = $uploadDir . $fileName;
            $dbpath   = "uploads/cvs/" . $fileName; 

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                
                $stmt = $pdo->prepare("UPDATE job_applications SET cv_path = ? WHERE user_id = ?");
                $stmt->execute([$dbpath, $user_id]);

                $message = "<div class='alert alert-success'>Your resume has been uploaded successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error uploading file.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Only PDF, DOC, or DOCX files are allowed.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Please select a valid file.</div>";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 text-center">Upload Your Resume</h2>
    <?= $message ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="" method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
                <div class="mb-3">
                    <label for="cv" class="form-label">Choose Resume (PDF, DOC, DOCX)</label>
                    <input type="file" name="cv" id="cv" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Upload Resume</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

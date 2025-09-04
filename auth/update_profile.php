<?php
session_start();
require_once '../config/db.php';


if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'jobseeker') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";


$stmt = $pdo->prepare("SELECT first_name, last_name, email, mobile_number, position, cv_path FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name    = $_POST['first_name'] ?? '';
    $last_name     = $_POST['last_name'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $position      = $_POST['position'] ?? '';

    
    $cv_path = $user['cv_path'];
    if (!empty($_FILES['cv']['name'])) {
        $uploadDir = "../uploads/cv/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['cv']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['cv']['tmp_name'], $targetFile)) {
            $cv_path = "uploads/cv/" . $fileName;
        } else {
            $message = "Error uploading CV.";
        }
    }

    
    $update = $pdo->prepare("
        UPDATE users 
        SET first_name = ?, last_name = ?, mobile_number = ?, position = ?, cv_path = ?
        WHERE user_id = ?
    ");
    $update->execute([$first_name, $last_name, $mobile_number, $position, $cv_path, $user_id]);

    $message = "Profile updated successfully!";
    $user['first_name'] = $first_name;
    $user['last_name'] = $last_name;
    $user['mobile_number'] = $mobile_number;
    $user['position'] = $position;
    $user['cv_path'] = $cv_path;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">Update Profile</h2>
  
  <?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">First Name</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Last Name</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Mobile Number</label>
      <input type="text" name="mobile_number" value="<?= htmlspecialchars($user['mobile_number']) ?>" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Position</label>
      <input type="text" name="position" value="<?= htmlspecialchars($user['position']) ?>" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Upload CV</label>
      <input type="file" name="cv" class="form-control">
      <?php if (!empty($user['cv_path'])): ?>
        <p class="mt-2">Current CV: <a href="../<?= htmlspecialchars($user['cv_path']) ?>" target="_blank">View CV</a></p>
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="../dashboard/jobseeker.php" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>

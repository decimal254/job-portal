<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];

                
                if ($user['role'] === 'jobseeker') {
                    header("Location: ../dashboard/jobseeker.php");
                } elseif ($user['role'] === 'employer') {
                    header("Location: ../dashboard/employer.php");
                } elseif ($user['role'] === 'admin') {
                    header("Location: ../dashboard/admin.php");
                }
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow p-4">
        <h3 class="text-center mb-4">Login</h3>
        <?php if (!empty($error)) { ?>
          <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>

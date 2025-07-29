<?php
session_start();
require_once '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        $message = "<p class='success'>Login successful! Welcome, " . htmlspecialchars($user['role']) . "</p>";
    } else {
        $message = "<p class='error'> Invalid email or password.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="form-box">
    <h2>Login</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" required>
      <input type="password" name="password" placeholder="Enter your password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>

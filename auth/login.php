<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("location: ../" . $user['role'] . "/dashboard.php");
        exit;
    } else {
        echo "Invalid email or password.";
    }
}
?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required/>
    <input type="password" name="password" placeholder="Placeholder" required/>
    <button type="submit">Login</button>
</form>
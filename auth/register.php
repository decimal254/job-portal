<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if (!empty($email) && !empty($password) && !empty($role)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$email, $password, $role]);

            echo "Registration successful as <strong>$role</strong></p>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo  "All fields are required.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Enter your password" required>

    <select name="role" required>
        <option value="jobseeker">Job Seeker</option>
        <option value="employer">Employer</option>
    </select><br><br>

    <button type="submit">Register</button>
</form>
    
</body>
</html>

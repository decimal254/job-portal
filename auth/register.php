<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; 
    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$email, $password, $role]);

    echo "Registration successful!";
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required /><br>
    <input type="password" name="password" placeholder="Password" required /><br>
    <select name="role">
        <option value="jobseeker">Job Seeker</option>
        <option value="employer">Employer</option>
    </select><br>
    <button type="submit">Register</button>
</form>

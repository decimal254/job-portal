<?php
$host = "localhost"; 
$dbname = "kazicorn_job-portal";   
$username = "kazicorn_kazicorn";  
$password = "E745l8pzVd";    

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

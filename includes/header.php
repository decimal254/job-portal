<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css"> 
</head>
<body>
   <header>
        <nav>
            <a href="/index.php">Home</a> 
            <a href="/jobs/index.php">Jobs</a> 
            <a href="/auth/login.php">Login</a> 
            <a href="/auth/register.php">Sign Up</a>
        </nav>
    </header>


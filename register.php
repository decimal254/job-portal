<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow p-4 text-center">
        <h1 class="mb-3">Create Your Account</h1>
        <p class="text-muted">Please choose your registration type:</p>

        <a href="auth/register_jobseeker.php" class="btn btn-primary w-100 mb-3">
          Register as a Job Seeker
        </a>

        <a href="auth/register_employer.php" class="btn btn-success w-100 mb-3">
          Register as an Employer
        </a>

        <p class="mt-3">Already have an account? 
          <a href="auth/login.php">Login here</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

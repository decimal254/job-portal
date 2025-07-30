<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    $first       = $_POST['first_name'];
    $last        = $_POST['last_name'];
    $dob         = $_POST['dob'];
    $nationality = $_POST['nationality'];
    $code        = $_POST['country_code'];
    $location    = $_POST['location'];
    $gender      = $_POST['gender'];
    $mobile      = $_POST['mobile_number'];
    $email       = $_POST['email'];
    $password    = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role        = 'jobseeker';

    
    $qualification    = $_POST['qualification'];
    $experience       = $_POST['experience'];
    $current_function = $_POST['current_function'];
    $desired_function = $_POST['desired_function'];
    $availability     = $_POST['availability'];

    try {
        
        $stmt = $pdo->prepare("INSERT INTO users 
            (first_name, last_name, email, password, country_code, mobile_number, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first, $last, $email, $password, $code, $mobile, $role]);

        $user_id = $pdo->lastInsertId(); 

        
        $stmt2 = $pdo->prepare("INSERT INTO jobseekers 
            (user_id, dob, nationality, location, gender, qualification, experience, current_function, desired_function, availability) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([$user_id, $dob, $nationality, $location, $gender, $qualification, $experience, $current_function, $desired_function, $availability]);

        echo "<div class='alert alert-success text-center'>Registration successful as a Job Seeker!</div>";

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Seeker Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow p-4">
        <h2 class="text-center mb-4">Job Seeker Registration</h2>
        
        
        <form method="POST" action="register_jobseeker.php">
          
          
          <h4 class="mb-3 text-primary">Personal Information</h4>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" name="last_name" class="form-control" required>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" class="form-control" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Nationality</label>
            <select name="nationality" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option>Kenyan</option>
              <option>Ugandan</option>
              <option>American</option>
              <option>Chinese</option>
              <option>Swedish</option>
              <option>Armenian</option>
              <option>Albanian</option>
              <option>Algerian</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Country Code</label>
              <select name="country_code" class="form-select" required>
                <option value="" disabled selected>Code</option>
                <option value="+254">+254</option>
                <option value="+256">+256</option>
                <option value="+1">+1</option>
                <option value="+61">+61</option>
              </select>
            </div>
            <div class="col-md-8 mb-3">
              <label class="form-label">Mobile Number</label>
              <input type="text" name="mobile_number" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Current Location</label>
            <select name="location" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option value="Nairobi">Nairobi</option>
              <option value="Mombasa">Mombasa</option>
              <option value="Kisumu">Kisumu</option>
              <option value="Eldoret">Eldoret</option>
              <option value="Kiambu">Kiambu</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          
          <h4 class="mb-3 text-primary mt-4">Work Information</h4>
          <div class="mb-3">
            <label class="form-label">Highest Qualification</label>
            <select name="qualification" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option>High School</option>
              <option>Diploma</option>
              <option>Bachelor</option>
              <option>Master</option>
              <option>PhD</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Years of Experience</label>
            <select name="experience" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option value="0">0</option>
              <option value="1-2">1-2</option>
              <option value="3-5">3-5</option>
              <option value="6+">6+</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Current Job Function</label>
            <select name="current_function" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option>IT</option>
              <option>Finance</option>
              <option>Healthcare</option>
              <option>Engineering</option>
              <option>Education</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Desired Job Function</label>
            <select name="desired_function" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option>IT</option>
              <option>Finance</option>
              <option>Healthcare</option>
              <option>Engineering</option>
              <option>Education</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Availability</label>
            <select name="availability" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option>Immediate</option>
              <option>1 Month</option>
              <option>2 Months</option>
              <option>3+ Months</option>
            </select>
          </div>

          
          <button type="submit" class="btn btn-success w-100">Register</button>

        </form>
        
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

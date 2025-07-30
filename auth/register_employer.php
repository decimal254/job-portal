<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    $rep_first_name  = $_POST['rep_first_name'];
    $rep_last_name   = $_POST['rep_last_name'];
    $rep_email       = $_POST['rep_email'];
    $country_code    = $_POST['country_code'];
    $rep_phone       = $_POST['rep_phone'];
    $position        = $_POST['position'];
    $password        = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role            = 'employer';

    
    $company_name    = $_POST['company_name'];
    $company_location= $_POST['company_location'];
    $industry        = $_POST['industry'];
    $num_employees   = $_POST['num_employees'];
    $employee_type   = $_POST['employee_type'];
    $website         = $_POST['website'];
    $referral        = $_POST['referral'];
    $contact_person  = $_POST['contact_person'];
    $notification_email = $_POST['notification_email'];

    try {
        
        $stmt = $pdo->prepare("INSERT INTO users 
            (first_name, last_name, email, password, country_code, mobile_number, role, position) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$rep_first_name, $rep_last_name, $rep_email, $password, $country_code, $rep_phone, $role, $position]);

        $user_id = $pdo->lastInsertId();

        
        $stmt2 = $pdo->prepare("INSERT INTO employers 
            (user_id, position, company_name, company_location, industry, num_employees, employee_type, website, referral, contact_person, notification_email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([$user_id, $position, $company_name, $company_location, $industry, $num_employees, $employee_type, $website, $referral, $contact_person, $notification_email]);

        echo "<div class='alert alert-success text-center'>Employer Registration Successful!</div>";

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
  <title>Register Employer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow p-4">
        <h2 class="text-center mb-4">Employer Registration</h2>
        <form method="POST">
          
          
          <h4 class="mb-3 text-primary">Company Representative</h4>
          <p class="text-muted">This is information about you as a representative of the company.</p>

          <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="rep_first_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="rep_last_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Work Email</label>
            <input type="email" name="rep_email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <div class="input-group">
              <select name="country_code" class="form-select" style="max-width:120px;" required>
                <option value="+254">+254 KE</option>
                <option value="+256">+256 UG</option>
                <option value="+1">+1 USA</option>
                <option value="+44">+44 UK</option>
              </select>
              <input type="text" name="rep_phone" class="form-control" placeholder="Phone Number" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Position in Company</label>
            <input type="text" name="position" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          
          <h4 class="mb-3 text-primary mt-4">Company Information</h4>
          <p class="text-muted">This information pertains to your company.</p>

          <div class="mb-3">
            <label class="form-label">Company Name</label>
            <input type="text" name="company_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Company Location</label>
            <input type="text" name="company_location" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Industry</label>
            <select name="industry" class="form-select" required>
              <option value="" disabled selected>Select Industry</option>
              <option value="IT">Information Technology</option>
              <option value="Finance">Finance</option>
              <option value="Health">Health</option>
              <option value="Education">Education</option>
              <option value="Manufacturing">Manufacturing</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Number of Employees</label>
            <input type="number" name="num_employees" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Type of Employee Needed</label>
            <select name="employee_type" class="form-select" required>
              <option value="" disabled selected>Select...</option>
              <option value="Full-Time">Full-Time</option>
              <option value="Part-Time">Part-Time</option>
              <option value="Contract">Contract</option>
              <option value="Internship">Internship</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Website</label>
            <input type="url" name="website" class="form-control" placeholder="website">
          </div>

          <div class="mb-3">
            <label class="form-label">Where did you hear about us?</label>
            <input type="text" name="referral" class="form-control" placeholder="e.g., Google, Friend, LinkedIn">
          </div>

          <div class="mb-3">
            <label class="form-label">Contact Person</label>
            <input type="text" name="contact_person" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Notification Email</label>
            <input type="email" name="notification_email" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-success w-100">Register Employer</button>
        </form>
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

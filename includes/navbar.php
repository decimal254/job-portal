<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    
    <a class="navbar-brand fw-bold text-primary" href="/job-portal/index.php">JobPortal</a>

    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
    
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/job-portal/jobs/index.php">Find Jobs</a></li>
        <li class="nav-item"><a class="nav-link" href="/job-portal/applications/list.php">Applications</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
          <li class="nav-item"><a class="nav-link" href="/job-portal/jobs/create.php">Post a Job</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

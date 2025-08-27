<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Job Portal</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/job-portal/css/style.css" rel="stylesheet" />
</head>
<body>
  <header class="bg-white border-bottom shadow-sm">
    <div class="container d-flex align-items-center justify-content-between py-2">
      
      <a href="/job-portal/index.php" class="navbar-brand fw-bold text-primary"></a>
      
      <form method="get" action="/job-portal/Applications/search.php" class="d-flex w-50">
        <input class="form-control me-2" type="search" name="q" placeholder="Search applications..." />
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </form>
    </div>
  </header>
  <main class="container my-4">

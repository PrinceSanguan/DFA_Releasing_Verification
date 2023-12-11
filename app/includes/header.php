<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../../assets/images/DFA.png">
  <title>DFA Releasing Verification</title>

  <link rel="stylesheet" href="../../assets/css/header.css">
</head>
<body>

  <div class="banner">
    <img class="image-banner" src="../../assets/images/banner.png" alt="broken-image">
  </div>

  <header>
    <div class="container2">
      <ul class="navlist">
        <li id="verification"><a href="../../app/pages/verification.php">Verification</a></li>
        <li id="verification"><a href="../../app/pages/search.php">Search</a></li>
        <li id="monitoring"><a href="../../app/pages/monitoring.php">Monitoring</a></li>
        <li id="reports"><a href="../../app/pages/reports.php">Reports</a></li>
        <li id="tools"><a href="../../app/pages/tools.php">Tools</a></li>
        <li id="logout"><a href="../../app/pages/logout.php">LogOut</a></li>
      </ul>
    </div>
  </header>

  <!-- Display the user role as a floating element -->
  <div class="floating-element">
    <p>Welcome! <?php echo $userRole ?></p>
  </div>
  

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var navLinks = document.querySelectorAll('.navlist a');

    navLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        // Remove the 'active-glow' class from all links
        navLinks.forEach(function(link) {
          link.classList.remove('active-glow');
        });

        // Add the 'active-glow' class to the clicked link
        link.classList.add('active-glow');
      });
    });
  });
</script>
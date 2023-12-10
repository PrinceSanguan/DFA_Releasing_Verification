<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../images/DFA.png">
  <title>DFA Releasing Verification</title>
  <style>
    
/* This style is for Body */
    
* {
    text-decoration: none;
    padding: 0;
    margin: 0;
    list-style: none;
  }

  body {
    background-color: #e0e0e0; /* Light gray background */
    height: 100vh;
    margin: 0;
  }

/* This Style is for the Header */

.container2 {
    width: 100px;
    height: 100px;
    position: absolute;
    top: 10px;
    left: 10px;
    display: block;
  }

  li {
    color: white;
  }

  a {
    text-decoration: none;
    font-size: 20px;
    display: inline;
    font-weight: bolder;
    color: blue;
  }

  .navlist {
    display: flex;
  }

  .navlist a {
    display: inline-block;
    color: var(--text-color);
    padding: 10px 51px;
    margin-top: 118px;
    animation: slideAnimation 1s ease forwards;
    animation-delay: calc(0.3s * var(--i));
  }

  .navlist a:hover {
    color: var(--hover-color);
    text-shadow: 0 0 10px rgba(18, 247, 255, 0.6),
      0 0 20px rgba(18, 247, 255, 0.6), 0 0 30px rgba(18, 247, 255, 0.6),
      0 0 40px rgba(18, 247, 255, 0.6), 0 0 70px rgba(18, 247, 255, 0.6),
      0 0 80px rgba(18, 247, 255, 0.6), 0 0 100px rgba(18, 247, 255, 0.6),
      0 0 150px rgba(18, 247, 255, 0.6);
  }

  .navlist a.active {
    color: var(--hover-color);
  }

  header {
    top: 0;
    left: 0;
    z-index: 1000;
    border-bottom: 1px solid transparent;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 50%;
    background-color: #555;
    padding-bottom: 50px;
  }

 /* Style for Banner */

 .banner {
    background-color: #2b78e4;
  }

  .image-banner {
    margin-left: 130px;
    vertical-align: middle;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 0;
    max-width: 100%;
  }

  /* Styles for the floating element */
  .floating-element {
    position: absolute;
    background-color: #007bff; /* Background color */
    color: #fff; /* Text color */
    padding: 10px; /* Padding around the content */
    border-radius: 5px; /* Rounded corners */
    top: 130px; /* Adjust the top position as needed */
    right: 20px; /* Adjust the right position as needed */
    z-index: 999; /* Ensure it's above other elements */
  }

  /* it will add glow when the user click it */
  .navlist a.active-glow {
  color: var(--hover-color);
  box-shadow: 0 0 10px rgba(18, 247, 255, 0.6),
              0 0 20px rgba(18, 247, 255, 0.6),
              0 0 30px rgba(18, 247, 255, 0.6),
              0 0 40px rgba(18, 247, 255, 0.6),
              0 0 70px rgba(18, 247, 255, 0.6),
              0 0 80px rgba(18, 247, 255, 0.6),
              0 0 100px rgba(18, 247, 255, 0.6),
              0 0 150px rgba(18, 247, 255, 0.6);
}
  /* it will add glow when the user click it */

  </style>
</head>
<body>

  <div class="banner">
    <img class="image-banner" src="../images/banner.png" alt="broken-image">
  </div>

  <header>
    <div class="container2">
      <ul class="navlist">
        <li id="verification"><a href="../pages/verification.php">Verification</a></li>
        <li id="monitoring"><a href="../pages/monitoring.php">Monitoring</a></li>
        <li id="reports"><a href="../pages/reports.php">Reports</a></li>
        <li id="tools"><a href="../pages/tools.php">Tools</a></li>
        <li id="logout"><a href="../pages/logout.php">LogOut</a></li>
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
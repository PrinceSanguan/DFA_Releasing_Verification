<?php
session_start();

// Define the hard-coded usernames and passwords
$processor_username = 'processor';
$processor_password = '123';

$admin_username = 'admin';
$admin_password = '123';

$programmer_username = 'programmer';
$programmer_password = '123';

// Check if the user is attempting to log in
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify the user's credentials
    if (($username === $processor_username && $password === $processor_password) ||
        ($username === $admin_username && $password === $admin_password) || 
        ($username === $programmer_username && $password === $programmer_password)) {
        $_SESSION['user'] = $username;


    // If you want to Change the name you change here!
    // Store the user's role in the session
    if ($username === $processor_username) {
        $_SESSION['role'] = 'Processor';
    } elseif ($username === $admin_username) {
        $_SESSION['role'] = 'Philip De Luna';
    } elseif ($username === $programmer_username) {
        $_SESSION['role'] = 'Prince Sanguan';
    }
  }
}

// Redirect logged-in users to the appropriate page
if (isset($_SESSION['user'])) {
  if ($_SESSION['user'] === 'admin' || $_SESSION['user'] === 'processor') {
      header('Location: app/pages/verification.php');
  } elseif ($_SESSION['user'] === 'programmer') {
      header('Location: app/pages/verification.php'); // Change this to the appropriate Programmer page
  }
  exit();
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/images/DFA.png">
    <title>DFA Releasing Verification</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    

  </head>
  <body>
    <section>
      <div class="box">
        <div class="form">
          <img src="assets/images/DFA.png" class="user" alt="broken-image" />
          <h2>MABUHAY!</h2>
          <form class="" action="index.php" method="post" >
           
          <!-- Log in Page -->
            <div class="inputBx">
              <input type="text" name="username" placeholder="Username" id="username" oninput="validation()" required autofocus autocomplete="off"/>
              <img src="assets/images/user.png" alt="broken-image" />
            </div>

            <div class="inputBx">
              <input type="password" name="password" id="password" placeholder="Password" oninput="validation()" required />
              <img src="assets/images/lock.png" alt="broken-image" />
            </div>


            <div class="inputBx">
              <input type="submit" name="submit" value="Login" id="submit" disabled />
            </div>

          </form>
        </div>
      </div>
    </section>

    <script>
      function validation() {
        let username = document.getElementById("username").value;
        let pass = document.getElementById("password").value;
        if (username != "" && pass != "") {
          document.getElementById("submit").disabled = false;
        } else {
          document.getElementById("submit").disabled = true;
        }
      }
    </script>
  </body>
</html>
<?php
session_start();

// Define the hard-coded usernames and passwords

/////////administrator account//////////////////////
$administrator_username = 'administrator';
$administrator_password = '123';
/////////administrator account//////////////////////

/////////locator account/////////////////////////
$locator_username = 'locator';
$locator_password = '123';
/////////locator account/////////////////////////

/////////window2 account/////////////////////////
$window2_username = 'window2';
$window2_password = '123';
/////////window2 account/////////////////////////

/////////window3 account/////////////////////////
$window3_username = 'window3';
$window3_password = '123';
/////////window3 account/////////////////////////

/////////Verification account/////////////////////////
$verification_username = 'verification';
$verification_password = '123';
/////////Verification account/////////////////////////

/////////Reliever account/////////////////////////
$reliever_username = 'reliever';
$reliever_password = '123';
/////////Reliever account/////////////////////////

// Check if the user is attempting to log in
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify the user's credentials
    if (($username === $administrator_username && $password === $administrator_password) ||
        ($username === $locator_username && $password === $locator_password) || 
        ($username === $window2_username && $password === $window2_password) ||
        ($username === $window3_username && $password === $window3_password) ||
        ($username === $verification_username && $password === $verification_password) ||
        ($username === $reliever_username && $password === $reliever_password)) {
        $_SESSION['user'] = $username;

        // Store the user's role in the session
        if ($username === $administrator_username) {
            $_SESSION['role'] = 'Administrator';
        } elseif ($username === $locator_username) {
            $_SESSION['role'] = 'Locator';
        } elseif ($username === $window2_username) {
            $_SESSION['role'] = 'Windows 2';
        } elseif ($username === $window3_username) {
            $_SESSION['role'] = 'Windows 3';
        } elseif ($username === $verification_username) {
            $_SESSION['role'] = 'Verification User';
        } elseif ($username === $reliever_username) {
            $_SESSION['role'] = 'Reliever';
        }
    }
}

// Redirect logged-in users to the appropriate page
if (isset($_SESSION['user'])) {
  if ($_SESSION['role'] === 'Administrator' || $_SESSION['role'] === 'Verification User') {
      header('Location: app/pages/verification.php');
  } else {
      header('Location: app/pages/search.php');
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
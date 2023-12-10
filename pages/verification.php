<?php
session_start();

// Check if the user is DFA Employee
if (!isset($_SESSION['user']) || ($_SESSION['user'] !== 'processor' && $_SESSION['user'] !== 'admin' && $_SESSION['user'] !== 'programmer')) {
  // Redirect to a different page or display an error message
  echo "Access denied. Only DFA Employee can access here!.";
  exit();
}
// Get the user's role from the session
if (isset($_SESSION['role'])) {
    $userRole = $_SESSION['role'];
} else {
    // Default role if not set (you can customize this as needed)
    $userRole = "Unknown";
}

// Initialize the result message variable
$resultMessage = "";

// Check if an appointment code was scanned and submitted
if (isset($_POST['appointmentCode'])) {
    // Store the scanned appointment code in a session variable
    $_SESSION['scanned_appointmentCode'] = $_POST['appointmentCode'];

    // Database connection
    require '../core/config.php';

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare a SQL statement to check if the scanned data exists in table 1
    $checkSql = "SELECT * FROM releasing_data WHERE appointmentCode = ?";
    $checkStmt = $conn->prepare($checkSql);

    if ($checkStmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameter for the check statement
    $checkStmt->bind_param("s", $_SESSION['scanned_appointmentCode']);

    // Execute the check statement
    $checkStmt->execute();

    // Fetch the result
    $result = $checkStmt->get_result();

    // Declare the $existingEntryStmt variable
    $existingEntryStmt = null;

    // Check if the scanned data exists in table 1
    if ($result->num_rows > 0) {
        // Fetch the data from table 1
        $row = $result->fetch_assoc();
        $lastName = $row['lastName'];
        $firstName = $row['firstName'];
        $middleName = $row['middleName'];
        $gender = $row['gender'];
        $birthDate = $row['birthDate'];
        $birthPlace = $row['birthPlace'];

        // Check if there is already an entry for the same barcode on the same day
        $existingEntrySql = "SELECT * FROM releasing_scan WHERE appointmentCode = ? AND DATE(scan_datetime) = CURDATE()";
        $existingEntryStmt = $conn->prepare($existingEntrySql);
        $existingEntryStmt->bind_param("s", $_SESSION['scanned_appointmentCode']);
        $existingEntryStmt->execute();

        // Fetch the result for existing entry check
        $existingEntryResult = $existingEntryStmt->get_result();

        // Check if there is already an entry for the same barcode on the same day
        if ($existingEntryResult->num_rows > 0) {
            $resultMessage = "Barcode already scanned today. You can scan it again tomorrow.";
        } else {
            // Data does not exist in table 2, so insert it
            $insertSql = "INSERT INTO releasing_scan (appointmentCode, lastName, firstName, middleName, gender, birthDate, birthPlace, scan_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $insertStmt = $conn->prepare($insertSql);

            // Check if the insert statement preparation failed
            if ($insertStmt === false) {
                die("Prepare failed: " . $conn->error);
            }

            // Bind the parameters for the insert statement
            $insertStmt->bind_param("sssssss", $_SESSION['scanned_appointmentCode'], $lastName, $firstName, $middleName, $gender, $birthDate, $birthPlace);

            // Execute the insert statement
            if ($insertStmt->execute()) {
                // Set the success message
                $resultMessage = "Success";
            } else {
                // Set the failure message
                $resultMessage = "Failed to store data in table 2: " . $insertStmt->error;
            }

            // Close the insert statement
            $insertStmt->close();
        }

        // Close the existing entry result
        $existingEntryResult->close();
    } else {
        // If the scanned data does not exist in table 1
        $resultMessage = "Appointment code not found in the database.";
    }

    // Close the existing entry statement
    if ($existingEntryStmt !== null) {
        $existingEntryStmt->close();
    }
}

// Include the header file
include "../includes/header.php";

?>

<style>
/* This Style is for the form */
    
form {
    padding-top: 20px;
  }

  .container {
    text-align: center;
    margin-top: 40px;
  }

  input {
    padding: 20px 70px; /* Adjust the padding as needed */
    margin-bottom: 10px;
    border: 2px solid black;
    border-radius: 5px;
    font-size: 20px;
    text-align: center;
    border-width: 5px;
  }

  label {
    display: block;
    font-weight: bold;
    font-size: 40px;
  }

 /**tHIS STYLE IS FOR BUTTON ONLY DONT TOUCH!! */

 :root {
  --glow-color: hsl(1 100% 69%);
}

*,
*::before,
*::after {
  box-sizing: border-box;
}

.glowing-btn {
  position: relative;
  color: var(--glow-color);
  cursor: pointer;
  border: 0.15em solid var(--glow-color);
  border-radius: 0.45em;
  background: none;
  perspective: 2em;
  font-family: "Raleway", sans-serif;
  font-size: 2em;
  font-weight: 900;
  letter-spacing: 1em;

  -webkit-box-shadow: inset 0px 0px 0.5em 0px var(--glow-color),
    0px 0px 0.5em 0px var(--glow-color);
  -moz-box-shadow: inset 0px 0px 0.5em 0px var(--glow-color),
    0px 0px 0.5em 0px var(--glow-color);
  box-shadow: inset 0px 0px 0.5em 0px var(--glow-color),
    0px 0px 0.5em 0px var(--glow-color);
  animation: border-flicker 2s linear infinite;
}

.glowing-txt {
  float: left;
  margin-right: -0.8em;
  -webkit-text-shadow: 0 0 0.125em hsl(0 0% 100% / 0.3),
    0 0 0.45em var(--glow-color);
  -moz-text-shadow: 0 0 0.125em hsl(0 0% 100% / 0.3),
    0 0 0.45em var(--glow-color);
  text-shadow: 0 0 0.125em hsl(0 0% 100% / 0.3), 0 0 0.45em var(--glow-color);
  animation: text-flicker 3s linear infinite;
}

.faulty-letter {
  opacity: 0.5;
  animation: faulty-flicker 2s linear infinite;
}

.glowing-btn::before {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  opacity: 0.7;
  filter: blur(1em);
  transform: translateY(120%) rotateX(95deg) scale(1, 0.35);
  background: var(--glow-color);
  pointer-events: none;
}

.glowing-btn::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0;
  z-index: -1;
  background-color: var(--glow-color);
  box-shadow: 0 0 2em 0.2em var(--glow-color);
  transition: opacity 100ms linear;
}

.glowing-btn:hover {
  color: rgba(0, 0, 0, 0.8);
  text-shadow: none;
  animation: none;
}

.glowing-btn:hover .glowing-txt {
  animation: none;
}

.glowing-btn:hover .faulty-letter {
  animation: none;
  text-shadow: none;
  opacity: 1;
}

.glowing-btn:hover:before {
  filter: blur(1.5em);
  opacity: 1;
}

.glowing-btn:hover:after {
  opacity: 1;
}

@keyframes faulty-flicker {
  0% {
    opacity: 0.1;
  }
  2% {
    opacity: 0.1;
  }
  4% {
    opacity: 0.5;
  }
  19% {
    opacity: 0.5;
  }
  21% {
    opacity: 0.1;
  }
  23% {
    opacity: 1;
  }
  80% {
    opacity: 0.5;
  }
  83% {
    opacity: 0.4;
  }

  87% {
    opacity: 1;
  }
}

@keyframes text-flicker {
  0% {
    opacity: 0.1;
  }

  2% {
    opacity: 1;
  }

  8% {
    opacity: 0.1;
  }

  9% {
    opacity: 1;
  }

  12% {
    opacity: 0.1;
  }
  20% {
    opacity: 1;
  }
  25% {
    opacity: 0.3;
  }
  30% {
    opacity: 1;
  }

  70% {
    opacity: 0.7;
  }
  72% {
    opacity: 0.2;
  }

  77% {
    opacity: 0.9;
  }
  100% {
    opacity: 0.9;
  }
}

@keyframes border-flicker {
  0% {
    opacity: 0.1;
  }
  2% {
    opacity: 1;
  }
  4% {
    opacity: 0.1;
  }

  8% {
    opacity: 1;
  }
  70% {
    opacity: 0.7;
  }
  100% {
    opacity: 1;
  }
}

 /**tHIS STYLE IS FOR BUTTON ONLY DONT TOUCH!! */


  /* Add this CSS for styling resultDiv */
  #resultDiv {
    display: inline-block;
    font-size: 40px;
    font-weight: bold;
  }
</style>

<div class="container">
  <img src="../images/DFA2.png" alt="broken-image" style="max-width: 100%; height: auto;">

  
  <form id="myForm" action="verification.php" method="post">
    <label id="appNumbers">Application Number</label>
    <input type="text" id="appointmentCode" name="appointmentCode" onkeyup="checkAppointmentCodeLength()" required><br>
    <button type="submit" class="glowing-btn"><span class="glowing-txt">S<span class="faulty-letter">U</span>BMIT</span></button>
  </form>

  <!-- Result div to display success or failure message -->
  <div id="resultDiv"><?php echo $resultMessage; ?></div>

  <!-- Add an audio element for success sound -->
<audio id="successAudio" src="../audio/Success.mp3"></audio>
<audio id="failureAudio" src="../audio/Error.mp3"></audio>
<audio id="repeatAudio" src="../audio/repeat.mp3"></audio>
  
</div>

<script>
  // JavaScript to change the text color every 2 seconds
  const appNumber = document.getElementById("appNumbers");
  const colors = ["red", "blue", "orange", "green", "yellow"];
  let colorIndex = 0;

  function changeColor() {
    appNumber.style.color = colors[colorIndex];
    colorIndex = (colorIndex + 1) % colors.length;
  }

  setInterval(changeColor, 1000); // Change color every 2 seconds

  // Script for appointmentCode input
  function checkAppointmentCodeLength() {
    var appointmentCodeInput = document.getElementById('appointmentCode').value;
    var desiredLength = 14;

    if (appointmentCodeInput.length === desiredLength) {
      document.getElementById('myForm').submit();
    }
  }

  // Automatically hide the result div after 2 seconds if it contains "Success"
  if (resultDiv.innerHTML === "Success" || resultDiv.innerHTML === "Appointment code not found in the database." || resultDiv.innerHTML === "Barcode already scanned today. You can scan it again tomorrow.") {
    setTimeout(function() {
      resultDiv.style.display = 'none';
    }, 2000);

    // Play the success audio
    if (resultDiv.innerHTML === "Success") {
      successAudio.play();
    } else if (resultDiv.innerHTML === "Barcode already scanned today. You can scan it again tomorrow.") {
      repeatAudio.play();
    } else {
      failureAudio.play();
    }
  }

  // Automatically focus on the appointmentCode input field when the page loads
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("appointmentCode").focus();
  });
</script>

</body>

</html>

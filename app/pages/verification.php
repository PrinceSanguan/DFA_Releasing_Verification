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

<!----------css for this page----------------->
<link rel="stylesheet" href="../../assets/css/verification.css">
<!----------css for this page----------------->

<div class="container">
  <img src="../../assets/images/DFA2.png" alt="broken-image" style="max-width: 100%; height: auto;">

  
  <form id="myForm" action="verification.php" method="post">
    <label id="appNumbers">Application Number</label>
    <input type="text" id="appointmentCode" name="appointmentCode" onkeyup="checkAppointmentCodeLength()" required><br>
    <button type="submit" class="glowing-btn"><span class="glowing-txt">S<span class="faulty-letter">U</span>BMIT</span></button>
  </form>

  <!-- Result div to display success or failure message -->
  <div id="resultDiv"><?php echo $resultMessage; ?></div>

  <!-- Add an audio element for success sound -->
<audio id="successAudio" src="../../assets/audio/Success.mp3"></audio>
<audio id="failureAudio" src="../../assets/audio/Error.mp3"></audio>
<audio id="repeatAudio" src="../../assets/audio/repeat.mp3"></audio>
  
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

<?php
session_start();

// Check if the user is DFA Employee (Administrator, Locator, or Reliever)
if (!isset($_SESSION['user']) || ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Locator' && $_SESSION['role'] !== 'Reliever')) {
  // Redirect to a different page or display an error message
  echo "Access denied. Only Administrator, Locator, and Reliever can access here!";
  exit();
}

// Get the user's role from the session
if (isset($_SESSION['role'])) {
  $userRole = $_SESSION['role'];
} else {
  // Default role if not set (you can customize this as needed)
  $userRole = "Unknown";
}

// Database connection
require '../core/config.php';

// Initialize variables for input fields
$updateAppointmentCode = $updateLastName = $updateFirstName = $updateMiddleName = $updateGender = "";

// Check if the "Update" link was clicked in the search results
if (isset($_GET['appointmentCode']) && isset($_GET['lastName']) && isset($_GET['firstName']) && isset($_GET['middleName']) && isset($_GET['gender'])) {
  // Retrieve the parameters from the URL
  $updateAppointmentCode = $_GET['appointmentCode'];
  $updateLastName = $_GET['lastName'];
  $updateFirstName = $_GET['firstName'];
  $updateMiddleName = $_GET['middleName'];
  $updateGender = $_GET['gender'];
}

// Check if the update form is submitted
if (isset($_POST['updateSubmit'])) {
  // Get the values from the form
 
  $updateAppointmentCode = $_POST['updateAppointmentCode'];
  $updateLocator = $_POST['updateLocator'];
  $updateStatus = $_POST['updateStatus'];

  // Perform the database update with NOW()
  $sql = "UPDATE releasing_scan SET locator = '$updateLocator', status = '$updateStatus' WHERE appointmentCode = '$updateAppointmentCode'";

if ($conn->query($sql) === TRUE) {
  echo '<script>alert("Record updated successfully.");</script>';
  // Redirect to search.php after updating the record
  echo '<script>window.location = "search.php";</script>';
} else {
  echo "Error updating record: " . $conn->error;
}
}

// Include the header file
include "../includes/header.php";
?>

<!----------css for this page----------------->
<!------<link rel="stylesheet" href="../../assets/css/update.css">----->
<!----------css for this page----------------->

<style>
  /* Style for Form */
  form {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  label {
    font-size: 25px;
    margin-bottom: 5px;
    text-align: center; /* Center the text */
    display: block; /* Ensure the text is on a new line */
  }

  input,
  select {
    font-size: 25px;
    width: 100%; /* Adjust the width as needed */
    margin-bottom: 15px;
  }

  .center-container {
    max-width: 50%;
    margin: 0 auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 50px 60px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
  }

  /* Center the label and --select-- option in the dropdown */
  select {
    font-size: 25px;
    width: 100%;
    margin-bottom: 15px;
    text-align-last: center; /* Center the text within the select element */
  }

  option[value=""][disabled] {
    display: none;
  }

  option {
    text-align: center;
  }

  /* button */
  button[type="submit"],
  button[type="button"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin: 10px; /* Add margin for spacing */
  }

  button[type="submit"]:hover,
  button[type="button"]:hover {
    background-color: #0056b3;
  }

  button[type="button"] {
    background-color: #ff0000; /* Red background color */
  }

  button[type="button"]:hover {
    background-color: lightcoral; /* Lighter red on hover */
  }
</style>

<!-- Display the details on the update page -->
<div class="updateRecord">
<h1 style="text-align: center;">Applicant Information</h1>
<p style="text-align: center; font-size: 30px;">Appointment Code: <?php echo $updateAppointmentCode; ?></p>
<p style="text-align: center; font-size: 30px;">Last Name: <?php echo $updateLastName; ?></p>
<p style="text-align: center; font-size: 30px;">First Name: <?php echo $updateFirstName; ?></p>
<p style="text-align: center; font-size: 30px;">Middle Name: <?php echo $updateMiddleName; ?></p>
<!--<?php echo $updateGender; ?></p> <hr><br>-->
</div>

<!-- Update Record Section -->
<div id="updateRecord">
  <form action="updatepnoy.php" method="post">
    <!-- Add values to the hidden fields -->
    <input type="hidden" id="updateAppointmentCode" name="updateAppointmentCode" value="<?php echo $updateAppointmentCode; ?>">
    <input type="hidden" id="updateLastName" name="updateLastName" value="<?php echo $updateLastName; ?>">
    <input type="hidden" id="updateFirstName" name="updateFirstName" value="<?php echo $updateFirstName; ?>">
    <input type="hidden" id="updateMiddleName" name="updateMiddleName" value="<?php echo $updateMiddleName; ?>">

    <div class="center-container">
      <label for="updateLocator">Locate By:</label>
      <input type="text" id="updateLocator" name="updateLocator" value="<?php echo $userRole; ?>" readonly><br>

      <label for="updateStatus">Status:</label>
      <select id="updateStatus" name="updateStatus">
        <option value="" disabled selected>--select--</option>
        <option value="Available">Available</option>
        <option value="Not Available">Not Available</option>
        <option value="Suspended">Suspended</option>
      </select><br>

      <!-- Modify the Submit button -->
      <button type="submit" name="updateSubmit">Submit</button>
      
      <!-- Modify the Cancel button to trigger the JavaScript function -->
      <button type="button" onclick="cancelUpdate()">Cancel</button>
    </div>
  </form>
</div>
  
<script>
  // JavaScript function to handle the Cancel button
  function cancelUpdate() {
    // Redirect to search.php
    window.location.href = "search.php";
  }
</script>

</body>
</html>

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
  $updatereleasedBy = $_POST['updatereleasedBy'];
  $updateclaimedBy = $_POST['updateclaimedBy'];
  $updatenotes = $_POST['updatenotes'];

  // Perform the database update with NOW()
  $sql = "UPDATE releasing_scan SET releasedBy = '$updatereleasedBy', claimedBy = '$updateclaimedBy', notes = '$updatenotes' WHERE appointmentCode = '$updateAppointmentCode'";

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
<link rel="stylesheet" href="../../assets/css/update.css">
<!----------css for this page----------------->

<!-- Display the details on the update page -->
<div class="update-record">
<h2>Update Record</h2>
<p>Appointment Code: <?php echo $updateAppointmentCode; ?></p>
<p>Last Name: <?php echo $updateLastName; ?></p>
<p>First Name: <?php echo $updateFirstName; ?></p>
<p>Middle Name: <?php echo $updateMiddleName; ?></p>
<?php echo $updateGender; ?></p> <hr><br>
</div>

<!-- Update Record Section -->
<div id="updateRecord">
  <form action="update.php" method="post">
    <!-- Add values to the hidden fields -->
    <input type="hidden" id="updateAppointmentCode" name="updateAppointmentCode" value="<?php echo $updateAppointmentCode; ?>">
    <input type="hidden" id="updateLastName" name="updateLastName" value="<?php echo $updateLastName; ?>">
    <input type="hidden" id="updateFirstName" name="updateFirstName" value="<?php echo $updateFirstName; ?>">
    <input type="hidden" id="updateMiddleName" name="updateMiddleName" value="<?php echo $updateMiddleName; ?>">


<h1 style="text-align: center;">Applicant Information</h1>
<div class="grid-container">
    <div class="grid-item">

    <label for="updatereleasedBy">Released By:</label>
    <select id="updatereleasedBy" name="updatereleasedBy">
      <option value="Kate">Kate</option>
      <option value="Gene">Gene</option>
      <option value="Reliever">Reliever</option>
    </select><br>

    <label for="updateclaimedBy">Claimed By:</label>
    <select id="updateclaimedBy" name="updateclaimedBy">
      <option value="Owner">Owner</option>
      <option value="Mother/Father">Mother/Father</option>
      <option value="Representative">Representative</option>
    </select><br>

    <label for="updatenotes">Notes:</label>
    <input type="text" id="updatenotes" name="updatenotes" style="padding-bottom: 30px;" autocomplete="off"><br><br>
</div>
  </div>
</div>

    <!-- Modify the Submit button -->
    <button type="submit" name="updateSubmit">Submit</button>

    <button type="button" onclick="hideUpdateRecord()">Cancel</button>
  </form>
</div>
<script src="./js/update.js">
  
</script>
</body>
</html>

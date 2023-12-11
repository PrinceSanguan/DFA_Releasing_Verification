
<?php 

// Start The Session

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
$appointmentCode = $lastName = $firstName = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $appointmentCode = $_POST['appointmentCode'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];

    // Check if at least one input field is filled
    if (empty($appointmentCode) && empty($lastName) && empty($firstName)) {
        // Display an alert message using JavaScript
        echo "<script>alert('Please input at least one parameter.');</script>";
    } else {
        // Build the SQL query dynamically based on input fields
        $sql = "SELECT * FROM releasing_scan WHERE 1=1";

        if (!empty($appointmentCode)) {
            // Check if the input has exactly 6 digits (you can add more validation if needed)
            if (preg_match('/^\d{6}$/', $appointmentCode)) {
                // Build the SQL query to search by the last 6 digits
                $sql .= " AND RIGHT(appointmentCode, 6) = '$appointmentCode'";
            } else {
                $sql .= " AND 0"; // This will ensure no results are returned for an invalid input
            }
        }

        if (!empty($lastName)) {
            $sql .= " AND lastName = '$lastName'";
        }

        if (!empty($firstName)) {
            $sql .= " AND firstName = '$firstName'";
        }

        $result = $conn->query($sql);

      }
}

// Include the header file
include "../includes/header.php";
?>

<!----------css for this page----------------->
<link rel="stylesheet" href="../../assets/css/search.css">
<!----------css for this page----------------->


 <!-- Container for form and Table -->
 <div class="container">
        <form action="search.php" method="post">
            <label for="appointmentCode">Last 6 ARN</label>
            <input type="text" id="appointmentCode" name="appointmentCode" autocomplete="off" "><br>
            
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" autocomplete="off" ><br>
            
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" autocomplete="off" ><br>
            
            <button type="submit" name="submit" value="search">Search</button>
        </form>

        <!-- The Table -->
        <?php
        if (isset($result)) {
            if ($result->num_rows > 0) {
                echo "<h3>Data matching your search:</h3>";
                echo "<table border='1'>
                        <tr>
                            <th>Appointment Code</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Gender</th>
                            <th>Birth Date</th>
                            <th>Birth Place</th>
                            <th>Site</th>
                            <th>Package Id</th>
                            <th>Scan Date</th>
                        </tr>";

                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                  <td>" . $row['appointmentCode'] . "</td>
                  <td>" . $row['lastName'] . "</td>
                  <td>" . $row['firstName'] . "</td>
                  <td>" . $row['middleName'] . "</td>
                  <td>" . $row['gender'] . "</td>
                  <td>" . $row['birthDate'] . "</td>
                  <td>" . $row['birthPlace'] . "</td>
                  <td>" . $row['site'] . "</td>
                  <td>" . $row['packageId'] . "</td>
                  <td>" . $row['scan_datetime'] . "</td>
                  <td><a href='../../app/pages/update.php?appointmentCode=" . $row['appointmentCode'] . "&lastName=" . $row['lastName'] . "&firstName=" . $row['firstName'] . "&middleName=" . $row['middleName'] . "&gender=" . $row['gender'] . "'>Update</a></td>
              </tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No matching records found in the database.</p>";
            }
        }
        ?>
    </div>


<!-- Display the user role as a floating element -->
<div class="floating-element">
    <p>Welcome, <?php echo $userRole ?></p>
</div>

<script>

</script>
</body>
</html>
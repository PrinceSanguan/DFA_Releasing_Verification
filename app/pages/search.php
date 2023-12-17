
<?php 

// Start The Session

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to a different page or display an error message
    echo "Access denied. Please log in to access this page.";
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
    }
} else {
    // If the form is not submitted, fetch data based on user role
    if ($userRole == 'Locator') {
        $sql = "SELECT * FROM releasing_scan WHERE locator IS NULL";
    } elseif ($userRole == 'Windows 2' || $userRole == 'Windows 3') {
        // For Windows2 and Windows3, fetch all data where releasedby is null
        $sql = "SELECT * FROM releasing_scan WHERE releasedBy IS NULL";
    } else {
        // For other user roles, fetch all data
        $sql = "SELECT * FROM releasing_scan";
    }
}

// Execute the SQL query
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Query failed: " . $conn->error);
}

      ///////////////////////////for monitoring/////////////////////////////////////////////////////////////

// Check if the filter button is clicked and a date is selected
if (isset($_POST['filter_button']) && isset($_POST['selected_date'])) {
    $selectedDate = $_POST['selected_date'];
    // Add a WHERE clause to filter the data based on the selected date
    $sql .= " WHERE DATE(scan_datetime) = '$selectedDate'";
}
/*

// Execute the SQL query
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Query failed: " . $conn->error);
}
*/

///////////////////////////for monitoring/////////////////////////////////////////////////////////////


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
  
<?php
// The Table
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
                    <th>Action</th>
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
                        <td>";
                
                    // Check user role and set the appropriate update link
                    if (in_array($_SESSION['role'], ['Windows 2', 'Windows 3', 'Administrator'])) {
                        echo "<a href='../../app/pages/update.php?appointmentCode=" . $row['appointmentCode'] . "&lastName=" . $row['lastName'] . "&firstName=" . $row['firstName'] . "&middleName=" . $row['middleName'] . "&gender=" . $row['gender'] . "'>Update</a>";
                    } elseif (in_array($_SESSION['role'], ['Locator', 'Reliever'])) {
                        echo "<a href='../../app/pages/updatepnoy.php?appointmentCode=" . $row['appointmentCode'] . "&lastName=" . $row['lastName'] . "&firstName=" . $row['firstName'] . "&middleName=" . $row['middleName'] . "&gender=" . $row['gender'] . "'>Update</a>";
                    }
                
                    echo "</td>
                    </tr>";
                }
                
                echo "</table>";
    } else {
        echo "<p>No matching records found in the database.</p>";
    }
}
        ?>
    </div>

<!------------------------------------------- Display the user role as a floating element ------------------------------------------>
<div class="floating-element">
    <p>Welcome, <?php echo $userRole ?></p>
</div>
<!------------------------------------------- Display the user role as a floating element ------------------------------------------>


</body>
</html>
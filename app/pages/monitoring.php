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

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a SQL query to select all data from your table
$sql = "SELECT * FROM releasing_scan";

// Check if the filter button is clicked and a date is selected
if (isset($_POST['filter_button']) && isset($_POST['selected_date'])) {
    $selectedDate = $_POST['selected_date'];
    // Add a WHERE clause to filter the data based on the selected date
    $sql .= " WHERE DATE(scan_datetime) = '$selectedDate'";
}

// Execute the SQL query
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Query failed: " . $conn->error);
}

// Check if the button is clicked
if (isset($_POST['delete_button'])) {
    // Define the table name
    $tableName = "releasing_scan"; // Replace with your table name

    // Delete all data from the table
    $deleteQuery = "DELETE FROM $tableName";
    if ($conn->query($deleteQuery) === true) {
        $alertMessage = "All data from tools is successfully deleted.";
    } else {
        $alertMessage = "Error deleting data: " . $conn->error;
    }
}

////// including the header ////////////////////////
include "../includes/header.php";
////// including the header ////////////////////////

?>
<!----------css for this page----------------->
  <link rel="stylesheet" href="../../assets/css/monitoring.css">
<!----------css for this page----------------->

  <div class="container">
    <h1>Monitoring Page</h1>

    <!--
   <form class="form_delete" method="post">
        <button class="buttonDelete" type="submit" name="delete_button">Delete All Data</button>
    </form>
-->

    <form method="post">
        <input type="date" id="selected_date" name="selected_date">
        <button type="submit" name="filter_button" class="button-85" role="button">Filter Data</button>
    </form>

    <!-- Display the data in an HTML table -->
    <table border='1'>
        <tr>
            <th>Appointment Code</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Gender Name</th>
            <th>Birth Date</th>
            <th>Birth Place</th>
            <th>Site</th>
            <th>Package Id</th>
            <th>Released By</th>
            <th>Claimed By</th>
            <th>Notes</th>
            <th>Scan Time</th>
            <!-- Add more columns as needed -->
        </tr>

        <?php
        // Loop through the data and display it in the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['appointmentCode'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['middleName'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td>" . $row['birthDate'] . "</td>";
            echo "<td>" . $row['birthPlace'] . "</td>";
            echo "<td>" . $row['site'] . "</td>";
            echo "<td>" . $row['packageId'] . "</td>";
            echo "<td>" . $row['releasedBy'] . "</td>";
            echo "<td>" . $row['claimedBy'] . "</td>";
            echo "<td>" . $row['notes'] . "</td>";
            echo "<td>" . $row['scan_datetime'] . "</td>";
            // Add more columns as needed
            echo "</tr>";
        }
        ?>
    </table>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>

<script>
        var alertMessage = "<?php echo $alertMessage; ?>";
        if (alertMessage) {
            alert(alertMessage);
        }
    </script>
</body>
</html>

<!-- HTML !-->


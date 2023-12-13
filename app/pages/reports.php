<?php

session_start();

// Check if the user is DFA Employee (excluding Verification User)
if (!isset($_SESSION['user']) || ($_SESSION['role'] === 'Verification User')) {
  // Redirect to a different page or display an error message
  echo "Access denied. Only DFA Employees (excluding Verification User) can access here!";
  exit();
}

// Get the user's role from the session
if (isset($_SESSION['role'])) {
  $userRole = $_SESSION['role'];
} else {
  // Default role if not set (you can customize this as needed)
  $userRole = "Unknown";
}

// Initialize variables
$totalAppearances = 0;

include "../includes/header.php";

?>

<!----------css for this page----------------->
<link rel="stylesheet" href="../../assets/css/reports.css">
<!----------css for this page----------------->

  <div class="container">
    <form action="reports.php" method="post" onsubmit="return confirmGenerate()">
      <label for="selectedDate">Select Date:</label>
      <input type="date" id="selectedDate" name="selectedDate" required>
      <button type="submit" name="generateReport" class="button-85" role="button">Generate Report</button>
    </form>

    <?php

    // Database connection
    require '../core/config.php';

    // Check if the form is submitted
    if (isset($_POST['generateReport'])) {
      // Get the selected date
      $selectedDate = $_POST['selectedDate'];

      // Fetch data from the database for the selected date
      $sql = "SELECT COUNT(*) as total FROM releasing_scan WHERE DATE(scan_datetime) = '$selectedDate'";
      $result = $conn->query($sql);

      if ($result === false) {
        die("Query failed: " . $conn->error);
      }

      $row = $result->fetch_assoc();
      $totalAppearances = $row['total'];
    }

    // Close the database connection
    $conn->close();
    ?>

    <!-- Display the counts in your HTML -->
    <div class="container">
      <table>
        <tr>
          <th>Selected Date</th>
          <th>Total Appearances</th>
        </tr>

        <tr>
          <td><?php echo isset($selectedDate) ? $selectedDate : 'Not available'; ?></td>
          <td><?php echo $totalAppearances; ?></td>
        </tr>
      </table>
    </div>

    <script>
      function confirmGenerate() {
        var selectedDate = document.getElementById("selectedDate").value;
        return confirm("Generate report for the selected date: " + selectedDate + "?");
      }
    </script>
</body>

</html>


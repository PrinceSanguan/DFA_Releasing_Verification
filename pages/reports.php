<?php

session_start();

// Check if the user is DFA Employee
if (!isset($_SESSION['user']) || ($_SESSION['user'] !== 'processor' && $_SESSION['user'] !== 'admin' && $_SESSION['user'] !== 'programmer')) {
  // Redirect to a different page or display an error message
  echo "Access denied. Only DFA Employee can access here!.";
  exit();
}

// Initialize variables
$totalAppearances = 0;

// Get the user's role from the session
if (isset($_SESSION['role'])) {
  $userRole = $_SESSION['role'];
} else {
  // Default role if not set (you can customize this as needed)
  $userRole = "Unknown";
}

include "../includes/header.php";

?>

<style>
  /* This style is for the Table */

  .container {
    text-align: center;
    margin-top: 1px;
  }

  table {
  width: 30%;
  border-collapse: collapse;
  margin-top: 10px;
  margin-left: auto;
  margin-right: auto;
  text-align: center;
}

  td {
    padding: 7px;
    text-align: center;
    border-bottom: 6px solid #f2f2f2;
    font-size: 30px;
  }

  th {
    background-color: #f2f2f2;
    text-align: center;
    font-size: 20px;
  }

  h1 {
    text-align: center;
  }

   /* This Style is for the form */
    

   input {
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 20px;
    text-align: center;
    border-width: 5px;
  }

  label {
    display: block;
    font-weight: bold;
    font-size: 25px;
  }

   /**************************************  CSS for filter button Only ***********************/
.button-85 {
  padding: 0.6em 2em;
  border: none;
  outline: none;
  color: rgb(255, 255, 255);
  background: #111;
  cursor: pointer;
  position: relative;
  z-index: 0;
  border-radius: 10px;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  font-weight: bold;
}

.button-85:before {
  content: "";
  background: linear-gradient(
    45deg,
    #ff0000,
    #ff7300,
    #fffb00,
    #48ff00,
    #00ffd5,
    #002bff,
    #7a00ff,
    #ff00c8,
    #ff0000
  );
  position: absolute;
  top: -2px;
  left: -2px;
  background-size: 400%;
  z-index: -1;
  filter: blur(5px);
  -webkit-filter: blur(5px);
  width: calc(100% + 4px);
  height: calc(100% + 4px);
  animation: glowing-button-85 20s linear infinite;
  transition: opacity 0.3s ease-in-out;
  border-radius: 10px;
}

@keyframes glowing-button-85 {
  0% {
    background-position: 0 0;
  }
  50% {
    background-position: 400% 0;
  }
  100% {
    background-position: 0 0;
  }
}

.button-85:after {
  z-index: -1;
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  background: #222;
  left: 0;
  top: 0;
  border-radius: 10px;
}

/************************************** CSS for filter button Only ***********************/
</style>

<body>

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


<?php 

session_start();

// Check if the user is DFA Employee (Administrator, Locator, or Reliever)
if (!isset($_SESSION['user']) || !in_array($_SESSION['role'], ['Administrator', 'Locator', 'Reliever'])) {
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
require "../core/config.php"; 

// Check if the button is clicked
if (isset($_POST['delete_button'])) {
  // Define the table name
  $tableName = "releasing_data"; // Replace with your table name

  // Delete all data from the table
  $deleteQuery = "DELETE FROM $tableName";
  if ($conn->query($deleteQuery) === true) {
      $alertMessage = "All data from tools is succesfully deleted.";
  } else {
      $alertMessage = "Error deleting data: " . $conn->error;
  }

}

include "../includes/header.php";
?>

<!----------css for this page----------------->
<link rel="stylesheet" href="../../assets/css/tools.css">
<!----------css for this page----------------->

    <h1>Import Data</h1>
		<form class="" action="tools.php" method="post" enctype="multipart/form-data">
			<input type="file" name="excel" required value="">
			<button type="submit" name="import" class="button-85" role="button">Import</button>
		</form>

    <form class="form_delete" method="post">
        <!-- Create a button to delete data -->
       <!-- <button style="--clr:#FF44CC" type="submit" name="delete_button"><span>Delete all Data</span><i></i></button> -->
    </form>
		<hr>
		<table border = 1>
			<tr>
				<td>Appointment Code</td>
				<td>Last Name</td>
        <td>First Name</td>
				<td>Middle Name</td>
				<td>Gender</td>
				<td>Birth Date</td>
				<td>Birth Place</td>
				<td>Site</td>
				<td>Package ID</td>
			</tr>
			<?php
			$i = 1;
			$rows = mysqli_query($conn, "SELECT appointmentCode, lastName, firstName, middlename, gender, birthDate, birthPlace, site, packageId FROM releasing_data");

			foreach($rows as $row) :
			?>
			<tr>
				<td> <?php echo $row["appointmentCode"]; ?> </td>
				<td> <?php echo $row["lastName"]; ?> </td>
        <td> <?php echo $row["firstName"]; ?> </td>
				<td> <?php echo $row["middlename"]; ?> </td>
				<td> <?php echo $row["gender"]; ?> </td>
				<td> <?php echo $row["birthDate"]; ?> </td>
				<td> <?php echo $row["birthPlace"]; ?> </td>
				<td> <?php echo $row["site"]; ?> </td>
				<td> <?php echo $row["packageId"]; ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
		if(isset($_POST["import"])){
			$fileName = $_FILES["excel"]["name"];
			$fileExtension = explode('.', $fileName);
      $fileExtension = strtolower(end($fileExtension));
			$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

			$targetDirectory = "../../assets/uploads/" . $newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

			require '../../assets/excelReader/excel_reader2.php';
			require '../../assets/excelReader/SpreadsheetReader.php';

			$reader = new SpreadsheetReader($targetDirectory);
			foreach($reader as $key => $row){

				// Code for the skipping the first row

				if ($key === 0) {
					continue;
				}

				$appointmentCode = $row[0];
				$lastName = $row[1];
				$firstName = $row[2];
        $middleName = $row[3];
				$gender = $row[4];
				$birthDate = $row[5];
				$birthPlace = $row[6];
				$site = $row[7];
				$packageId = $row[8];

				if (mysqli_query($conn, "INSERT INTO releasing_data (appointmentCode, lastName, firstName, middleName, gender, birthDate, BirthPlace, site, packageId) 
																	VALUES ('$appointmentCode', '$lastName', '$firstName', '$middleName', '$gender', '$birthDate', '$birthPlace', '$site', '$packageId')")) {
          echo "Data inserted successfully";
      } else {
          echo "Error: " . mysqli_error($conn);
      }
      
			}

			echo
			"
			<script>
			alert('Succesfully Imported');
			document.location.href = '';
			</script>
			";
		}
		?>

    <script>
        var alertMessage = "<?php echo $alertMessage; ?>";
        if (alertMessage) {
            alert(alertMessage);
        }
    </script>
	</body>
</html>

<!--

	CREATE TABLE releasing_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointmentCode VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    firstName VARCHAR(255) NOT NULL,
    middleName VARCHAR(255),
    gender VARCHAR(10),
    birthDate VARCHAR(255),
    birthPlace VARCHAR(255),
		site VARCHAR(255),
		packageId VARCHAR(255)
);

 This is how to create a table for reference only!! dont touch this!!!!
-->

<!--
	ALTER TABLE releasing_data
ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;

if you want the id restart to zero like that!
-->

<!-- HTML !-->

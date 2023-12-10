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

<style>
	/* Style for Table */

  table {
  width: 100%;
  }
  
/* Style for Title */

  h1 {
  text-align: center;
  }

/* Style for Form */

  form {
  text-align: center;
  }

/* Style for Button */

  .form_delete {
  margin-top: 20px;
  }

	/* This Style is for the form */
    
	/************************ THIS STYLE IS FOR "CHOOSE FILE" *****************/

	input::file-selector-button {
  background-image: linear-gradient(
    to right,
    #ff7a18,
    #af002d,
    #319197 100%,
    #319197 200%
  );
  background-position-x: 0%;
  background-size: 200%;
  border: 0;
  border-radius: 8px;
  color: #fff;
  padding: 1rem 1.25rem;
  text-shadow: 0 1px 1px #333;
  transition: all 0.25s;
}
input::file-selector-button:hover {
  background-position-x: 100%;
  transform: scale(1.1);
}

/************************ THIS STYLE IS FOR "CHOOSE FILE" *****************/

/************************ THIS STYLE IS FOR "IMPORT" *****************/
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

/************************ THIS STYLE IS FOR "IMPORT" *****************/

/************************ THIS STYLE IS FOR "DELETE BUTTON" *****************/
button {
  position: relative;
  background: #444;
  color: #fff;
  text-decoration: none;
  text-transform: uppercase;
  border: none;
  letter-spacing: 0.1rem;
  font-size: 1rem;
  padding: 1rem 3rem;
  transition: 0.2s;
}

button:hover {
  letter-spacing: 0.2rem;
  padding: 1.1rem 3.1rem;
  background: var(--clr);
  color: var(--clr);
  /* box-shadow: 0 0 35px var(--clr); */
  animation: box 3s infinite;
}

button::before {
  content: "";
  position: absolute;
  inset: 2px;
  background: #272822;
}

button span {
  position: relative;
  z-index: 1;
}
button i {
  position: absolute;
  inset: 0;
  display: block;
}

button i::before {
  content: "";
  position: absolute;
  width: 10px;
  height: 2px;
  left: 80%;
  top: -2px;
  border: 2px solid var(--clr);
  background: #272822;
  transition: 0.2s;
}

button:hover i::before {
  width: 15px;
  left: 20%;
  animation: move 3s infinite;
}

button i::after {
  content: "";
  position: absolute;
  width: 10px;
  height: 2px;
  left: 20%;
  bottom: -2px;
  border: 2px solid var(--clr);
  background: #272822;
  transition: 0.2s;
}

button:hover i::after {
  width: 15px;
  left: 80%;
  animation: move 3s infinite;
}

@keyframes move {
  0% {
    transform: translateX(0);
  }
  50% {
    transform: translateX(5px);
  }
  100% {
    transform: translateX(0);
  }
}

@keyframes box {
  0% {
    box-shadow: #27272c;
  }
  50% {
    box-shadow: 0 0 25px var(--clr);
  }
  100% {
    box-shadow: #27272c;
  }
}
/************************ THIS STYLE IS FOR "DELETE BUTTON" *****************/
label {
  display: block;
  font-weight: bold;
  font-size: 25px;
  }

</style>


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
			</tr>
			<?php
			$i = 1;
			$rows = mysqli_query($conn, "SELECT appointmentCode, lastName, firstName, middlename, gender, birthDate, birthPlace FROM releasing_data");

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
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
		if(isset($_POST["import"])){
			$fileName = $_FILES["excel"]["name"];
			$fileExtension = explode('.', $fileName);
      $fileExtension = strtolower(end($fileExtension));
			$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

			$targetDirectory = "../uploads/" . $newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

			require '../excelReader/excel_reader2.php';
			require '../excelReader/SpreadsheetReader.php';

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

				if (mysqli_query($conn, "INSERT INTO releasing_data (appointmentCode, lastName, firstName, middleName, gender, birthDate, BirthPlace) 
																	VALUES ('$appointmentCode', '$lastName', '$firstName', '$middleName', '$gender', '$birthDate', '$birthPlace')")) {
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
    birthPlace VARCHAR(255)
);

 This is how to create a table for reference only!! dont touch this!!!!
-->

<!--
	ALTER TABLE releasing_data
ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;

if you want the id restart to zero like that!
-->

<!-- HTML !-->

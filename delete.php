<!DOCTYPE html>
<html>
<head>
	<title>Delete User</title>
</head>
<body>
	<h2>Delete User</h2>
	<form method="post" action="">
		<label>Password:</label>
		<input type="password" name="password" required><br>

		<input type="submit" name="submit" value="Delete">
	</form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$password = $_POST['password'];

	// Connect to the database
	$db_host = 'localhost'; // Replace with your database host
	$db_name = 'mydb'; // Replace with your database name
	$db_user = 'root'; // Replace with your database username
	$db_pass = ''; // Replace with your database password
	$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

	// Check for errors
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Find the user by email
	session_start();
	$email = $_SESSION['login_email'];
	$sql = "SELECT * FROM users WHERE email = '$email'";
	$result = $conn->query($sql);

	if ($result->num_rows == 0) {
		echo "User not found.";
	} else {
		// Check if the password is correct
		$user = $result->fetch_assoc();
		if (password_verify($password, $user['password'])) {
			// Delete the user data from the database
			$sql = "DELETE FROM users WHERE email = '$email'";
			if ($conn->query($sql) === TRUE) {
				header("location:login.php"); 
			} else {
				echo "Error deleting user: " . $conn->error;
			}
			session_destroy();
		} else {
			echo "Incorrect password.";
		}
	}

	// Close the database connection
	$conn->close();
}
?>
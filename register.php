<!DOCTYPE html>
<html>
<head>
	<title>Registration Form</title>
</head>
<body>
	<h2>Registration Form</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label for="username">Username:</label>
		<input type="text" name="username" id="username"><br><br>

		<label for="password">Password:</label>
		<input type="password" name="password" id="password"><br><br>

		<input type="submit" value="Register">
        <p>Already have an account? <a href="login.phhp">Login</a></p>
	</form>

	<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$username = $_POST['username'];
		$password = $_POST['password'];

		// Generate a random salt for the user.
		$salt = bin2hex(random_bytes(16));

		// Hash the user's password using the salt.
		$hashed_password = hash('sha256', $password . $salt);

        require_once("config.php");

		// Insert the user's information into the database.
		$sql = "INSERT INTO user_testing (username, password, salt) VALUES ('$username', '$hashed_password', '$salt')";
		if (mysqli_query($conn, $sql)) {
		    echo "Registration successful. Please log in.";
		} else {
		    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}

		mysqli_close($conn);
	}
	?>
</body>
</html>
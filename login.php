<!DOCTYPE html>
<html>

    <head>
        <title>Login Form</title>
    </head>

    <body>
        <h2>Login Form</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username"><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password"><br><br>

            <input type="submit" value="Login">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>

        <?php
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            require_once("config.php");

            // Retrieve the salt for the user from the database.
            $sql = "SELECT salt FROM user_testing WHERE username='$username'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $salt = $row['salt'];

                // Hash the user's password using the salt from the database.
                $hashed_password = hash('sha256', $password . $salt);

                // Check if the hashed password matches the one in the database.
                $sql = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) === 1) {
                    // If the login is successful, create a session for the user and redirect them to another page.
                    $_SESSION['username'] = $username;
                    header("Location: home.php");
                    exit;
                }
            }

            // If the login is unsuccessful, display an error message.
            echo "Invalid username or password.";

            mysqli_close($conn);
        }
        ?>
    </body>

</html>
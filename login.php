<!DOCTYPE html>
<html>
  <head>
      <title>Login Form</title>
  </head>
  <body>
      <h2>Login Form</h2>
      <form method="POST" action="login.php">
          <label for="username">Username:</label>
          <input type="text" name="username" id="username"><br><br>

          <label for="password">Password:</label>
          <input type="password" name="password" id="password"><br><br>

          <label for="remember_me">Remember Me:</label>
          <input type="checkbox" name="remember_me" id="remember_me"><br><br>

          <input type="submit" value="Login">
      </form>
  </body>
</html>

<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $remember_me = isset($_POST['remember_me']);

      // Check the username and password against a database or some other authentication system.
      // If they're valid, create a session for the user and redirect them to another page.
      // If not, display an error message.

      if ($remember_me) {
          // If the "Remember Me" checkbox was checked, set a cookie that will remember the user's login.
          setcookie('remembered_username', $username, time() + (60 * 60 * 24 * 30)); // The cookie will expire in 30 days.
      } else {
          // If the "Remember Me" checkbox was not checked, delete any previously-set cookie for the user.
          setcookie('remembered_username', '', time() - 3600);
      }
  }
?>

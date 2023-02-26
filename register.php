<?php
    // Include config file
    require_once "config.php";
    
    // Define variables and initialize with empty values
    $email = $password = $confirm_password = $username = "";
    $email_err = $password_err = $confirm_password_err = "";

    // Generate a random salt for the user.
	$salt = bin2hex(random_bytes(16));
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        // Validate username
        if(empty(trim($_POST["email"]))){
            $email_err = "Please enter a email.";
        } else{
            // Prepare a select statement
            $sql = "SELECT id FROM user_testing WHERE email = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                // Set parameters
                $param_email = trim($_POST["email"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "This email is already taken.";
                    } else{
                        $email = trim($_POST["email"]);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        // Validate password
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }
        
        // Check input errors before inserting in database
        if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
            
            $username = substr($_POST["email"], 0, stripos($_POST["email"], "@") );

            // Prepare an insert statement
            $sql = "INSERT INTO user_testing (email, password, username) VALUES (?, ?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_password, $param_username);
                
                // Set parameters
                $param_email = $email;
                $param_password = password_hash($password, $salt); // Creates a password hash
                $param_username = $username;    
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                    header("location: login.php");
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        // Close connection
        mysqli_close($link);
    }
?>

<?php echo file_get_contents("../html/header-footer/account-header.php") ?>
 
<main class="register-page">
    <tittle>Register | Design Atlas</title>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label><p>Email</p></label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <p style="color: var(--red-color);"><span class="invalid-feedback"><?php echo $email_err; ?></span></p>
            </div>    
            <div class="form-group">
                <label><p>Password</p></label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <p style="color: var(--red-color);"><span class="invalid-feedback"><?php echo $password_err; ?></span></p>
            </div>
            <div class="form-group">
                <label><p>Confirm Password</p></label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <p style="color: var(--red-color);"><span class="invalid-feedback"><?php echo $confirm_password_err; ?></span></p>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</main>

<?php echo file_get_contents("../html/header-footer/account-footer.php") ?>
<?php

require_once "config.php";

$checkEmailColumn = $conn->query("SHOW COLUMNS FROM users LIKE 'email'");
if($checkEmailColumn->num_rows == 0) {
    // Column doesn't exist, add it
    $addEmailColumn = "ALTER TABLE users ADD COLUMN email VARCHAR(255) NOT NULL UNIQUE AFTER username";
    $conn->query($addEmailColumn);
}
 
// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address.";
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter a valid email address.";
    } else{
        // Prepare a select statement to check email
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already registered.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

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
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: http://localhost/indian-culture-main/indian-culture-main/php/login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Indian Culture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts Link -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700|Montserrat:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        /* Video Background Styling */
        #bgVideo {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            background-size: cover;
            filter: brightness(0.9); /* Slightly dim the video */
        }
        body {
            font-family: 'Raleway', sans-serif;
            /* Background overlay - slightly darker */
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)); /* Overlay only */
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 0;
        }
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
            width: 100%; /* Ensure it takes width */
            z-index: 1; /* Keep above video */
        }
        .logo-section h1 {
            font-family: 'Montserrat', sans-serif;
            color: white;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
         .logo-section p {
            color: #eee;
            font-size: 16px;
            font-weight: 400;
        }
        .wrapper {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
            border: 1px solid #FF9933;
            margin: auto;
            z-index: 1; /* Keep above video */
            position: relative; /* Ensure z-index works */
        }
        .wrapper h2 {
            color: #FF9933; /* Saffron color */
            font-weight: 700; /* Bolder */
            font-family: 'Montserrat', sans-serif; /* Consistent heading font */
            margin-bottom: 15px; /* Adjusted spacing */
            text-align: center;
            font-size: 28px; /* Slightly larger heading */
        }
        .wrapper p {
            color: #555; /* Darker grey for better readability */
            margin-bottom: 30px; /* Increased spacing */
            text-align: center;
            font-size: 15px;
        }
        .form-group {
            margin-bottom: 20px; /* Adjusted spacing for register form */
        }
        .form-control {
            border-radius: 8px; /* Softer corners */
            padding: 12px 15px; /* Adjusted padding */
            border: 1px solid #ccc; /* Lighter border */
            transition: all 0.3s ease;
            height: auto; /* Ensure padding is respected */
        }
        .form-control:focus {
            border-color: #FF9933; /* Saffron border on focus */
            box-shadow: 0 0 8px rgba(255, 153, 51, 0.4); /* Saffron shadow on focus */
            background-color: #fff; /* Keep white background on focus */
        }
        /* Style for input fields inside input-group */
        .input-group .form-control {
             border-top-left-radius: 0;
             border-bottom-left-radius: 0;
        }
        .input-group .form-control:focus {
            z-index: 3; /* Ensure focus shadow is on top */
        }
        label {
            font-weight: 600;
            color: #333; /* Darker label */
            margin-bottom: 6px; /* Adjusted space below label */
            display: inline-block;
        }
        .btn-primary {
            background-color: #FF9933; /* Saffron background */
            border: none;
            padding: 12px; /* Adjusted padding */
            font-weight: 700; /* Bolder text */
            font-size: 16px;
            border-radius: 8px; /* Softer corners */
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase; /* Uppercase text */
            letter-spacing: 0.5px;
            margin-top: 10px; /* Add some space above the button */
        }
        .btn-primary:hover {
            background-color: #E68A00; /* Darker Saffron on hover */
            transform: translateY(-3px); /* Slightly more lift */
            box-shadow: 0 8px 20px rgba(230, 138, 0, 0.4); /* Enhanced Saffron shadow */
        }
         /* Keep secondary button styles if needed, or remove/adjust */
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px; /* Match primary button */
            transition: all 0.3s ease;
            width: 100%; /* Full width */
            margin-top: 10px; /* Space above reset button */
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        .alert {
            border-radius: 8px; /* Match other elements */
            margin-bottom: 20px;
            padding: 12px 15px;
        }
        .alert-danger { /* Style error messages */
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .wrapper a {
            color: #FF9933; /* Saffron links */
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .wrapper a:hover {
            color: #E68A00; /* Darker Saffron on hover */
            text-decoration: underline;
        }
        .home-link {
            display: block;
            margin-top: 25px; /* Increased spacing */
            text-align: center;
            font-weight: 600;
        }
        .input-group-prepend {
            width: auto; /* Adjust width automatically */
             margin-right: -1px; /* Fix gap between addon and input */
        }
        .input-group-text {
            width: 45px; /* Fixed width for icon background */
            background-color: #FF9933; /* Saffron background */
            border: 1px solid #FF9933; /* Saffron border */
            color: white;
            justify-content: center;
            border-top-left-radius: 8px; /* Match input radius */
            border-bottom-left-radius: 8px; /* Match input radius */
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .invalid-feedback {
             font-size: 85%; /* Slightly smaller error text */
             text-align: left; /* Align error messages left */
             margin-top: 3px;
        }
        .mt-3 { /* Ensure login link paragraph has correct spacing */
            margin-top: 1.5rem !important; /* Increased top margin */
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <video autoplay muted loop id="bgVideo">
        <source src="../images/v2.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <!-- Centering Container -->
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="logo-section">
            <h1>Indian Culture</h1>
            <p style="color: #eee;">Join our community to explore the rich heritage of India</p>
        </div>
        <div class="wrapper">
            <h2>Sign Up</h2>
            <p>Please fill this form to create an account</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                        <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                        </div>
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-between">
                    <input type="submit" class="btn btn-primary" value="Create Account">
                    <input type="reset" class="btn btn-secondary" value="Reset">
                </div>
                <p class="mt-3">Already have an account? <a href="http://localhost/indian-culture-main/indian-culture-main/php/login.php">Login here</a></p>
            </form>
            <a href="http://localhost/indian-culture-main/indian-culture-main/" class="home-link">Back to Home</a>
        </div>
    </div>
</body>
</html> 
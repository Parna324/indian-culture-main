<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: http://localhost/indian-culture-main/indian-culture-main/");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header("location: http://localhost/indian-culture-main/indian-culture-main/");
                        } else{
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Indian Culture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts Link -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700|Montserrat:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            /* Keeping original background, modify URL if needed */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../images/explore-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0; /* Add some padding for smaller screens */
        }
        .logo-section {
            text-align: center;
            margin-bottom: 30px; /* Increased margin */
        }
        .logo-section h1 {
            font-family: 'Montserrat', sans-serif; /* Apply Montserrat font */
            color: white;
            font-size: 42px; /* Increased font size */
            font-weight: 700;
            margin-bottom: 5px; /* Adjusted margin */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Added text shadow */
        }
         .logo-section p {
            color: #eee; /* Lighter color for subtitle */
            font-size: 16px;
            font-weight: 400;
        }
        .wrapper {
            width: 100%; /* Make responsive */
            max-width: 420px; /* Max width */
            padding: 40px; /* Increased padding */
            background-color: rgba(255, 255, 255, 0.95); /* Slightly more opaque */
            border-radius: 15px; /* Increased border radius */
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4); /* Enhanced shadow */
            border: 1px solid #FF9933; /* Added subtle border */
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
            margin-bottom: 25px; /* Increased spacing */
        }
        .form-control {
            border-radius: 8px; /* Softer corners */
            padding: 14px 15px; /* Increased padding */
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
            margin-bottom: 8px; /* Added space below label */
            display: inline-block;
        }
        .btn-primary {
            background-color: #FF9933; /* Saffron background */
            border: none;
            padding: 14px; /* Increased padding */
            font-weight: 700; /* Bolder text */
            font-size: 16px;
            border-radius: 8px; /* Softer corners */
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase; /* Uppercase text */
            letter-spacing: 0.5px;
        }
        .btn-primary:hover {
            background-color: #E68A00; /* Darker Saffron on hover */
            transform: translateY(-3px); /* Slightly more lift */
            box-shadow: 0 8px 20px rgba(230, 138, 0, 0.4); /* Enhanced Saffron shadow */
        }
        .alert-danger { /* Style error messages */
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 25px;
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
        }
        .mb-0 { /* Ensure signup link paragraph has correct spacing */
             margin-bottom: 0 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="logo-section">
                    <h1>Indian Culture</h1>
                    <p style="color: white;">Explore the rich heritage of India</p>
                </div>
                <div class="wrapper">
                    <h2>Login</h2>
                    <p>Please fill in your credentials to login</p>

                    <?php 
                    if(!empty($login_err)){
                        echo '<div class="alert alert-danger">' . $login_err . '</div>';
                    }        
                    ?>

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
                            <label>Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Login">
                        </div>
                        <p class="mb-0">Don't have an account? <a href="http://localhost/indian-culture-main/indian-culture-main/php/register.php">Sign up now</a></p>
                    </form>
                    <a href="http://localhost/indian-culture-main/indian-culture-main/" class="home-link">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 
<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: http://localhost/indian-culture-main/indian-culture-main/php/login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome - Indian Culture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Fonts Link -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700|Montserrat:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            /* Consistent background */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../images/explore-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white; /* White text on dark background */
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Stack items vertically */
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        h1.my-5 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3rem; /* Larger heading */
            font-weight: 700;
            margin-bottom: 2.5rem !important; /* More space below heading */
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6);
        }
        h1 b {
            color: #FFD700; /* Gold color for username */
        }
        p {
            margin-top: 1.5rem;
        }
        .btn {
            padding: 12px 25px; /* Consistent padding */
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px; /* Consistent radius */
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 10px; /* Space between buttons */
            min-width: 200px; /* Minimum width for buttons */
        }
        .btn-primary {
            background-color: #FF9933; /* Saffron */
            border-color: #FF9933;
            color: white;
        }
        .btn-primary:hover {
            background-color: #E68A00; /* Darker Saffron */
            border-color: #E68A00;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(230, 138, 0, 0.4);
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
        }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to Indian Culture.</h1>
    <p>
        <a href="http://localhost/indian-culture-main/indian-culture-main/php/reset-password.php" class="btn btn-primary">Reset Your Password</a>
        <a href="http://localhost/indian-culture-main/indian-culture-main/php/logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html> 
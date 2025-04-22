<?php
require_once "config.php";

$token = $_GET['token'] ?? null;
$password = $confirm_password = "";
$password_err = $confirm_password_err = "";
$message = "";
$error = "";
$user_id = null;
$show_form = false;

if (!$token) {
    $error = "Invalid or missing password reset token.";
} else {
    // Validate token
    $sql = "SELECT user_id, expires_at FROM password_resets WHERE token = ? AND used = 0";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $db_user_id, $db_expires_at);
                mysqli_stmt_fetch($stmt);
                
                // Check if token has expired
                $now = date("Y-m-d H:i:s");
                if ($db_expires_at < $now) {
                    $error = "This password reset token has expired.";
                    // Optionally mark as used anyway
                    $sql_update = "UPDATE password_resets SET used = 1 WHERE token = ?";
                    if($stmt_update = mysqli_prepare($conn, $sql_update)){
                         mysqli_stmt_bind_param($stmt_update, "s", $token);
                         mysqli_stmt_execute($stmt_update);
                         mysqli_stmt_close($stmt_update);
                    }
                } else {
                    // Token is valid, allow password reset
                    $user_id = $db_user_id;
                    $show_form = true;
                }
            } else {
                $error = "Invalid or expired password reset token.";
            }
        } else {
            $error = "Error validating token. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    } else {
         $error = "Error preparing token validation. Please try again later.";
    }
}

// Handle form submission for new password
if ($_SERVER["REQUEST_METHOD"] == "POST" && $show_form) {
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a new password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
    }
        
    // Check input errors before updating the database
    if (empty($password_err) && empty($confirm_password_err)) {
        // Hash new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password in users table (assuming 'users' table)
        $sql_update_user = "UPDATE users SET password = ? WHERE id = ?";
        
        if ($stmt_update_user = mysqli_prepare($conn, $sql_update_user)) {
            mysqli_stmt_bind_param($stmt_update_user, "si", $hashed_password, $user_id);
            
            if (mysqli_stmt_execute($stmt_update_user)) {
                // Password updated successfully. Now invalidate the token.
                 $sql_invalidate_token = "UPDATE password_resets SET used = 1 WHERE token = ?";
                 if($stmt_invalidate = mysqli_prepare($conn, $sql_invalidate_token)){
                     mysqli_stmt_bind_param($stmt_invalidate, "s", $token);
                     mysqli_stmt_execute($stmt_invalidate);
                     mysqli_stmt_close($stmt_invalidate);
                 }

                $message = "Your password has been reset successfully.";
                $show_form = false; // Hide form after success
            } else {
                $error = "Something went wrong updating your password. Please try again later.";
            }
            mysqli_stmt_close($stmt_update_user);
        } else {
             $error = "Error preparing password update. Please try again later.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Indian Culture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom styles here or link CSS file -->
     <style>
        /* Reuse styles from forgot_password.php or create new */
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .wrapper {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .wrapper h2 {
            margin-bottom: 20px;
            color: #333;
        }
         .wrapper p {
            margin-bottom: 25px;
            color: #666;
         }
        .form-group {
             margin-bottom: 20px;
             text-align: left;
        }
        .form-control {
             border-radius: 5px;
             padding: 10px;
        }
        .btn-primary {
            background-color: #FF9933;
            border-color: #FF9933;
            width: 100%;
            padding: 10px;
        }
         .btn-primary:hover {
             background-color: #E68A00;
             border-color: #E68A00;
        }
        .alert {
             margin-top: 20px;
        }
        .login-link a {
            color: #FF9933;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Your Password</h2>

        <?php 
        if (!empty($message)) echo '<div class="alert alert-success">' . htmlspecialchars($message) . ' <a href="login.php">Login here</a>.</div>'; 
        if (!empty($error)) echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>'; 
        ?>

        <?php if ($show_form): ?>
            <p>Please enter your new password.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?token=' . htmlspecialchars($token); ?>" method="post">
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block text-danger"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Reset Password">
                </div>
            </form>
        <?php else: ?>
             <div class="login-link">
                 <a href="login.php">Back to Login</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 
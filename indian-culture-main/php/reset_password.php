<?php
require_once "config.php";
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$error = '';
$success = '';
$password_err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['new_password'])) {
        $password_err = 'Please enter a new password.';
    } elseif (strlen($_POST['new_password']) < 6) {
        $password_err = 'Password must be at least 6 characters.';
    } elseif (empty($_POST['confirm_password'])) {
        $password_err = 'Please confirm the password.';
    } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
        $password_err = 'Passwords do not match.';
    }

    if (empty($password_err)) {
        $sql = "SELECT pr.user_id, u.email 
               FROM password_resets pr 
               JOIN users u ON pr.user_id = u.id 
               WHERE pr.token = ? AND pr.used = 0 
               AND pr.expires_at > NOW()";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $token);
            
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    $user_id = $row['user_id'];
                    $user_email = $row['email'];

                    // Update password
                    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                    if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                        $hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $user_id);
                        
                        if (mysqli_stmt_execute($update_stmt)) {
                            // Mark token as used
                            $mark_sql = "UPDATE password_resets SET used = 1 WHERE token = ?";
                            if ($mark_stmt = mysqli_prepare($conn, $mark_sql)) {
                                mysqli_stmt_bind_param($mark_stmt, "s", $token);
                                mysqli_stmt_execute($mark_stmt);
                                mysqli_stmt_close($mark_stmt);

                                // Send confirmation email
                                $mail = new PHPMailer(true);
                                try {
                                    $mail->isSMTP();
                                    $mail->Host = 'smtp.gmail.com';
                                    $mail->SMTPAuth = true;
                                    $mail->Username = 'parnaghosh628@gmail.com';
                                    $mail->Password = 'uyye kevn biwe fpkt';
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                    $mail->Port = 587;
                                    $mail->setFrom('no-reply@indianculture.com', 'Indian Culture');
                                    $mail->addAddress($user_email);
                                    $mail->isHTML(true);
                                    $mail->Subject = 'Password Reset Successful';
                                    $mail->Body = "<p>Your password has been successfully reset.</p>";
                                    $mail->send();
                                } catch (Exception $e) {
                                    error_log('Mailer Error: ' . $e->getMessage());
                                }

                                $success = "Password reset successful! You can now login with your new password.";
                            } else {
                                $error = "Error updating token status.";
                            }
                        } else {
                            $error = "Error updating password.";
                        }
                        mysqli_stmt_close($update_stmt);
                    } else {
                        $error = "Error preparing password update.";
                    }
                } else {
                    $error = "Invalid or expired token.";
                }
            } else {
                $error = "Error validating token.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error.";
        }
    }
} else {
    // Verify token on page load
    $sql = "SELECT 1 FROM password_resets WHERE token = ? AND used = 0 AND expires_at > NOW()";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 0) {
                $error = "Invalid or expired token. Please request a new password reset.";
            }
        } else {
            $error = "Error validating token.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Database error.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Indian Culture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .wrapper { max-width: 500px; margin: 0 auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 20px; }
        .alert { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <div class="text-center">
                <a href="login.php" class="btn btn-primary">Login Now</a>
            </div>
        <?php else: ?>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                    <small class="text-muted">Password must be at least 6 characters long.</small>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                    <?php if (!empty($password_err)): ?>
                        <small class="text-danger"><?php echo htmlspecialchars($password_err); ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Reset Password">
                </div>
                <p class="text-center">
                    <a href="forgot_password.php">Request new reset link</a>
                </p>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

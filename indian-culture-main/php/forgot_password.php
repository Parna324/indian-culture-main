<?php
require_once "config.php";
// We need the autoloader from the PARENT directory relative to this php/ folder
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = "";
$email_err = "";
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // If no validation errors
    if (empty($email_err)) {
        // Check if email exists in the database (assuming 'users' table)
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $user_id);
                    mysqli_stmt_fetch($stmt);

                    // Generate token and expiry
                    $token = bin2hex(random_bytes(50)); // Secure random token
                    $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token valid for 1 hour

                    // Store token in password_resets table
                    $sql_insert = "INSERT INTO password_resets (user_id, email, token, expires_at) VALUES (?, ?, ?, ?)";
                    if ($stmt_insert = mysqli_prepare($conn, $sql_insert)) {
                        mysqli_stmt_bind_param($stmt_insert, "isss", $user_id, $email, $token, $expires_at);

                        if (mysqli_stmt_execute($stmt_insert)) {
                            // --- Send Email ---
                            // Get the current URL path and construct the reset link
                            $reset_link = "http://localhost/indian-culture-main/indian-culture-main/php/reset_password.php?token=" . $token;

                            $subject = "Password Reset Request - Indian Culture";
                            $mail_body = "
                            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                                <h2 style='color: #FF9933; border-bottom: 2px solid #FF9933; padding-bottom: 10px;'>Password Reset Request</h2>
                                <div style='background: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                                    <p style='color: #333;'>You requested a password reset for your Indian Culture account.</p>
                                    <p style='color: #333;'>Click the button below to reset your password. This link is valid for 1 hour:</p>
                                    <p style='text-align: center; margin: 30px 0;'>
                                        <a href='" . $reset_link . "' style='background-color: #FF9933; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Reset Password</a>
                                    </p>
                                    <p style='color: #666; font-size: 0.9em;'>If the button doesn't work, copy and paste this link into your browser:</p>
                                    <p style='background: #fff; padding: 10px; border-left: 4px solid #FF9933; font-size: 0.9em; word-break: break-all;'>
                                        " . $reset_link . "
                                    </p>
                                </div>
                                <p style='color: #666; font-size: 0.9em; border-top: 1px solid #eee; padding-top: 15px; margin-top: 20px;'>
                                    If you did not request this password reset, please ignore this email and your password will remain unchanged.
                                </p>
                            </div>";
                            

                            // --- Use PHPMailer ---

                            $mail = new PHPMailer(true);
                            try {
                                // --- SERVER SETTINGS ---
                                // IMPORTANT: Replace with your actual SMTP credentials!
                                // Example for Gmail (Requires: Gmail account, "App Password" generated in Google Account settings)
                                $mail->isSMTP();
                                $mail->Host       = 'smtp.gmail.com';             // Set the SMTP server to send through
                                $mail->SMTPAuth   = true;                        // Enable SMTP authentication
                                $mail->Username   = 'parnaghosh628@gmail.com';       // SMTP username (Your full Gmail address)
                                $mail->Password   = 'uyye kevn biwe fpkt';   // SMTP password (Your generated App Password)
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                                $mail->Port       = 587;                         // TCP port to connect to

                                // --- RECIPIENTS ---
                                $mail->setFrom('no-reply@yourdomain.com', 'Indian Culture'); // SET YOUR "FROM" ADDRESS AND NAME
                                $mail->addAddress($email); // Add a recipient (uses the email from the form)

                                // --- CONTENT ---
                                $mail->isHTML(true); // Set email format to HTML
                                $mail->Subject = $subject;
                                $mail->Body    = $mail_body;
                                $mail->AltBody = strip_tags($mail_body); // For non-HTML mail clients

                                $mail->send();
                                $message = "Password reset instructions have been sent to your email address.";
                            } catch (Exception $e) {
                                $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}. Please contact support.";
                                // Log the error for debugging: error_log("PHPMailer error: " . $e->getMessage());
                            }


                        } else {
                            $error = "Oops! Something went wrong storing the reset token. Please try again.";
                        }
                        mysqli_stmt_close($stmt_insert);
                    } else {
                        $error = "Oops! Something went wrong preparing to store the reset token. Please try again.";
                    }
                } else {
                    // Email not found, show generic message for security
                    $message = "If an account with that email exists, reset instructions have been sent.";
                }
            } else {
                $error = "Oops! Something went wrong executing the email check. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Oops! Something went wrong preparing the email check. Please try again later.";
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
    <title>Forgot Password - Indian Culture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #FF9933;    /* Saffron from Indian flag */
            --secondary-color: #138808; /* Green from Indian flag */
            --accent-color: #000080;   /* Navy blue representing wisdom */
            --background-color: #f8f3e9; /* Soft cream background */
        }
        body {
            font-family: 'Arial', sans-serif;
            background: var(--background-color);
            background-image: linear-gradient(45deg, rgba(255, 153, 51, 0.1), rgba(19, 136, 8, 0.1));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .wrapper {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
            border-top: 5px solid var(--primary-color);
        }
        .wrapper h2 {
            margin-bottom: 20px;
            color: var(--accent-color);
            font-size: 2rem;
            font-weight: bold;
            position: relative;
            padding-bottom: 10px;
        }
        .wrapper h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary-color);
        }
        .wrapper p {
            margin-bottom: 25px;
            color: #555;
            line-height: 1.6;
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        .form-group label {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.2);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #e68a00;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 153, 51, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .alert {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            font-weight: 500;
        }
        .alert-success {
            background-color: rgba(19, 136, 8, 0.1);
            border: 1px solid var(--secondary-color);
            color: var(--secondary-color);
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border: 1px solid #dc3545;
            color: #dc3545;
        }
        .login-link {
            margin-top: 25px;
        }
        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }
        .login-link a:hover {
            color: var(--accent-color);
            transform: translateX(5px);
        }
        .help-block {
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Forgot Your Password?</h2>
        <p>Enter your email address below and we will send you instructions to reset your password.</p>

        <?php
        if (!empty($message)) echo '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
        if (!empty($error)) echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <span class="help-block text-danger"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Send Reset Link">
            </div>
            <div class="login-link">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>
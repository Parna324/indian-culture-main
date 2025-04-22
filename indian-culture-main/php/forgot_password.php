<?php
require_once "config.php";
// We need the autoloader from the PARENT directory relative to this php/ folder
require __DIR__ . '/../../../vendor/autoload.php';
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
                            $reset_link = "http://localhost/indian-culture-main/php/reset_password.php?token=" . $token; // Adjust URL if needed

                            $subject = "Password Reset Request - Indian Culture";
                            $mail_body = "<p>You requested a password reset for your Indian Culture account.</p>";
                            $mail_body .= "<p>Click the following link to reset your password. This link is valid for 1 hour:</p>";
                            $mail_body .= "<p><a href='" . $reset_link . "'>" . $reset_link . "</a></p>";
                            $mail_body .= "<p>If you did not request this, please ignore this email.</p>";

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
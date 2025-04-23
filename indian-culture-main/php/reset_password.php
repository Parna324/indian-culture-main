<?php
require_once "config.php"; // DB connection

$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$new_password = '';
$confirm_password = '';
$password_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($new_password) || strlen($new_password) < 6) {
        $password_err = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
        $password_err = "Passwords do not match.";
    }

    if (empty($password_err)) {
        $sql = "SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $user_id);
                mysqli_stmt_fetch($stmt);

                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $user_id);
                    if (mysqli_stmt_execute($update_stmt)) {
                        // Delete the token
                        mysqli_query($conn, "DELETE FROM password_resets WHERE token = '$token'");
                        $success = "Password updated successfully! You can now <a href='login.php'>login</a>.";
                    } else {
                        $error = "Something went wrong. Please try again.";
                    }
                    mysqli_stmt_close($update_stmt);
                }
            } else {
                $error = "Invalid or expired token.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color: #f4f4f4;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title text-center">Reset Your Password</h4>
                        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                        <?php if (empty($success)): ?>
                        <form action="" method="post">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                                <small class="text-danger"><?php echo $password_err; ?></small>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-block" value="Reset Password">
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

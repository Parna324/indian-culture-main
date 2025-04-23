<?php
$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Send to your email
        $to = "parnaghosh628@gmail.com"; // Your email
        $subject = "New Contact Message - Heritage of India";
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $body = "You received a new message:\n\n";
        $body .= "Name: $name\n";
        $body .= "Email: $email\n\n";
        $body .= "Message:\n$message\n";

        if (mail($to, $subject, $body, $headers)) {
            $success = "Message sent successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Heritage of India</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: sans-serif;
        }
        .container {
            margin-top: 60px;
            max-width: 600px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #FF9933;
            border-color: #FF9933;
        }
        .btn-primary:hover {
            background-color: #E68A00;
            border-color: #E68A00;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center">Contact Us</h3>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label>Your Name</label>
            <input type="text" name="name" required class="form-control">
        </div>
        <div class="form-group">
            <label>Your Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="form-group">
            <label>Your Message</label>
            <textarea name="message" rows="5" required class="form-control"></textarea>
        </div>
        <input type="submit" class="btn btn-primary btn-block" value="Send Message">
    </form>
</div>

</body>
</html>

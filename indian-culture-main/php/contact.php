<?php
require_once "config.php";
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'parnaghosh628@gmail.com';
            $mail->Password = 'uyye kevn biwe fpkt';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@indianculture.com', 'Indian Culture');
            $mail->addAddress('parnaghosh628@gmail.com');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = !empty($subject) ? "Contact Form: $subject" : "New Contact Form Message";
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <h2 style='color: #FF9933; border-bottom: 2px solid #FF9933; padding-bottom: 10px;'>New Contact Message</h2>
                    <div style='background: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                        <p><strong>From:</strong> $name</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Subject:</strong> $subject</p>
                        <p><strong>Message:</strong></p>
                        <p style='background: #fff; padding: 15px; border-left: 4px solid #FF9933;'>$message</p>
                    </div>
                </div>";

            $mail->send();
            $success = "Thank you for your message! We'll get back to you soon.";
        } catch (Exception $e) {
            error_log("Mailer Error: " . $e->getMessage());
            $error = "Sorry, there was an error sending your message. Please try again later.";
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
        :root {
            --primary-color: #FF9933;    /* Saffron from Indian flag */
            --secondary-color: #138808; /* Green from Indian flag */
            --accent-color: #000080;   /* Navy blue representing wisdom */
        }
        body {
            background: url('../images/contact-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 153, 51, 0.95), 
                rgba(19, 136, 8, 0.85)
            );
            z-index: 0;
        }
        .container {
            margin-top: 40px;
            margin-bottom: 40px;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .section-title {
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: bold;
            position: relative;
            padding-bottom: 15px;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-color);
        }
        .contact-info {
            background: rgba(248, 249, 250, 0.8);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }
        .contact-info i {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .form-group label {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 153, 51, 0.25);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #e68a00;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 153, 51, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .alert {
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container">
    <h1 class="section-title">Get in Touch</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-5">
            <div class="contact-info">
                <h4 class="mb-4">Contact Information</h4>
                <p><i class="fas fa-map-marker-alt"></i> 123 Heritage Street, Kolkata, India</p>
                <p><i class="fas fa-phone"></i> +91 1234567890</p>
                <p><i class="fas fa-envelope"></i> info@indianculture.com</p>
                <p><i class="fas fa-clock"></i> Monday - Friday: 9:00 AM - 6:00 PM</p>
            </div>
            <div class="social-links mt-4">
                <h4 class="mb-3">Follow Us</h4>
                <a href="#" class="btn btn-outline-primary mr-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="btn btn-outline-primary mr-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="btn btn-outline-primary mr-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="btn btn-outline-primary"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="col-md-7">
            <form method="post" action="" class="contact-form">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Your Name</label>
                    <input type="text" name="name" required class="form-control" placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Your Email</label>
                    <input type="email" name="email" required class="form-control" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-pen"></i> Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="Enter subject (optional)">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Your Message</label>
                    <textarea name="message" rows="5" required class="form-control" placeholder="Enter your message"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

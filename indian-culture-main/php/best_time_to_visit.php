<?php
session_start(); // Keep session access if needed
require_once "config.php"; // Include DB config
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../../vendor/autoload.php';

// --- Fetch States for Dropdown ---
$states = [];
$sql_states = "SELECT id, state_name FROM state_visits ORDER BY state_name ASC";
if ($result_states = mysqli_query($conn, $sql_states)) {
    while ($row = mysqli_fetch_assoc($result_states)) {
        $states[] = $row;
    }
    mysqli_free_result($result_states);
} else {
    // Handle error fetching states if needed
    echo "Error fetching states: " . mysqli_error($conn);
}

$selected_state_id = null;
$selected_state_name = null;
$result_info = null;
$error = null;
$avg_rating = null; // For later use

// Initialize feedback variables
$feedback_message = "";
$feedback_error = "";
$feedback_success = "";

// Handle feedback form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback_submit'])) {
    $state_name = isset($_POST['state_name']) ? trim($_POST['state_name']) : '';
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $suggestion = isset($_POST['suggestion']) ? trim($_POST['suggestion']) : '';
    $user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    
    // Basic validation
    if (empty($state_name) || empty($suggestion) || empty($user_email) || $rating < 1 || $rating > 5) {
        $feedback_error = "Please fill in all fields and provide a valid rating.";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $feedback_error = "Please provide a valid email address.";
    } else {
        // Get state_id from the state name
        $state_query = "SELECT id FROM states WHERE name = ?";
        $state_id = null;
        
        if ($stmt_state = mysqli_prepare($conn, $state_query)) {
            mysqli_stmt_bind_param($stmt_state, "s", $state_name);
            mysqli_stmt_execute($stmt_state);
            mysqli_stmt_bind_result($stmt_state, $state_id);
            mysqli_stmt_fetch($stmt_state);
            mysqli_stmt_close($stmt_state);
        }
        
        if (!$state_id) {
            // If state not found, create it
            $insert_state = "INSERT INTO states (name) VALUES (?)";
            if ($stmt_insert = mysqli_prepare($conn, $insert_state)) {
                mysqli_stmt_bind_param($stmt_insert, "s", $state_name);
                mysqli_stmt_execute($stmt_insert);
                $state_id = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt_insert);
            }
        }
        
        // Save feedback to database
        $sql = "INSERT INTO visit_feedback (state_id, rating, comment) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iis", $state_id, $rating, $suggestion);
            
            if (mysqli_stmt_execute($stmt)) {
                // Now send email
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'parnaghosh628@gmail.com';
                    $mail->Password = 'uyye kevn biwe fpkt';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('no-reply@indianculture.com', 'Indian Culture Website');
                    $mail->addAddress('parnaghosh628@gmail.com');
                    $mail->addReplyTo($user_email); // Allow replying to the user

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = "New Feedback for $state_name - Best Time to Visit";
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                            <h2 style='color: #FF9933; border-bottom: 2px solid #FF9933; padding-bottom: 10px;'>New Visitor Feedback</h2>
                            <div style='background: #f9f9f9; padding: 20px; border-radius: 5px;'>
                                <p><strong>State:</strong> " . htmlspecialchars($state_name) . "</p>
                                <p><strong>Rating:</strong> " . str_repeat('★', $rating) . str_repeat('☆', 5-$rating) . " ($rating/5)</p>
                                <p><strong>Feedback:</strong></p>
                                <p style='background: #fff; padding: 15px; border-left: 4px solid #FF9933;'>" . nl2br(htmlspecialchars($suggestion)) . "</p>
                                <p><strong>From:</strong> <a href='mailto:" . htmlspecialchars($user_email) . ">" . htmlspecialchars($user_email) . "</a></p>
                            </div>
                            <p style='color: #666; font-size: 0.9em; margin-top: 20px;'>This feedback was submitted from the Indian Culture Website's Best Time to Visit section.</p>
                        </div>";

                    $mail->send();
                    $feedback_success = "Thank you! Your feedback has been saved and sent successfully.";
                } catch (Exception $e) {
                    // Email failed but database save was successful
                    $feedback_success = "Thank you! Your feedback has been saved. However, there was an issue sending the email notification.";
                }
            } else {
                $feedback_error = "Sorry, there was an error saving your feedback. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $feedback_error = "Sorry, there was a database error. Please try again later.";
        }
    }
    
    // Database connection will be closed automatically at the end of the script
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['state_id'])) {
        $selected_state_id = (int)$_POST['state_id'];

        // --- Fetch selected state info from DB ---
        $sql_details = "SELECT state_name, best_time, description, culture FROM state_visits WHERE id = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql_details)) {
            mysqli_stmt_bind_param($stmt, "i", $selected_state_id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $db_state_name, $db_best_time, $db_description, $db_culture);
                if (mysqli_stmt_fetch($stmt)) {
                    $selected_state_name = $db_state_name;
                    $result_info = [
                        "best_time" => $db_best_time,
                        "description" => $db_description,
                        "culture" => $db_culture
                    ];
                    // TODO: Fetch average rating here later
                } else {
                    $error = "Information not found for the selected state ID.";
                }
            } else {
                $error = "Error executing query: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
             $error = "Error preparing query: " . mysqli_error($conn);
        }

    } else {
        $error = "Please select a state.";
    }
}

mysqli_close($conn); // Close connection

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Time to Visit - Indian Culture</title>
    <!-- Include existing stylesheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css"> <!-- Adjust path as needed -->
    <link rel="stylesheet" href="../css/font-awesome.min.css"> <!-- Adjust path -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700|Montserrat:400,500,600,700&display=swap" rel="stylesheet">
    <!-- Add specific styles for this page later in style.css or here -->
    <style>
        /* Basic page styling - enhance in style.css later */
        body.best-time-body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../images/india1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: 'Raleway', sans-serif;
        }
        .planner-container {
            padding-top: 120px; /* Adjust based on header height */
            padding-bottom: 60px;
            min-height: 100vh;
        }
        .planner-title {
            text-align: center;
            margin-bottom: 40px;
            font-family: 'Montserrat', sans-serif;
            font-size: 36px;
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        .state-form {
            max-width: 600px;
            margin: 0 auto 40px auto;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.4); /* Darker semi-transparent bg */
            /* backdrop-filter: blur(5px); */ /* Temporarily remove blur */
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1); /* Softer border */
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .state-form label {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
            color: #eee; /* Lighter label color */
        }
        .state-form .form-control {
            /* Explicitly style the select */
            display: block;
            width: 100%;
            height: calc(1.5em + .75rem + 2px); /* Default BS height */
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff; /* Reset to plain white */
            background-clip: padding-box;
            border: 1px solid #ced4da; /* Standard BS border */
            border-radius: .25rem; /* Standard BS radius */
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .state-form .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #FF9933; /* Saffron focus border */
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(255, 153, 51, 0.25); /* Saffron focus shadow */
        }
        .state-form .btn-submit {
            background-color: #FF9933;
            border-color: #FF9933;
            color: white;
            font-weight: 600;
            padding: 10px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
         .state-form .btn-submit:hover {
            background-color: #E68A00;
            border-color: #E68A00;
            transform: translateY(-2px);
        }
        .result-card {
            max-width: 700px;
            margin: 40px auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            display: none; /* Hidden by default */
        }
        .result-card.show {
            display: block; /* Shown when results are available */
        }
        .result-card h3 {
            color: #FFD700; /* Gold */
            margin-bottom: 15px;
            font-weight: 700;
        }
        .result-card h4 {
            color: #FF9933; /* Saffron */
            font-size: 1.1rem;
            margin-top: 20px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .result-card p {
            color: #eee;
            line-height: 1.7;
            margin-bottom: 10px;
        }
        
        /* Feedback Form Styles */
        .feedback-form {
            margin-top: 20px;
        }
        .feedback-form .form-group {
            margin-bottom: 20px;
        }
        .feedback-form label {
            color: #eee;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        .feedback-form .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .feedback-form .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: #FF9933;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255, 153, 51, 0.25);
        }
        .feedback-form textarea {
            resize: vertical;
        }
        
        /* Star Rating Styles */
        .rating-input {
            display: inline-flex;
            flex-direction: row-reverse;
            gap: 5px;
            margin-bottom: 15px;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            font-size: 25px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #FFD700;
        }
        .rating-input label:hover:before,
        .rating-input label:hover ~ label:before,
        .rating-input input:checked ~ label:before {
            content: '★';
            position: absolute;
        }

        /* Submit Button Styles */
        .feedback-submit-btn {
            background-color: #FF9933;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: auto;
            min-width: 200px;
        }
        .feedback-submit-btn:hover {
            background-color: #E68A00;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 153, 51, 0.3);
        }
        .feedback-submit-btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="best-time-body">

    <?php include 'header.php'; // Include the navigation bar ?>

    <div class="container planner-container">
        <h1 class="planner-title">Best Time to Visit Planner</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="state-form">
            <div class="form-group">
                <label for="stateSelect">Select a State:</label>
                <select class="form-control" id="stateSelect" name="state_id">
                    <option value="">-- Select State --</option>
                    <?php
                        if (empty($states)) {
                            echo '<option value="" disabled>No states found in database!</option>';
                        } else {
                            foreach ($states as $state) {
                                $selected_attr = ($selected_state_id == $state['id']) ? ' selected' : '';
                                echo "<option value=\"" . $state['id'] . "\"$selected_attr>" . htmlspecialchars($state['state_name']) . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="text-center">
                 <button type="submit" class="btn btn-submit">Find Best Time</button>
            </div>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Results Area -->
        <div class="result-card <?php echo ($result_info) ? 'show' : ''; ?>">
            <?php if ($result_info): ?>
                <h3><?php echo htmlspecialchars($selected_state_name); ?></h3>
                
                <h4><i class="fa fa-calendar"></i> Best Time to Visit:</h4>
                <p><?php echo htmlspecialchars($result_info['best_time']); ?></p>
                
                <h4><i class="fa fa-info-circle"></i> Why Visit Then?</h4>
                <p><?php echo htmlspecialchars($result_info['description']); ?></p>
                
                <h4><i class="fa fa-star"></i> Cultural Highlights:</h4>
                <p><?php echo htmlspecialchars($result_info['culture']); ?></p>

                <hr style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 30px;">
                <div id="feedback-section" style="margin-top: 20px;">
                    <h5>Rate this information / Suggest edits:</h5>
                    
                    <?php if ($feedback_success): ?>
                        <div class="alert alert-success"><?php echo $feedback_success; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($feedback_error): ?>
                        <div class="alert alert-danger"><?php echo $feedback_error; ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="feedback-form">
                        <input type="hidden" name="state_name" value="<?php echo htmlspecialchars($selected_state_name); ?>">
                        
                        <div class="form-group">
                            <label>Your Rating:</label>
                            <div class="rating-input">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>">
                                    <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="suggestion">Your Suggestions/Feedback:</label>
                            <textarea name="suggestion" id="suggestion" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="user_email">Your Email:</label>
                            <input type="email" name="user_email" id="user_email" class="form-control" required>
                        </div>

                        <button type="submit" name="feedback_submit" class="feedback-submit-btn">Send Feedback</button>
                    </form>
                </div>
                <div id="average-rating" style="margin-top: 10px;">
                    <!-- Avg rating display will go here later -->
                </div>
            <?php endif; ?>
        </div>

    </div> 

    <!-- Include JS if needed -->
    <!-- <script src="../js/jquery-3.1.1.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->
</body>
</html> 
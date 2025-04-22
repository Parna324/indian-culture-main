<?php
session_start(); // Keep session access if needed
require_once "config.php"; // Include DB config

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
                    <p><i>Feedback form coming soon...</i></p>
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
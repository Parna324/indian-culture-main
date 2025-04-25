<!DOCTYPE html>
<html lang="en">
<head>
    <title>Indian Culture</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/800px-Flag_of_India.svg.png?20111003033457">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,600i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,500i,600,600i" rel="stylesheet">
</head>
<body>

    <nav>
        <div class="container">
            <div class="menu-par">
                <div class="logo-par">
                    <h2>Indian Culture</h2>
                </div>
                <div class="nav">
                    <ul>
                        <li><a href="http://localhost/indian-culture-main/indian-culture-main/">Home</a></li>
                        <li><a href="http://localhost/indian-culture-main/indian-culture-main/#slides_parent">Heritage</a></li>
                        <li><a href="http://localhost/indian-culture-main/indian-culture-main/#explore-fiji">Explore</a></li>
                        <li><a href="http://localhost/indian-culture-main/indian-culture-main/#heritage-gallery">Gallery</a></li>
                        <li><a href="http://localhost/indian-culture-main/indian-culture-main/#blog">Blog</a></li>
                        <li><a href="http://localhost/indian-culture-main/indian-culture-main/php/best_time_to_visit.php">Visit Planner</a></li>
                        <li><a href="http://localhost/indian-culture-main/indian-culture-main/php/contact.php">Contact Us</a></li>
                        <?php
                        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                            echo '<li><a href="http://localhost/indian-culture-main/indian-culture-main/php/welcome.php">Profile</a></li>';
                            echo '<li><a href="http://localhost/indian-culture-main/indian-culture-main/php/logout.php">Logout</a></li>';
                        } else {
                            echo '<li><a href="http://localhost/indian-culture-main/indian-culture-main/php/login.php">Login</a></li>';
                            echo '<li><a href="http://localhost/indian-culture-main/indian-culture-main/php/register.php">Register</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="toggle-btn">
                    <i class="fa fa-bars"></i>
                </div>
            </div>
        </div>
    </nav>

    <script>
    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.querySelector('nav').classList.add('nav-fixed');
        } else {
            document.querySelector('nav').classList.remove('nav-fixed');
        }
    };

    document.querySelector('.toggle-btn').addEventListener('click', function() {
        document.querySelector('.nav').classList.toggle('nav-active');
        this.querySelector('i').classList.toggle('nav-active');
    });
    </script>

</body>
</html>

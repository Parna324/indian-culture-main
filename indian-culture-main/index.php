<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Indian Culture</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />

    <!-- Owl carousel CSS -->
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/owl.theme.default.min.css" />

    <link type="image" rel="icon" href="https://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/800px-Flag_of_India.svg.png?20111003033457" />
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,600i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,500i,600,600i" rel="stylesheet">
</head>

<body>

    <div id="arrow">
        <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </div>

    <?php include 'php/header.php'; ?>

    <header>
        <div class="container">
            <div class="home_header_info">
                <h1 class="typewrite" data-period="2000" data-type='[ "Incredible!ndia", "Dekho Apna Desh!", " Explore the Indian Culture and Heritage." ]'></h1>
                <p>India is a country dotted with stunning wildlife diversity, and rich traditions. While the Western coast greets you with mouth-watering delicacies, the East part invites you to experience its greenery.</p>
                <a href="https://indianculture.gov.in/">Read More</a>
            </div>
        </div>
        <div class="video">
            <video autoplay muted loop id="myVideo">
                <source type="video/mp4" src="images/v1.mp4" />
            </video>
        </div>
    </header>

    <Section id="welcome_Sec">
        <div class="container">
            <h2><span>Where science meets the sacred!
            </span></h2>
            <p>Witness the grand Architecture! How about taking your kids on an exploration of India's heritage this holiday season?</p>
        </div>
    </Section>

    <Section id="slides_parent">
        <div class="container">
            <div class="slides">
                <div class="slide_1">
                    <div class="slide_info">
                        <p>Modhera Sun Temple </p>
                    </div>
                </div>
                <div class="slide_1 slide_2">
                    <div class="slide_info">
                        <p>Adi Annamalai temple</p>
                    </div>
                </div>
                <div class="slide_1 slide_3">
                    <div class="slide_info">
                        <p>Western Ghats</p>
                    </div>
                </div>
                <div class="slide_1 slide_4">
                    <div class="slide_info">
                        <p>Red Fort</p>
                    </div>
                </div>
                <div class="slide_1 slide_5">
                    <div class="slide_info">
                        <p>Chhatrapati Shivaji Terminus </p>
                    </div>
                </div>
                <div class="slide_1 slide_6">
                    <div class="slide_info">
                        <p>Bandipur National Park</p>
                    </div>
                </div>
                <div class="slide_1 slide_7">
                    <div class="slide_info">
                        <p>Ganga Aarti</p>
                    </div>
                </div>
            </div>
        </div>
    </Section>

    <section id="explore-fiji">
       <center><h1> Locate top heritage sites in India!</h1></center> 
       <br><br>
       <center><p><h4>This map depicts the top ten historical sites in India in terms of their geographical location.<br> Click on any pointer to learn more about the place and to appreciate the diversity!</h4></p></center>
     <br><br><center> <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1rWVL7-ObutJZd9PlLtzqS4kounhEzHg&ehbc=2E312F" width="60%" height="480"></iframe></center>
    </section>


    <section id="gallery">
        <div class="container">
            <div class="row">
                <div align="center">
                    <button class="btn filter-button active" data-filter="all">All</button>
                    <button class="btn filter-button" data-filter="hdpe">Foods and Culture</button>
                    <button class="btn filter-button" data-filter="sprinkle">Heritage Sites</button>
                    <button class="btn filter-button" data-filter="spray">Paradise</button>
                </div>

                <div class="filter-gal-par">
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hdpe">
                        <img src="images/fiji-surprise/f1.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter sprinkle">
                        <img src="images/fiji-surprise/f2.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hdpe">
                        <img src="images/fiji-surprise/f3.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter spray">
                        <img src="images/fiji-surprise/h1.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter sprinkle">
                        <img src="images/fiji-surprise/h2.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter spray">
                        <img src="images/fiji-surprise/h3.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter sprinkle">
                        <img src="images/fiji-surprise/p1.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hdpe">
                        <img src="images/fiji-surprise/p2.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter spray">
                        <img src="images/fiji-surprise/p3.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hdpe">
                        <img src="images/fiji-surprise/g1.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter sprinkle">
                        <img src="images/fiji-surprise/g2.jpg" class="img-responsive">
                    </div>

                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter spray">
                        <img src="images/fiji-surprise/g3.jpg" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fiji-holyday">
        <div class="container">
            <h3>The Great Indian Cities</h3>
            <ul class="card-wrapper">
  <li class="card">
    <img src='images/slider/delhi.jpg' alt=''>
    <h3><a href="">Delhi</a></h3>
    <p>The Capital of India</p>
  </li>
  <li class="card">
    <img src='images/slider/mumbai.jpg' alt=''>
    <h3><a href="">Mumbai</a></h3>
    <p>The financial Capital Of India</p>
  </li>
  <li class="card">
    <img src='images/slider/banglore.jpg' alt=''>
    <h3><a href="">Banglore</a></h3>
    <p>Silicon Valley Of India</p>
  </li>
</ul>
        </div>
    </section>

 <?php include "./php/blog.php" ?>

    <footer>
        <div class="container">
            <div class="footer-par">
                <div class="footer-1">
                    <h2>Travel with us</h2>
                </div>
                <div class="footer-2 fot-info">
                    <h3>Dekho Apna Desh!</h3>
                    <ul>
                        <li><a href="https://indianculture.gov.in/">Indian Culture</a></li>
                        <li><a href="https://www.incredibleindia.org/content/incredible-india-v2/en.html">Incredible!ndia</a></li>
                        <li><a href="http://www.indiaculture.nic.in/hi">Ministry of Culture</a></li>
                        <li><a href="https://swachhbharatmission.gov.in/SBMCMS/index.htm">Swachh Bharat</a></li>
                    </ul>
                </div>
                <div class="footer-3 fot-info">
                    <h3>Visit For More</h3>
                    <ul>
                        <li><a href="https://www.mygov.in/">My Goverment</a></li>
                        <li><a href="https://www.digitalindia.gov.in/">Digital India</a></li>
                        <li><a href="https://tourism.gov.in/">Ministry of Tourism</a></li>
                    </ul>
                </div>
                <div class="footer-4 fot-info">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><a href="https://github.com/"></a>Parna ghosh</li>
                        <li><a href="https://github.com/">tanvi mishra</a></li>
                        <li><a href="https://github.com/">Amisha singh</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <p class="copyright">Developed by Parna ghosh and Team</p>
    </footer>


    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/extrenaljq.js"></script>
</body>

</html>

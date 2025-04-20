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
    
    <!-- Lightbox2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />

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

    <!-- ======= New Heritage Gallery Section ======= -->
    <section id="heritage-gallery" class="heritage-gallery section-bg">
        <div class="container">

            <div class="section-title text-center mb-5">
                <h2>Explore The Heritage of India</h2>
                <p>Discover the rich culture, history, and beauty of India, one state at a time.</p>
            </div>

            <!-- --- West Bengal Section --- -->
            <div class="state-section west-bengal mb-5 pb-5 border-bottom">
                <!-- Background Video -->
                <div class="state-bg-video">
                    <video autoplay muted loop playsinline>
                        <source src="images/westbengal/west1.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <!-- Content Row -->
                <div class="row state-content-row align-items-center mb-4">
                    <!-- Text Column -->
                    <div class="col-lg-6 text-container ps-lg-5">
                        <h3>West Bengal</h3>
                        <p>Known as the cultural capital of India, West Bengal offers a unique blend of colonial architecture, vibrant festivals like Durga Puja, rich literature, and the serene beauty of the Sundarbans mangrove forest.</p>
                        <p>From the bustling streets of Kolkata to the tea gardens of Darjeeling, experience the artistic soul and historical depth of this eastern state.</p>
                    </div>
                     <!-- Placeholder Column (to maintain layout balance if needed, or remove if text/images span full width) -->
                     <div class="col-lg-6"></div>
                </div>
                <!-- Image Grid -->
                <div class="image-grid row state-content-row">
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/westbengal/p1.jpg" class="img-fluid" alt="West Bengal Image 1"></div>
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/westbengal/p2.jpg" class="img-fluid" alt="West Bengal Image 2"></div>
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/westbengal/p3.jpg" class="img-fluid" alt="West Bengal Image 3"></div>
                    <div class="col-lg col-md-6 col-6 img-item"><img src="images/westbengal/p4.jpg" class="img-fluid" alt="West Bengal Image 4"></div>
                    <div class="col-lg col-md-6 col-12 img-item"><img src="images/westbengal/p5.jpg" class="img-fluid" alt="West Bengal Image 5"></div>
                </div>
            </div>
            <!-- --- End West Bengal Section --- -->

            <!-- --- Uttar Pradesh Section --- -->
            <div class="state-section uttar-pradesh mb-5 pb-5 border-bottom">
                 <!-- Background Video -->
                 <div class="state-bg-video">
                    <video autoplay muted loop playsinline>
                        <source src="images/uttarpradesh/west2.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <!-- Content Row - Reversed Order -->
                <div class="row state-content-row align-items-center mb-4">
                    <!-- Text Column (Order 1 on large screens) -->
                    <div class="col-lg-6 text-container order-lg-1 pe-lg-5">
                        <h3>Uttar Pradesh</h3>
                        <p>Home to iconic landmarks like the Taj Mahal and the holy city of Varanasi on the banks of the Ganges, Uttar Pradesh is the heartland of India. It boasts a rich history, diverse cultures, and spiritual significance.</p>
                        <p>Explore ancient temples, Mughal architecture, and vibrant religious ceremonies in India's most populous state.</p>
                    </div>
                     <!-- Placeholder Column (Order 2 on large screens) -->
                     <div class="col-lg-6 order-lg-2"></div>
                </div>
                <!-- Image Grid -->
                <div class="image-grid row state-content-row">
                    <!-- Using available images from uttarpradesh folder -->
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/uttarpradesh/IMG-20250420-WA0016.jpg" class="img-fluid" alt="Uttar Pradesh Image 1"></div>
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/uttarpradesh/IMG-20250420-WA0017.jpg" class="img-fluid" alt="Uttar Pradesh Image 2"></div>
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/uttarpradesh/IMG-20250420-WA0018.jpg" class="img-fluid" alt="Uttar Pradesh Image 3"></div>
                    <div class="col-lg col-md-6 col-6 img-item"><img src="images/uttarpradesh/IMG-20250420-WA0019.jpg" class="img-fluid" alt="Uttar Pradesh Image 4"></div>
                    <div class="col-lg col-md-6 col-12 img-item"><img src="images/uttarpradesh/IMG-20250420-WA0027.jpg" class="img-fluid" alt="Uttar Pradesh Image 5"></div>
                </div>
            </div>
            <!-- --- End Uttar Pradesh Section --- -->

             <!-- --- Bihar Section --- -->
            <div class="state-section bihar mb-5 pb-5 border-bottom">
                 <!-- Background Video -->
                 <div class="state-bg-video">
                    <video autoplay muted loop playsinline>
                        <source src="images/bihar/b1.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <!-- Content Row - Original Order -->
                <div class="row state-content-row align-items-center mb-4">
                    <!-- Placeholder Column -->
                     <div class="col-lg-6"></div>
                     <!-- Text Column -->
                    <div class="col-lg-6 text-container ps-lg-5">
                        <h3>Bihar</h3>
                        <p>Birthplace of Buddhism and Jainism, Bihar holds immense historical and spiritual significance. It is home to ancient sites like Nalanda University ruins and Bodh Gaya, where Buddha attained enlightenment.</p>
                        <p>Discover the state's rich past, vibrant Madhubani art, and unique cultural traditions along the fertile plains of the Ganges.</p>
                    </div>
                </div>
                <!-- Image Grid -->
                <div class="image-grid row state-content-row">
                    <!-- Using available images from bihar folder -->
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/bihar/IMG-20250420-WA0020.jpg" class="img-fluid" alt="Bihar Image 1"></div>
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/bihar/IMG-20250420-WA0021.jpg" class="img-fluid" alt="Bihar Image 2"></div>
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/bihar/IMG-20250420-WA0022.jpg" class="img-fluid" alt="Bihar Image 3"></div>
                    <div class="col-lg col-md-6 col-6 img-item"><img src="images/bihar/IMG-20250420-WA0023.jpg" class="img-fluid" alt="Bihar Image 4"></div>
                    <div class="col-lg col-md-6 col-12 img-item"><img src="images/bihar/IMG-20250420-WA0024.jpg" class="img-fluid" alt="Bihar Image 5"></div>
                    <div class="col-lg col-md-4 col-6 img-item"><img src="images/bihar/IMG-20250420-WA0026.jpg" class="img-fluid" alt="Bihar Image 6"></div> 
                </div>
            </div>
            <!-- --- End Bihar Section --- -->

        </div>
    </section>
    <!-- ======= End Heritage Gallery Section ======= -->

    <section id="fiji-holyday">
        <div class="container">
            <h3 class="section-heading">The Great Indian Cities</h3>
            <ul class="card-wrapper">
              <li class="card">
                <div class="card-image-container">
                    <img src='images/slider/delhi.jpg' alt=''>
                </div>
                <div class="card-content">
                    <h3 class="city-name"><a href="">Delhi</a></h3>
                    <p class="city-description">The Capital of India</p>
                </div>
              </li>
              <li class="card">
                 <div class="card-image-container">
                    <img src='images/slider/mumbai.jpg' alt=''>
                 </div>
                 <div class="card-content">
                    <h3 class="city-name"><a href="">Mumbai</a></h3>
                    <p class="city-description">The financial Capital Of India</p>
                 </div>
              </li>
              <li class="card">
                <div class="card-image-container">
                    <img src='images/slider/banglore.jpg' alt=''>
                </div>
                 <div class="card-content">
                    <h3 class="city-name"><a href="">Banglore</a></h3>
                    <p class="city-description">Silicon Valley Of India</p>
                </div>
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
    <!-- Lightbox2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/extrenaljq.js"></script>
</body>

</html>

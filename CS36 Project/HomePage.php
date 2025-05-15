<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);       
?>

<!DOCTYPE html>
<html>
    <head>
        <title>RKG Hotel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="Hotel.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
   
    <body>
        <header>
            <a href="HomePage.Php"><img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo" ></a>

            <div class="navBar">
                <ul>

                    <li><a href="Rooms.php">Rooms & Accommodations</a></li>
                    <li><a href="Booking.php">Book Now</a></li>
                </ul> 
            </div>

            <!-- Show this div if a user is logged in, hide if not-->
            <div class="account dropdown" style="display: <?php echo (isset($_SESSION['fName'])) ? "block" : "none"; ?>">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="resources/person.png" alt="Account Photo" class="accountPhoto"><?php echo "Hi, ".$_SESSION['fName']; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                    <li><a class="dropdown-item" href="PersonalAccount.php">Profile</a></li>                   
                    <li><a class="dropdown-item" href="LogOut.php">Sign Out</a></li>
                </ul>
            </div>
            <!-- Show this div if a user is not logged in, hide if logged in-->
            <div class="account dropdown" style="display: <?php echo (!isset($_SESSION['fName'])) ? "block" : "none"; ?>">
                <a href="LogIn.php"><button class="btn btn-secondary" type="button">
                    Log In
                </button></a>              
            </div>
        </header>

        <div class="container">
            <div class="wrapper">
                <div class = "wrapper-holder">
                    <div class="slide" id = "slider-img-1"></div>
                    <div class="slide" id = "slider-img-2"></div>
                    <div class="slide" id = "slider-img-3"></div>
                    <div class="slide" id = "slider-img-4"></div>
                </div>
            </div>

            <div class="text-holder">
                <h1>Luxury At Its Finest</h1>
                <p>Experience the wonders of our rooms and feel your troubles melt away</p>
                <a href="Rooms.php" class="slide-btn">Rooms & Accommodations</a>
            </div>

            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
            <a class="next" onclick="changeSlide(1)">&#10095;</a>

            <div class="slide-indicators">
                <span class="dot" data-slide="0"></span>
                <span class="dot" data-slide="1"></span>
                <span class="dot" data-slide="2"></span>
                <span class="dot" data-slide="3"></span>
            </div>
        </div>  
    
        <script src="js\slideshow.js"></script>

        <div class = "resaurant-section">
            <div class = "card-group">
                <div class = "card-header">
                    <h1>Restaurant & Bar</h1>
                </div>
                <div class = "card-body">

                    <div class = "card-text">
                        <h4>Gregg's</h4>
                        <p>Our restaurant offers a wide variety of local and international cuisine, prepared by our talented chefs. Enjoy a delightful dining experience in a cozy and elegant atmosphere.</p>

                        <button class="card-button">Explore Menu</button>

                    </div>

                    <div class = "card-img-wrapper">
                        <img src="resources/restaurant.jpg" alt="Restaurant Image" class="card-img-top">
                    </div>

                </div>

        </div>

        <div class = "events-section">
            <div class = "events-card-group">
                <div class = "events-card-header">
                    <h1>Meetings & Events</h1>
                    <p>Host your next event with us and make it a memorable experience.</p>
                </div>
                <div class = "events-card-body">

                    <div class = "events-card-img-wrapper">
                        <div class="event-img1-container">
                            <img src="resources/diningtable2.jpg" alt="Dining Table Image" class="card-img">
                        </div>

                        <div class="event-img2-container">
                            <img src="resources/wedding1.jpg" alt="Wedding Image" class="card-img">
                        </div>
  
                    </div>

                    <div class = "events-card-text">
                        <h4>Meetings and Events</h4>
                        <p>Our hotel offers state-of-the-art facilities for meetings and events. Whether it's a corporate meeting, a wedding, or a special celebration, we have the perfect space for you.</p>
                    </div>
                </div>

                <div class = "events-card-body">
                    <div class = "events-card-text">
                        <h4>Weddings</h4>
                        <p>Make your dream wedding a reality at our hotel. With stunning venues, exceptional service, and exquisite catering options, we will help you create the perfect day.</p>
                    </div>

                    <div class = "events-card-img-wrapper">
                        <div class="event-img1-container">
                            <img src="resources/wedding2.jpg" alt="Wedding Image" class="card-img">
                        </div>

                        <div class="event-img2-container">
                            <img src="resources/beach.jpg" alt="Beach Image" class="card-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class = "gym-section">
            <div class = "card-group">
                <div class = "card-header">
                    <h1>Workout Area</h1>
                </div>
                <div class = "card-body">

                    <div class = "card-text">
                        <h4>Gymdol</h4>
                        <p>Our gym is equipped with the latest fitness equipment and offers a variety of classes to help you stay fit and healthy during your stay. Whether you're a beginner or an experienced athlete, we have something for everyone.</p>

                    </div>

                    <div class = "card-img-wrapper">
                        <img src="resources/gym.jpg" alt="Gym Image" class="card-img-top">
                    </div>

                </div>

        </div>

        <footer>
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>
    </body>
</html>
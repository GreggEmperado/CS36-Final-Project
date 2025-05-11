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
            <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo" href="HomePage.Php">

            <div class="navBar">
                <ul>

                    <li><a href="Rooms.php">Rooms & Accommodations</a></li>
                    <li><a href="Booking.php">Book Now</a></li>
                </ul> 
            </div>

            <div class="account dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="resources/person.png" alt="Account Photo" class="accountPhoto"><?php echo (isset($_SESSION['fName'])) ? "Hi, " . $_SESSION['fName'] : "Guest"; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                    <li><a class="dropdown-item" href="PersonalAccount.php">Profile</a></li>                   
                    <li><a class="dropdown-item" href="LogOut.php">Sign Out</a></li>
                </ul>
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
                <button>Rooms & Accommodations</button>
            </div>

            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
            <a class="next" onclick="changeSlide(1)">&#10095;</a>
        </div>  
        
        <script src="js\slideshow.js"></script>

        <footer>
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>
    </body>
</html>
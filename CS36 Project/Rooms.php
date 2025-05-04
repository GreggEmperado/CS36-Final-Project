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
            <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo">

            <div class="navBar">
                <ul>

                    <li><a href="news.asp">Rooms & Accommodations</a></li>
                    <li><a href="contact.asp">Book Now</a></li>
                </ul> 
            </div>

            <div class="account dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="resources/person.png" alt="Account Photo" class="accountPhoto"> Hi Gregg
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="signout.php">Sign Out</a></li>
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
                <h1>RKG Hotel</h1>
                <p>Contact us at rkg@gmail.com<br>
                    Experience the wonders of our rooms</p>
            </div>

            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
            <a class="next" onclick="changeSlide(1)">&#10095;</a>
        </div>  


        <div class = "rooms-container">
            <div class = "room" id = "single-bedroom"> 
                <img src="resources/singleBedRoom.jpg" alt="Single Bedroom" class="room-image">
                <h1>Single Bedroom</h1>
                <p>Perfect for solo travelers or couples looking for a cozy retreat.</p>
                <button class ="Book">Book Now</button>
            </div>

            <div class = "room" id = "double-bedroom">
                <img src="resources/doubleBedRoom.jpg" alt="Double Bedroom" class="room-image">
                <h1>Double Bedroom</h1>
                <p>Spacious and comfortable, ideal for families or groups.</p>
                <button class ="Book">Book Now</button>
            </div>

            <div class = "room" id = "suite">
                <img src="resources/image2.png" alt="Suite" class="room-image">
                <h1>Suite</h1>
                <p>Luxury and elegance combined for an unforgettable experience.</p>
                <button class ="Book">Book Now</button>
            </div>

            <div class = "room" id = "king-bedroom">
                <img src="resources/king-room.jpg" alt="King Bedroom" class="room-image">
                <h1>King Bedroom</h1>
                <p>Indulge in the ultimate comfort and style.</p>
                <button class ="Book">Book Now</button>
            </div>

            <div class = "room" id = "studio-bedroom">
                <img src="resources/studio-room.jpg" alt="Studio Bedroom" class="room-image">
                <h1>Studio Bedroom</h1>
                <p>Perfect for extended stays with all the amenities you need.</p>
                <button class ="Book">Book Now</button>
            </div>

            <div class = "room" id = "penthouse">
                <img src="resources/penthouse.jpg" alt="Penthouse" class="room-image">
                <h1>Penthouse</h1>
                <p>Experience the height of luxury with breathtaking views.</p>
                <button class ="Book">Book Now</button>
            </div>

        </div>
        
        <script src="js\slideshow.js"></script>

        <footer>
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>
    </body>
</html>
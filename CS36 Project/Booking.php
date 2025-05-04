<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Booking&Account.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@4.0.0-alpha.3/dist/fullcalendar.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="calendar.js"></script>
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
        <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="resources\defaultprofile.png" class="profileimg rounded-circle pt-3 m-5 img-fluid">
            </div>
            <div class="col-md-3 mt-5">
                <h4>First Name</h4>
                <p>Gregg</p>
                <h4>Email</h4>
                <p>greggmemperado@su.edu.ph</p>
                <br>
            </div>
            <div class="col-md-3 mt-5">
                <h4>Last Name</h4>
                <p>Emperado</p>
                
                <h4>Contact No.</h4>
                <p>55554327896</p>
            </div>
        </div>


        <div>
            <h1>Booked Rooms</h1>
            <hr>
            <br>
            <p>Single Room</p>
            <br>
            <hr>
            
        </div>

        <div class="col-md-6">
            <p>Select Check-In and Check-Out dates by clicking the calendar</p>
            <div class="mb-3">
                <strong>Check-In:</strong> <span id="checkin-date">None</span> |
                <strong>Check-Out:</strong> <span id="checkout-date">None</span>
            </div>

            <div id="calendar"></div>
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
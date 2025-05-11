<?php

$roomslist = null;
if (isset($_POST["searchrooms"])) {
    $roomslist = 1;
}

?>


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
                <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo" href="HomePage.Php">
                <div class="navBar">
                    <ul>
                        <li><a href="Rooms.php">Rooms & Accommodations</a></li>
                        <li><a href="Booking.php">Book Now</a></li>
                    </ul> 
                </div>
                <div class="account dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="resources/person.png" alt="Account Photo" class="accountPhoto"> Hi Gregg
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item" href="PersonalAccount.php">Profile</a></li>
                        <li><a class="dropdown-item" href="LogIn.php">Sign Out</a></li>
                    </ul>
                </div>
        </header>
    
        <div class="container">
        <div class="container mt-5">
        <!-- <h1 class="pt-2">Personal Information</h1>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <img src="resources\defaultprofile.png" class="img-fluid profileimg rounded-circle pt-3 m-5">
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
        </div> -->

        <div class="row pt-5">
            <div class="col-md-6">
                <p>Select Check-In and Check-Out dates by clicking the calendar</p>
                <div class="mb-3">
                    <strong>Check-In:</strong> <span id="checkin-date">None</span> |
                    <strong>Check-Out:</strong> <span id="checkout-date">None</span>
                </div>

                <div id="calendar"></div>

                <form method="post">
                <button name="searchrooms" class="btn-outline-info mt-5">Look for Available Rooms</button>
                </form>
            </div>

            <div class="col-md-6 border-3">
                <?php if ($roomslist): ?>
                <h2>Rooms Available</h2>
                <hr>
                <br>
                <div class = "row">
                    <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)"
                        data-room="Single Bedroom. Good for 2 persons" 
                        data-price=2250 
                        data-img="resources/singleBedRoom.jpg">
                        <img src="resources\singleBedRoom.jpg" id="roomsimg">
                        <p>Single</p>
                    </div>
                    <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)"
                        data-room="Double Bedroom. Good for 3 persons" 
                        data-price=5000 
                        data-img="resources\doubleBedRoom.jpg">
                        <img src="resources\doubleBedRoom.jpg" id="roomsimg">
                        <p>Double</p>
                    </div>
                    <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)"
                        data-room="Suite Bedroom. Good for 4 persons" 
                        data-price=7000 
                        data-img="resources\image2.png">
                        <img src="resources\image2.png" id="roomsimg">
                        <p>Suite</p>
                    </div>
                </div>

                <div class = "row">
                    <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)"
                        data-room="King Bedroom. Good for 6 persons" 
                        data-price=9000 
                        data-img="resources\king-room.jpg">
                        <img src="resources\king-room.jpg" id="roomsimg">
                        <p>King</p>
                    </div>
                    <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)"
                        data-room="Studio Bedroom. Good for 8 persons" 
                        data-price=10000 
                        data-img="resources\studio-room.jpg">
                        <img src="resources\studio-room.jpg" id="roomsimg">
                        <p>Studio</p>
                    </div>
                    <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)"
                        data-room="Penthouse Bedroom. Good for 10 persons" 
                        data-price= 12000 
                        data-img="resources\penthouse.jpg">
                        <img src="resources\penthouse.jpg" id="roomsimg">
                        <p>Penthouse</p>
                    </div>
                </div>
                <script>
                        let roomCount = 0;
                        let totalprice = 0;

                        function RoomSelect(element) {//gets attributes from each room type
                        const imgSrc = element.getAttribute("data-img");
                        const roomDesc = element.getAttribute("data-room");
                        let price = Number(element.getAttribute("data-price"));

                        roomCount++; //Increases room count on click
                        totalprice += price;//adds price

                        const container = document.getElementById("selected-rooms");

                        const roomDiv = document.createElement("div");//creates a new div for the selected rooms section
                        roomDiv.className = "row mb-3 align-items-center";
                        roomDiv.id = `room-${roomCount}`;//each selected room has an id room-{1,2,3...}
                        roomDiv.setAttribute("data-price", price); //sets price attribute for subtraction
                        roomDiv.innerHTML = `
                            <div class="col-md-3">
                            <img src="${imgSrc}" class="img-fluid">
                            </div>
                            <div class="col-md-3">
                            <p>${roomDesc}</p>
                            </div>
                            <div class="col-md-3">
                            <p>PHP ${price}/per night</p>
                            </div>
                            <div class="col-md-3">
                            <button class="btn btn-danger" onclick="removeRoom('room-${roomCount}')">Remove</button>
                            </div>
                        `;

                        container.appendChild(roomDiv);
                        document.getElementById("cart").textContent = `Your Cart: ${roomCount} Item(s)`;//Updates cart items & price
                        document.getElementById("total-price").textContent = `Total: PHP${totalprice}`;
                        }

                        function removeRoom(roomId) {// removes the selected room when Remove button is clicked
                        const roomDiv = document.getElementById(roomId);
                        let price = Number(roomDiv.getAttribute("data-price"));// Decrease room count and price depending on removed room type
                        roomCount--;
                        if (roomDiv) {
                        totalprice -= price;
                        document.getElementById("cart").textContent = `Your Cart: ${roomCount} Item(s)`;
                        document.getElementById("total-price").textContent = `Total: PHP${totalprice}`;
                            roomDiv.remove();
                        }
                        }
                </script>
                <br>
                <div class="container room-select">
                    <h3>Rooms Selected</h3>
                    <div id="selected-rooms">
                        <!-- Room selections will be added here dynamically -->
                    </div>
                </div>
                <?php endif; ?>

                <div class="col-md-5">
                    <h4 id="cart">Your Cart: 0 Item(s)</h4>
                    <p id="total-price">Total: PHP0</p><button>Confirm Booking</button>
                </div>

            </div>

        </div>
        <br>
    </div>
    </div>

        <footer>
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>

    </body>
</html>
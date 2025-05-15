<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);   

    $checkInDate = $checkOutDate = "";

    if (!isset($_SESSION['memberID'])){
        header("Location: LogIn.php");
        exit();
    }
        
    $roomslist = null;
    if (isset($_POST['searchRooms'])) {
        $roomslist = 1;
    }

    //Search query for available room types
    if ($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['searchRooms']))){        
        $_SESSION['checkin'] = $_POST['checkin'];
        $_SESSION['checkout'] = $_POST['checkout'];  
        
        $checkInDate = $_SESSION['checkin'];
        $checkOutDate = $_SESSION['checkout'];

        $sql = "SELECT DISTINCT r.roomType 
                FROM rooms r 
                WHERE EXISTS (
                    SELECT 1 
                    FROM rooms rm 
                    WHERE rm.roomType = r.roomType 
                    AND rm.isAvailable = TRUE 
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM roomAvailability ra 
                        WHERE ra.roomNumber = rm.roomNumber 
                            AND ra.date BETWEEN ? AND ?
                    )
                );";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $checkInDate, $checkOutDate);
        $stmt->execute();
        $result = $stmt->get_result();

        //Set to false indicating unavailable room type
        $roomSingle = false;
        $roomDouble = false;
        $roomSuite = false;
        $roomKing = false;
        $roomStudio = false;
        $roomPent = false;        

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['roomType'] == 'single')
                    $roomSingle = true;
                if ($row['roomType'] == 'double')
                    $roomDouble = true;
                if ($row['roomType'] == 'suite')
                    $roomSuite = true;
                if ($row['roomType'] == 'king')
                    $roomKing = true;
                if ($row['roomType'] == 'studio')
                    $roomStudio = true;
                if ($row['roomType'] == 'pent')
                    $roomPent = true;               
            }
        } else {
            echo "No available room types found."; //TEMPORARY TEMPO TEMPO TEMPO
        }        

        $stmt->close();
        $conn->close();
    }

    //Booking the room
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['confirm'])){
        $memberID = $_SESSION['memberID'];
        $roomType = $_POST['roomType'];
        $roomNum = "";
        $checkInDate = $_SESSION['checkin'];
        $checkOutDate = $_SESSION['checkout'];
        $status = "pending";        

        //Search for available rooms based on the room type
        $sql = "SELECT r.roomNumber
                FROM rooms r
                WHERE r.roomType = ?  
                AND NOT EXISTS (
                    SELECT 1
                    FROM roomAvailability ra
                    WHERE ra.roomNumber = r.roomNumber
                    AND ra.date BETWEEN ? AND ?  
                )
                AND r.isAvailable = TRUE  
                LIMIT 1
                ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $roomType, $checkInDate, $checkOutDate);
        $stmt->execute();
        $result = $stmt->get_result();

        //This just checks the available rooms
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $roomNum = $row['roomNumber'];
        }

        $insertSql = "INSERT INTO bookings (memberID, roomNumber, checkInDate, checkOutDate, status)
                      VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("sssss", $memberID, $roomNum, $checkInDate, $checkOutDate, $status);
        $insertStmt->execute();

        echo "Booking confirmed for room ID: $roomNum";

        //Update room availability for each day in the booking period
        $currentDate = $checkInDate;
        while ($currentDate <= $checkOutDate) {
            $availabilitySql = "INSERT INTO roomAvailability (roomNumber, date)
                                VALUES (?, ?)
                                ON DUPLICATE KEY UPDATE roomNumber = roomNumber
            ";
            $availabilityStmt = $conn->prepare($availabilitySql);
            $availabilityStmt->bind_param("ss", $roomNum, $currentDate);
            $availabilityStmt->execute();

            //Increment the day
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }        
    }
?>

<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Booking&Account.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@4.0.0-alpha.3/dist/fullcalendar.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/calendar.js"></script>
    </head>
    <body>
        <header>
                <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo" href="HomePage.php">
                <div class="navBar">
                    <ul>
                        <li><a href="Rooms.php">Rooms & Accommodations</a></li>
                        <li><a href="Booking.php">Book Now</a></li>
                    </ul> 
                </div>
                <div class="account dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="resources/person.png" alt="Account Photo" class="accountPhoto"><?php echo "Hi, ".$_SESSION['fName']?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                    <li><a class="dropdown-item" href="PersonalAccount.php">Profile</a></li>
                    <li><a class="dropdown-item" href="LogIn.php">Sign Out</a></li>
                </ul>
            </div>
        </header>
    
        <div class="container">
        <div class="container mt-5">

        <div class="row pt-5">
            <div class="col-md-6">
                <p>Select Check-In and Check-Out dates by clicking the calendar</p>
                <div class="mb-3">
                    <strong>Check-In:</strong> <span id="checkin-date">
                        <?php echo $checkInDate; ?></span> |
                    <strong>Check-Out:</strong> <span id="checkout-date">
                        <?php echo $checkOutDate; ?></span>
                    
                </div>

                <div id="calendar"></div>                

                <form method="POST">
                    <input type="hidden" id="hidden-checkin" name="checkin" value="<?php echo $checkInDate; ?>">
                    <input type="hidden" id="hidden-checkout" name="checkout" value="<?php echo $checkOutDate; ?>">
                    <button type="submit" name="searchRooms" id="searchRooms" class="btn-outline-info mt-5" value="Look">Look for Available Rooms</button>                    
                </form>
            </div>
            
            <div class="col-md-6 border-3">
                <form method="POST" onsubmit="return handleConfirmBooking(event)">
                    <?php if ($roomslist): ?>
                    <h2>Rooms Available</h2>
                    <hr>
                    <br>
                    <div class = "row">
                        <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)" name="singleRoom"
                            style="display: <?php if ($roomSingle) { echo 'block'; } else { echo 'none'; } ?>"
                            data-roomType="single" 
                            data-room="Single Bedroom. Good for 2 persons"
                            data-price=2250 
                            data-img="resources/singleBedRoom.jpg">
                                <img src="resources\singleBedRoom.jpg" id="roomsimg">
                                <p>Single</p>
                        </div>
                        <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)" 
                            style="display: <?php if ($roomDouble) { echo 'block'; } else { echo 'none'; } ?>"
                            data-roomType="double" 
                            data-room="Double Bedroom. Good for 3 persons" 
                            data-price=5000 
                            data-img="resources\doubleBedRoom.jpg">
                                <img src="resources\doubleBedRoom.jpg" id="roomsimg">
                                <p>Double</p>                                
                        </div>
                        <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)" 
                            style="display: <?php if ($roomSuite) { echo 'block'; } else { echo 'none'; } ?>"
                            data-roomType="suite" 
                            data-room="Suite Bedroom. Good for 4 persons" 
                            data-price=7000 
                            data-img="resources\image2.png">
                                <img src="resources\image2.png" id="roomsimg">
                                <p>Suite</p>
                        </div>
                    </div>

                    <div class = "row">
                        <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)" 
                            style="display: <?php if ($roomKing) { echo 'block'; } else { echo 'none'; } ?>"
                            data-roomType="king" 
                            data-room="King Bedroom. Good for 6 persons" 
                            data-price=9000 
                            data-img="resources\king-room.jpg">
                                <img src="resources\king-room.jpg" id="roomsimg">
                                <p>King</p>
                        </div>
                        <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)" 
                            style="display: <?php if ($roomStudio) { echo 'block'; } else { echo 'none'; } ?>"
                            data-roomType="studio" 
                            data-room="Studio Bedroom. Good for 8 persons" 
                            data-price=10000 
                            data-img="resources\studio-room.jpg">
                                <img src="resources\studio-room.jpg" id="roomsimg">
                                <p>Studio</p>
                        </div>
                        <div class="col-md-4 text-center room-grid" onclick="RoomSelect(this)" 
                            style="display: <?php if ($roomPent) { echo 'block'; } else { echo 'none'; } ?>"
                            data-roomType="pent" 
                            data-room="Penthouse Bedroom. Good for 10 persons" 
                            data-price= 12000 
                            data-img="resources\penthouse.jpg">
                                <img src="resources\penthouse.jpg" id="roomsimg">
                                <p>Penthouse</p>
                        </div>
                    </div>

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
                        <p id="total-price">Total: PHP0</p>
                        <button type="submit" name="confirm" id="confirm" data-bs-toggle="modal" data-bs-target="#bookingSuccessful" disabled>Confirm Booking</button>
                        <div class="modal fade" id="bookingSuccessful" tabindex="-1" aria-labelledby="dailyBookingsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="dailyBookingsModalLabel">Successful Booking</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Your booking has been successfully confirmed!</p>
                                        <p>Check your email for the booking details.</p>
                                        <p>Thank you for choosing RKG Hotel!</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Book again</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" action="HomePage.php">Home</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>



                </form>

                <script>
                        let roomCount = 0;
                        let totalprice = 0;

                        function RoomSelect(element) {//gets attributes from each room type

                            //Disable all rooms after a selection
                            const allRooms = document.querySelectorAll('[data-room]');
                            allRooms.forEach(room => {
                                room.onclick = null;  //Disable clicking
                            });

                            element.setAttribute("data-selected", "true");
                            const imgSrc = element.getAttribute("data-img");
                            const roomDesc = element.getAttribute("data-room");   
                            const roomType = element.getAttribute("data-roomType");                       
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
                                <input type="hidden" name="roomType" value="${roomType}">
                            `;

                            container.appendChild(roomDiv);
                            document.getElementById("cart").textContent = `Your Cart: ${roomCount} Item(s)`;//Updates cart items & price
                            document.getElementById("total-price").textContent = `Total: PHP${totalprice}`;      
                            
                            //Enable the "Confirm" button if a room is selected
                            if (roomCount > 0) {
                                document.getElementById("confirm").disabled = false;  // Enable the button
                            }
                            
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
                                
                                //Re-enable all rooms by adding the onclick back
                                const allRooms = document.querySelectorAll('[data-room]');
                                allRooms.forEach(room => {
                                    room.onclick = () => RoomSelect(room); //Add the click event back
                                });

                                // If no rooms are selected, disable the "Confirm" button again
                                if (roomCount === 0) {
                                    document.getElementById("confirm").disabled = true;  // Disable the button
                                }
                            }
                        }

                        function handleConfirmBooking(event) {
                            // Prevent the default form submission
                            event.preventDefault();

                            // Show the modal
                            const bookingModal = new bootstrap.Modal(document.getElementById('bookingSuccessful'));
                            bookingModal.show();

                            // Optionally, you can submit the form after the modal is displayed
                            // Uncomment the following line if you want to submit the form after showing the modal
                            // document.querySelector('form').submit();

                            // Return false to prevent the default form submission
                            return false;
                        }

                        function redirectToHome() {
                            window.location.href = "HomePage.php"; // Redirects to HomePage.php
                        }
                </script>
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
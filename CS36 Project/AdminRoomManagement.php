<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);

    $roomNumber = $roomType = $roomCapacity = $availability = $error = "";
    
    //Change availability
    if (isset($_POST['change'])){
        $roomNumber = $_POST['roomNumber'];
        $availability = $_POST['availability'];
        $sql = $conn->prepare("UPDATE rooms SET isAvailable = ? WHERE roomNumber = ?");
        $sql->bind_param("ss", $availability, $roomNumber);
        $sql->execute();       
    }
?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Booking&Account.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <header class="header-login">
            <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo-login" href="HomePage.Php">
            <div class="account dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="resources/person.png" alt="Account Photo" class="accountPhoto"><?php echo (isset($_SESSION['fName'])) ? "Hi, " . $_SESSION['fName'] : "Guest"; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">                        
                        <li><a class="dropdown-item" href="LogIn.php">Sign Out</a></li>
                    </ul>
                </div>
        </header>

        <div class="row flex-grow-1">
                <div class="col-md-2 container">
                    <h2 class="text-center mb-4 pt-4">HOTEL DATABASE MANAGER</h2>
                        <a class="manager-buttons" href="AdminRoomManagement.php">Room Manager</a><br>
                        <a class="manager-buttons" href="AdminGuestList.php">Member Manager</a><br>
                        <a class="manager-buttons" href="AdminBookingList.php">Booking Manager</a><br>

                </div>
                
                <div class="col-md-10 mt-5">
                <div class="container pt-3 manager custom-table">
                <div class="row">
                    <div>
                        <!-- Create Room Modal-->
                        <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createRoomModalLabel">Create New Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="AdminRoomManagement.php">
                                            <div class="mb-3">
                                                <label for="room_number" class="form-label">Room Number</label>
                                                <input type="text" name="room_number" id="room_number" class="form-control" placeholder="Room Number" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="room_type" class="form-label">Room Type</label>
                                                <input type="text" name="room_type" id="room_type" class="form-control" placeholder="Room Type" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="capacity" class="form-label">Capacity</label>
                                                <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Capacity" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="availability" class="form-label">Availability</label>
                                                <select name="availability" id="availability" class="form-control" required>                                                    
                                                    <option value="Available">Available</option>
                                                    <option value="Unavailable">Unavailable</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="create" class="btn btn-success" 
                                                    style="background-color:#1D1128; border: 1px solid #1D1128">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Room Availability Modal-->
                        <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editRoomModalLabel">Create New Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="AdminRoomManagement.php">  
                                            <input type="hidden" name="roomNumber" id="roomNumberInput" class="form-control"> <!--Hidden field to store roomNumber-->
                                            <div class="mb-3">
                                                <label for="availability" class="form-label">Availability</label>                                                
                                                <select name="availability" id="availability" class="form-control" required>
                                                    <option selected="true" disabled="true">Choose...</option>
                                                    <option value="available">Available</option>
                                                    <option value="unavailable">Unavailable</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="changeRoomAvail" class="btn btn-success" 
                                                    style="background-color:#1D1128; border: 1px solid #1D1128">Change</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking per Room Modal-->
                        <div class="modal fade" id="bookingPerRoomModal" tabindex="-1" aria-labelledby="bookingPerRoomModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bookingPerRoomModalLabel">Booking per Room Report</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Room Number</th>
                                                    <th>Room Type</th>
                                                    <th>Total Bookings</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php                                           
                                                    $sql = "SELECT a.roomNumber, a.roomType, COUNT(b.bookingID) AS totalBookings
                                                            FROM rooms a
                                                            LEFT JOIN bookings b 
                                                            ON a.roomNumber = b.roomNumber
                                                            GROUP BY a.roomNumber, a.roomType";
                                                    $result = $conn->query($sql);

                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>
                                                                    <td>{$row['roomNumber']}</td>
                                                                    <td>{$row['roomType']}</td>
                                                                    <td>{$row['totalBookings']}</td>
                                                                </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='3'>No bookings found</td></tr>";
                                                    }                                                
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>            
                    </div>
                                
                    <div class="col-md-12 justify-content-center ps-5">
                        <h2 class="row">Room Management</h2>
                        <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createRoomModal" 
                            style="background-color:#1D1128; border: 1px solid #1D1128">Create Room</button>
                        <button class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#bookingPerRoomModal" 
                            style="background-color:#1D1128; border: 1px solid #1D1128; color: white;">Generate Booking Report</button>
                        <br>
                        <br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Room Number</th>
                                    <th>Room Type</th>
                                    <th>Capacity</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>   
                            <tbody>
                                <?php                                           
                                    $sql = "SELECT * FROM rooms";                                        
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {                                        
                                ?>       
                                <tr>
                                    <td><?php echo $row['roomNumber']; ?></td>
                                    <td><?php echo $row['roomType']; ?></td>
                                    <td><?php echo $row['roomCapacity']; ?></td>
                                    <td><?php echo $row['isAvailable']; ?></td>
                                    <td>
                                        <form method="POST" action="">               
                                            <button type="button" name="edit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRoomModal" 
                                                onclick="setRoomNumber('<?php echo $row['roomNumber']; ?>')">Edit</button>
                                        </form>
                                    </td>                                
                                </tr>
                                <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No rooms found</td></tr>";
                                    }
                                ?>
                            </tbody>                     
                        </table>
                    </div>
                </div>
            </div>
                </div>
        </div>        
        
        <script>
            document.getElementById('createRoomButton').addEventListener('click', function () {
                const form = document.getElementById('createRoomForm');
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            });
            //Function to set the roomNumber
            function setRoomNumber(roomNumber) {
                document.getElementById('roomNumberInput').value = roomNumber;
            }
        </script>

        <footer style="margin-top:auto;">
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>
    
    </body>
</html>
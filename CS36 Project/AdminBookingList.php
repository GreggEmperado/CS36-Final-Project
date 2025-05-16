<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);
      
    $bookingID = $status = $roomType = "";    
    if (isset($_POST['changeStatus'])){       
        $bookingID = $_POST['bookingID2']; 
        $status = $_POST['status'];      

        //Update booking status
        $sqlUpdate = $conn->prepare("UPDATE bookings SET status = ? WHERE bookingID = ?");
        $sqlUpdate->bind_param("si", $status, $bookingID);
        $sqlUpdate->execute();
    }

    if (isset($_POST['changeRoomType'])){
        $bookingID = $_POST['bookingID'];
        $roomType = $_POST['roomType'];

        //Store old number and dates for deletion
        $sql = $conn->prepare("SELECT roomNumber, checkInDate, checkOutDate FROM bookings WHERE bookingID = ?");
        $sql->bind_param("i", $bookingID);
        $sql->execute();
        $sql->bind_result($oldRoomNumber, $checkIn, $checkOut);

        //Fetch the old room number
        $sql->fetch();        
        $sql->close();

        
        //Store new number
        $findRoom = $conn->prepare("SELECT roomNumber FROM rooms WHERE roomType = ? AND isAvailable = 'available' LIMIT 1");
        $findRoom->bind_param("s", $roomType);
        $findRoom->execute();
        $findRoom->bind_result($newRoomNumber);
        $findRoom->fetch();        
        $findRoom->close();       

        if ($newRoomNumber) {
            //Update booking with new roomNumber
            $updateBooking = $conn->prepare("UPDATE bookings SET roomNumber = ? WHERE bookingID = ?");
            $updateBooking->bind_param("si", $newRoomNumber, $bookingID);
            $updateBooking->execute();
            $updateBooking->close();

            //Delete old roomAvailability entries
            $deleteOld = $conn->prepare("DELETE FROM roomAvailability WHERE roomNumber = ? AND date BETWEEN ? AND ?");
            $deleteOld->bind_param("sss", $oldRoomNumber, $checkIn, $checkOut);
            $deleteOld->execute();
            $deleteOld->close();

            //Insert new roomAvailability entries
            $insertNew = $conn->prepare("INSERT IGNORE INTO roomAvailability (roomNumber, date) VALUES (?, ?)");
            $current = strtotime($checkIn);
            $end = strtotime($checkOut);
            while ($current <= $end) {
                $date = date('Y-m-d', $current);
                $insertNew->bind_param("ss", $newRoomNumber, $date);
                $insertNew->execute();
                // Add one day (in seconds)
                $current = strtotime("+1 day", $current);
            }
            $insertNew->close();                  
        }               
    }
    
    if (isset($_POST['delete'])){
        $bookingID = $_POST['bookingID'];       
        // Delete the booking
        $sqlDelete = $conn->prepare("DELETE FROM bookings WHERE bookingID = ?");
        $sqlDelete->bind_param("i", $bookingID);
        $sqlDelete->execute();       
    }  
?>
<!DOCTYPE html>
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
                    <li><a class="dropdown-item" href="LogOut.php">Sign Out</a></li>
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

                        <!-- Daily Bookings Modal -->
                        <div class="modal fade" id="dailyBookingsModal" tabindex="-1" aria-labelledby="dailyBookingsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="dailyBookingsModalLabel">Daily Bookings Report</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Booking ID</th>
                                                    <th>Member ID</th>
                                                    <th>Room ID</th>
                                                    <th>Check-In</th>
                                                    <th>Check-Out</th>
                                                    <th>Booking Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php                                            
                                                    // Fetch today's bookings
                                                    date_default_timezone_set('Asia/Manila'); //Set timezone to Manila
                                                    $today = date('Y-m-d');                                                    
                                                    $sql = "SELECT bookingID, memberID, roomNumber, checkInDate, checkOutDate, bookingDate
                                                            FROM bookings
                                                            WHERE bookingDate = '$today'";
                                                    $result = $conn->query($sql);

                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>
                                                                    <td>{$row['bookingID']}</td>
                                                                    <td>{$row['memberID']}</td>
                                                                    <td>{$row['roomNumber']}</td>
                                                                    <td>{$row['checkInDate']}</td>
                                                                    <td>{$row['checkOutDate']}</td>
                                                                    <td>{$row['bookingDate']}</td>
                                                                </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6'>No bookings found for today</td></tr>";
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
                        
                        <!-- Edit Room Number Modal-->
                        <div class="modal fade" id="editBookingsModal" tabindex="-1" aria-labelledby="editBookingsModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editBookingsModalLabel">Change Room Number</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="">                                                                                                                                     
                                            <div class="mb-3">
                                                <label for="bookingNumber" class="form-label">Booking ID</label>
                                                <input type="text" name="bookingID" id="bookingID" class="form-control" placeholder="Booking ID" required>
                                            </div>                                            
                                            <div class="mb-3">
                                                <label for="roomType" class="form-label">Select Room Type</label>
                                                <select name="roomType" id="roomType" class="form-control" required>                                                    
                                                    <option value="single">Single</option>
                                                    <option value="double">Double</option>
                                                    <option value="suite">Suite</option>
                                                    <option value="king">King</option>
                                                    <option value="studio">Studio</option>
                                                    <option value="pent">Penthouse</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="changeRoomType" class="btn btn-success" style="background-color:#1D1128; border: 1px solid #1D1128">Change</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Status Modal-->
                        <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editStatusModalLabel">Set Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <input type="hidden" name="bookingID2" id="bookingIDInput2" class="form-control"> <!--Hidden field to store bookingID-->                                             
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Change Status</label>                                                
                                                <select name="status" id="status" class="form-control" required>
                                                    <option selected="true" disabled="true">Choose...</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="confirmed">Confirmed</option>
                                                    <option value="checkedIn">Checked-In</option>
                                                    <option value="checkedOut">Checked-Out</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="changeStatus" class="btn btn-success" 
                                                    style="background-color:#1D1128; border: 1px solid #1D1128">Change</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>                        

                        <div class="col-md-12 justify-content-center ps-5">
                            <h2>List of Bookings</h2>
                            <button class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#dailyBookingsModal" style="background-color:#1d1128; border: 1px solid #1d1128; color: white;">Generate Daily Booking Report</button>
                            <button class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#editBookingsModal" style="background-color:#1d1128; border: 1px solid #1d1128; color: white;">Edit Booking</button>
                            <br><br>
                        
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Member ID</th>
                                        <th>Room Number</th>
                                        <th>Room Type</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Booking Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php                           
                                        $sql = "SELECT a.bookingID, a.memberID, a.roomNumber, b.roomType, a.checkInDate, a.checkOutDate, a.bookingDate, a.status
                                                FROM bookings a
                                                LEFT JOIN rooms b
                                                ON a.roomNumber = b.roomNumber
                                                ORDER BY a.bookingID DESC";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['bookingID']; ?></td>
                                        <td><?php echo $row['memberID']; ?></td>
                                        <td><?php echo $row['roomNumber']; ?></td>
                                        <td><?php echo $row['roomType']; ?></td>
                                        <td><?php echo $row['checkInDate']; ?></td>
                                        <td><?php echo $row['checkOutDate']; ?></td>
                                        <td><?php echo $row['bookingDate']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="bookingID" value="<?php echo $row['bookingID']; ?>">                                                                                                                                  
                                                <button type="button" name="editStatus" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editStatusModal"
                                                    onclick="setBookingID2('<?php echo $row['bookingID']; ?>')">Status</button>                                                
                                                <button type="submit" name="delete" class="btn btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>                                       
                                    <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='8'>There are no bookings currently.</td></tr>";
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
            function setBookingID2(bookingID) {
                document.getElementById('bookingIDInput2').value = bookingID;                
            }
        </script>

        <footer style="margin-top:auto;" class="footer">
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>
    
    </body>
</html>
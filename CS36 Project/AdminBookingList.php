<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit'])){
        $bookingID = $_POST['bookingID'];
        $memberID = $_POST['memberID'];
        $roomID = $_POST['roomNumber'];
        $checkInDate = $_POST['checkInDate'];
        $checkOutDate = $_POST['checkOutDate'];
        $bookingDate = $_POST['bookingDate'];
        $status = $_POST['status'];
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
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">                        <li><a class="dropdown-item" href="LogIn.php">Sign Out</a></li>
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
                </div>

                <div class="col-md-12 justify-content-center ps-5">
                    <h2>List of Bookings</h2>
                    <button class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#dailyBookingsModal" style="background-color:#1d1128; border: 1px solid #1d1128; color: white;">Generate Daily Booking Report</button>
                    <br><br>
                
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Member ID</th>
                            <th>Room Number</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Booking Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php                            
                            $today = date('Y-m-d');
                            $sql = "SELECT bookingID, memberID, roomNumber, checkInDate, checkOutDate, bookingDate, status
                                    FROM bookings
                                    WHERE bookingDate = '$today'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $row['bookingID']; ?></td>
                            <td><?php echo $row['memberID']; ?></td>
                            <td><?php echo $row['roomNumber']; ?></td>
                            <td><?php echo $row['checkInDate']; ?></td>
                            <td><?php echo $row['checkOutDate']; ?></td>
                            <td><?php echo $row['bookingDate']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="bookingID" value="<?php echo $row['bookingID']; ?>">
                                    <input type="hidden" name="memberID" value="<?php echo $row['memberID']; ?>">
                                    <input type="hidden" name="roomNumber" value="<?php echo $row['roomNumber']; ?>">
                                    <input type="hidden" name="checkInDate" value="<?php echo $row['checkInDate']; ?>">
                                    <input type="hidden" name="checkOutDate" value="<?php echo $row['checkOutDate']; ?>">
                                    <input type="hidden" name="bookingDate" value="<?php echo $row['bookingDate']; ?>">
                                    <input type="hidden" name="status" value="<?php echo $row['status']; ?>">                                                
                                    <button type="submit" name="edit" class="btn btn-primary">Edit</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>                                       
                        <?php
                                 }
                            } else {
                                echo "<tr><td colspan='8'>No rooms found</td></tr>";
                            }
                        ?>
                    </tbody>
                    </table>
                </div>

            <!-- <form method="get" action="AdminDashboard.php" style="display:inline;">
                <button type="submit" class="btn btn-primary">Back to Dashboard</button>
            </form> -->
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
        </script>

        <footer style="margin-top:auto;">
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>
    
    </body>
</html>
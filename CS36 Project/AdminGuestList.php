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

        <div class="container mt-5 manager custom-table">
            <h2 class="text-center mb-4 pt-4">HOTEL DATABASE MANAGER</h2>
            <div class="row">
                <div class="col-md-2">
                    <a class="manager-buttons" href="AdminRoomManagement.php">Room Manager</a><br>
                    <a class="manager-buttons" href="AdminGuestList.php">Member Manager</a><br>
                    <a class="manager-buttons" href="AdminBookingList.php">Booking Manager</a><br>

                     <!-- Booking per Guest Modal -->
                    <div class="modal fade" id="bookingPerGuestModal" tabindex="-1" aria-labelledby="bookingPerGuestModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookingPerGuestModalLabel">Booking per Guest Report</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Member ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Total Bookings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Example PHP code to fetch booking data grouped by guest
                                            $conn = new mysqli("localhost", "root", "", "hotelDB");

                                            if ($conn->connect_error) {
                                                die("Connection failed: " . $conn->connect_error);
                                            }

                                            $sql = "SELECT g.member_id, g.first_name, g.last_name, COUNT(b.booking_id) AS total_bookings
                                                    FROM guests g
                                                    LEFT JOIN bookings b ON g.member_id = b.member_id
                                                    GROUP BY g.member_id, g.first_name, g.last_name";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                            <td>{$row['member_id']}</td>
                                                            <td>{$row['first_name']}</td>
                                                            <td>{$row['last_name']}</td>
                                                            <td>{$row['total_bookings']}</td>
                                                        </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No bookings found</td></tr>";
                                            }

                                            $conn->close();
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

                <div class="col-md-10 justify-content-center ps-5">
                <h4>Member Manager</h4>
                <button class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#bookingPerGuestModal" style="background-color:#1d1128; border: 1px solid #1d1128; color: white;">Generate Booking Report</button>
                <br>
                <br>
                
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Member ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Contact No.</th>
                            <th>password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // $conn = new mysqli("localhost", "root", "", "hotelDB");
                            // $sql = "SELECT * FROM rooms";
                            // $result = $conn->query($sql);
                            
                            // if ($result->num_rows > 0) {
                            //     while ($row = $result->fetch_assoc()) {
                        ?>
                                    <tr>
                                        <td><?php //echo $row['room_number']; ?></td>
                                        <td><?php //echo $row['room_type']; ?></td>
                                        <td><?php //echo $row['price']; ?></td>
                                        <td ><?php //echo $row['status']; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <form method="post" action="AdminRoomManagement.php">
                                                <input type="hidden" name="roomID" value="<?php //echo $row['roomID']; ?>">
                                                <button type="submit" name="edit" class="btn btn-primary">Edit</button>
                                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    

                                    
                        <?php
                            //     }
                            // } else {
                            //     echo "<tr><td colspan='4'>No rooms found</td></tr>";
                            // }
                        ?>
                    </tbody>
                    </table>
                </div>

            <!-- <form method="get" action="AdminDashboard.php" style="display:inline;">
                <button type="submit" class="btn btn-primary">Back to Dashboard</button>
            </form> -->
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
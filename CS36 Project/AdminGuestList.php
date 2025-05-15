<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);

    if (isset($_POST['delete'])){
        $memberID = $_POST['memberID'];       
        $sql = $conn->prepare("DELETE FROM members WHERE memberID = ?");
        $sql->bind_param("i", $memberID);
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
                <div class="container manager custom-table pt-3">
                <div class="row">
                    <div>
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
                                                //Example PHP code to fetch booking data grouped by guest                                                   

                                                    $sql = "SELECT a.memberID, a.firstName, a.lastName, COUNT(b.bookingID) AS total_bookings
                                                            FROM members a
                                                            LEFT JOIN bookings b ON a.memberID = b.memberID
                                                            GROUP BY a.memberID, a.firstName, a.lastName";
                                                    $result = $conn->query($sql);

                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>
                                                                    <td>{$row['memberID']}</td>
                                                                    <td>{$row['firstName']}</td>
                                                                    <td>{$row['lastName']}</td>
                                                                    <td>{$row['total_bookings']}</td>
                                                                </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='4'>No bookings found</td></tr>";
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

                    <div class="col-md-12 justify-content-center ps-5 ml-0">
                    <h2>Member Manager</h2>
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
                                    $sql = "SELECT * FROM members";
                                    $result = $conn->query($sql);                                    
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $row['memberID']; ?></td>
                                    <td><?php echo $row['firstName']; ?></td>
                                    <td><?php echo $row['lastName']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phoneNumber']; ?></td>
                                    <td><?php echo $row['password']; ?></td>                               
                                    <td>
                                        <form method="POST" action="">
                                            <input type="hidden" name="memberID" value="<?php echo $row['memberID']; ?>">                                            
                                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
            
                                <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No rooms found</td></tr>";
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
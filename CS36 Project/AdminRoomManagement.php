<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $maindb = "emperado dbe6";


// $conn = new mysqli($servername, $username, $password);


// if($conn->connect_error){
//    die("Connection failed: ". $conn->connect_error);
// }
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

        <div class="container mt-5 manager custom-table">
            <h2 class="text-center mb-4 pt-4">HOTEL DATABASE MANAGER</h2>
            <div class="row">
                <div class="col-md-2">
                    <a class="manager-buttons" href="AdminRoomManagement.php">Room Manager</a><br>
                    <a class="manager-buttons" href="AdminGuestList.php">Member Manager</a><br>
                    <a class="manager-buttons" href="AdminBookingList.php">Booking Manager</a><br>


                    <!-- Create Room Modal -->
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
                                            <button type="submit" name="create" class="btn btn-success" style="background-color:#1D1128; border: 1px solid #1D1128">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 justify-content-center ps-5">
                <h4 class="row">Room Management</h4>
                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createRoomModal" style="background-color:#1D1128; border: 1px solid #1D1128">Create Room</button>
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
                            // $conn = new mysqli("localhost", "root", "", "hotelDB");
                            // $sql = "SELECT * FROM rooms";
                            // $result = $conn->query($sql);
                            
                            // if ($result->num_rows > 0) {
                            //     while ($row = $result->fetch_assoc()) {
                        ?>
                                    <tr>
                                        <td scope="col"><?php //echo $row['room_number']; ?></td>
                                        <td scope="col"><?php //echo $row['room_type']; ?></td>
                                        <td scope="col"><?php //echo $row['price']; ?></td>
                                        <td scope="col"><?php //echo $row['status']; ?></td>
                                        <td scope="col">
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
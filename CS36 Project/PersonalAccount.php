<?php     
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);    

    //Get the ID of the logged in user
    if (isset($_SESSION['memberID'])) 
        $memberID = $_SESSION['memberID'];    
    else {
    // Redirect to login page or handle the error
        header("Location: LogIn.php");
        exit();
    }  

    $fName = $lName = $phone = "";

    //Change profile information
    if (isset($_POST['change'])){
        $fName = $_POST['fName'];
        $lName = $_POST["lName"];       
        $phone = $_POST["phone"];

        $sql = $conn->prepare("UPDATE members SET firstName = ?, lastName = ?, phoneNumber = ? WHERE memberID = ?");
        $sql->bind_param("sssi", $fName, $lName, $phone, $memberID);
        $sql->execute();

        $_SESSION['fName'] = $fName;
        $_SESSION['lName'] = $lName;
        $_SESSION['phone'] = $phone;
        
    }

    //Cancellation of booking
    if (isset($_POST['cancel'])){ 

        //Set booking status to cancel
        $bookingID = $_POST['bookingID'];
        $sql = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE bookingID = ?");
        $sql->bind_param("i", $bookingID);
        $sql->execute();
        
        //Remove values in room availability for the cancelled dates 
        $roomNumber = $_POST['roomNumber']; 
        $checkInDate = $_POST['checkInDate'];
        $checkOutDate = $_POST['checkOutDate'];        
        $current = strtotime($checkInDate);
        $end = strtotime($checkOutDate);

        //Loop through the dates and remove them from the room availability table
        while ($current < $end) {
            $date = date('Y-m-d', $current);
            $sql2 = $conn->prepare("DELETE FROM roomAvailability WHERE roomNumber = ? AND date = ?");
            $sql2->bind_param("ss", $roomNumber, $date);
            $sql2->execute();
            $current = strtotime("+1 day", $current);
        }
    } 
    
    if (isset($_POST['changeRoomType'])){
        $bookingID = $_POST['bookingID'];
        $roomType = $_POST['roomType'];

        //Store old number and dates for room Availabilty deletion
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
        
        if (!$newRoomNumber) {
            // Handle the case where the room doesn't exist
            
        } else {

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
?>
    
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="Booking&Account.css" rel="stylesheet">    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <header>
            <a href="HomePage.php" class="a-logo"> <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo"></a>

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

        <!-- Edit Room Number Modal-->
        <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoomModalLabel">Change Room Number</h5>
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
                                    <option value="penthouse">Penthouse</option>
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

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                <img src="resources\defaultprofile.png" class="profileimg rounded-circle pt-3 m-5 img-fluid mx-auto d-block">
                </div>
            
                <div class="col-md-3 mt-5">
                    <h4>First Name</h4><p><?php echo $_SESSION['fName']; ?></p><br>
                    <h4>Email</h4><p><?php echo $_SESSION['email']; ?></p><br>
                </div>

                <div class="col-md-3 mt-5">
                    <h4>Last Name</h4> <p><?php echo $_SESSION['lName']; ?></p><br>
                    <h4>Contact No.</h4> <p><?php echo $_SESSION['phone']; ?></p><br>
                    <!-- <form method="post"> -->
                        <button type="button" data-bs-toggle="modal" data-bs-target="#EditProfile">Edit Profile</button>
                    <!-- </form> -->

                    <div class="modal fade" id="EditProfile" tabindex="-1" aria-labelledby="dailyBookingsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="dailyBookingsModalLabel">Edit Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">                                       
                                        <form class="form" method="POST" autocomplete="off">                                            
                                            <div class="input-field">
                                                <label class="label">First Name</label><br>
                                                <input type="firstName" name="fName" placeholder="Enter your first name" value="<?php echo $_SESSION['fName']; ?>"required> <br>                                              
                                            
                                                <label class="label">Last Name</label><br>
                                                <input type="lastName" name="lName" placeholder="Enter your last name" value="<?php echo $_SESSION['lName']; ?>"required> <br>                                                

                                                <label class="label">Phone Number</label><br>
                                                <input type="firstName" name="phone" placeholder="Enter your phone number" value="<?php echo $_SESSION['phone']; ?>"required> <br>                                                                                            
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal" name="change">Accept Changes</button>
                                            </div>                                             
                                        </form>            
                                    </div>                                        
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div>
        <!-- Pending Booking-->
        <?php
            $sql = $conn->prepare("SELECT 1 FROM bookings WHERE memberID = ? AND status = 'pending'");
            $sql->bind_param("i", $memberID);
            $sql->execute();
            $result = $sql->get_result();

            //Check if there are any pending bookings
            $isPending = false;
            if ($result->num_rows > 0)
                $isPending = true;            
            else 
                $isPending = false;               
        ?>       
        <div style="display: <?php echo $isPending ? 'block' : 'none'; ?>;"> <!--Will only show if there are pending bookings-->
            <h1>Pending Bookings</h1>
            <hr>
            <table class="table custom-table">    
                <thead>        
                    <tr>
                        <th>BookingID</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Booked on</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>    
                </thead>   

                <tbody>         
                    <?php 
                        //Get all of the bookings of the user that are pending
                        $sql = $conn->prepare("SELECT a.bookingID, a.roomNumber, b.roomType, a.bookingDate, a.checkInDate, a.checkOutDate, a.status
                                               FROM bookings a
                                               INNER JOIN rooms b 
                                               ON a.roomNumber = b.roomNumber
                                               WHERE a.memberID = ?
                                               AND a.status = 'pending'
                                               ORDER BY a.bookingID DESC");
                        $sql->bind_param("i", $memberID);
                        $sql->execute();
                        $result = $sql->get_result();  
                    
                        if ($result->num_rows > 0) { //If there are bookings                           
                            while ($row = $result->fetch_assoc()) {
                    ?>                                                           
                    <tr>
                        <!--display booking-->
                        <td><?php echo $row['bookingID']; ?></td>
                        <td><?php echo $row['roomNumber']; ?></td>
                        <td><?php echo $row['roomType']; ?></td>
                        <td><?php echo $row['bookingDate']; ?></td>
                        <td><?php echo $row['checkInDate']; ?></td>
                        <td><?php echo $row['checkOutDate']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <form method="POST"> 
                                <!--hidden inputs to pass data to the server-->
                                <input type="hidden" name="bookingID" value="<?php echo $row['bookingID']; ?>">
                                <input type="hidden" name="roomNumber" value="<?php echo $row['roomNumber']; ?>">    
                                <input type="hidden" name="checkInDate" value="<?php echo $row['checkInDate']; ?>"> 
                                <input type="hidden" name="checkOutDate" value="<?php echo $row['checkOutDate']; ?>">                                                                               
                                <button type="button" name="edit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRoomModal">Change</button>
                                <button type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                            </form>
                        </td>
                    </tr> 
                    <?php  } 
                        }else {
                            echo "<tr><td colspan='8'>No bookings found</td></tr>";
                        } ?>                         
                </tbody>
            </table>
        </div>

        <div>
            <h1>Booking History</h1>
            <hr>
            <table class="table custom-table">    
                <thead>        
                    <tr>
                        <th>BookingID</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Booked on</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>                        
                    </tr>    
                </thead>    

                <tbody>         
                    <?php                     
                        $sql = $conn->prepare("SELECT a.bookingID, a.roomNumber, b.roomType, a.bookingDate, a.checkInDate, a.checkOutDate, a.status
                                               FROM bookings a
                                               INNER JOIN rooms b 
                                               ON a.roomNumber = b.roomNumber
                                               WHERE a.memberID = ?
                                               ORDER BY a.bookingID DESC");
                        $sql->bind_param("i", $memberID);
                        $sql->execute();
                        $result = $sql->get_result();                        

                        if ($result->num_rows > 0) { //If there are bookings                           
                            while ($row = $result->fetch_assoc()) {
                    ?>                                                           
                    <tr>
                        <td><?php echo $row['bookingID']; ?></td>
                        <td><?php echo $row['roomNumber']; ?></td>
                        <td><?php echo $row['roomType']; ?></td>
                        <td><?php echo $row['bookingDate']; ?></td>
                        <td><?php echo $row['checkInDate']; ?></td>
                        <td><?php echo $row['checkOutDate']; ?></td>
                        <td><?php echo $row['status']; ?></td>                        
                    </tr> 
                    <?php  
                    } 
                        }else {
                            echo "<tr><td colspan='8'>No bookings found</td></tr>";
                        } ?>                         
                </tbody>
            </table>
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
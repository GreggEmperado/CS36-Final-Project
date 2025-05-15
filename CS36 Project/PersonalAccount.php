<?php     
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);    

    //Cant access this page if not logged in
    if (!isset($_SESSION['memberID'])){
        header("Location: LogIn.php");
        exit();
    }

    $memberID = $_SESSION['memberID'];

    $sql = $conn->prepare("SELECT a.bookingID, a.roomNumber, b.roomType, a.bookingDate, a.checkInDate, a.checkOutDate, a.status
                           FROM bookings a
                           INNER JOIN rooms b 
                           ON a.roomNumber = b.roomNumber
                           WHERE a.memberID = ?
                           ORDER BY a.bookingDate DESC");
    $sql->bind_param("i", $memberID);
    $sql->execute();
    $result = $sql->get_result();    
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
            <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo" href="HomePage.Php">

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

    <div class="container mt-5">
        <!-- Account Editing -->
        <div class="row">
            <div class="col-md-6">
            <img src="resources\defaultprofile.png" class="profileimg rounded-circle pt-3 m-5 img-fluid mx-auto d-block">
            </div>
           
            <div class="col-md-3 mt-5">
                <form method="POST">
                    <h4>First Name</h4><p><input type="text" name="fName" value="<?php echo $_SESSION['fName']; ?>"></p><br>
                    <h4>Email</h4><p><input type="text" name="Email" value="<?php echo $_SESSION['email']; ?>"></p><br>
                </form>
            </div>

            <div class="col-md-3 mt-5">
                <form method="POST">
                    <h4>Last Name</h4> <p><input type="text" name="Lname" value="<?php echo $_SESSION['lName']; ?>"></p><br>
                    <h4>Contact No.</h4> <p><input type="text" name="Contact" value="<?php echo $_SESSION['phone']; ?>"></p><br>
                    <button type="submit" name="edit details">Edit Details</button>
                </form>
            </div>            
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
                        <th>Action</th>
                    </tr>    
                </thead>    

                <tbody>         
                    <?php            
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
                                <td>
                                    <form method="POST" action="">                                                                                  
                                        <button type="submit" name="edit" class="btn btn-primary">Change</button>
                                        <button type="submit" name="delete" class="btn btn-danger">Cancel</button>
                                    </form>
                                </td>
                            </tr> 
                            <?php  } }else {
                                        echo "<tr><td colspan='8'>No bookings found</td></tr>";
                                        } ?>                         
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <h1>RKG Hotel</h1>
        <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
        <p>rkghotel@gmail.com</p>
    </footer>

    </body>
</html>
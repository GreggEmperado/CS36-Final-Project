<?php
    session_start();  
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error);     
    
    $dbMemberID = $email = $dbFName = $password = $dbPassword = $dbLName = $dbEmail = $dbPhone = "";
    $emailErr = $passErr = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){   

        if (empty($_POST['email']))
            $userErr = "Email is required";
        else             
            $email = clean_input($_POST['email']);  
        
        if (empty($_POST['password']))
            $passErr = "Password is required";
        else                         
            $password = clean_input($_POST['password']);   
        
        if (!empty($username) && !empty($password)){

            $search = $conn->prepare("SELECT memberID, firstName, lastName, email, phoneNumber, password FROM members WHERE email = ?");
            $search->bind_param("s", $email);
            $search->execute();

            $search->store_result();
            if ($search->num_rows === 1){
                $search->bind_result($dbMemberID, $dbFName, $dbLName, $dbEmail, $dbPhone, $dbPassword);
                $search->fetch();

                if ($password == $dbPassword){
                    $_SESSION['fName'] = $dbFName;
                    $_SESSION['memberID'] = $dbMemberID;  
                    $_SESSION['lName'] = $dbLName;
                    $_SESSION['email'] = $dbEmail;
                    $_SESSION['phone'] = $dbPhone;                    
                                     
                    header("Location: HomePage.php");          
                }
                else
                    $passErr = "Invalid password.";
            }
            else
                $emailErr = "Email not found.";
        }  
    }      
   
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }   
?>

<!DOCTYPE html>
<html>
    <head>
        <title>RKG Hotel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="Hotel.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <header class="header-login">
            <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo-login">
        </header>

        <div class="form-log-in">
            <form class="form" method="POST">
                <h1>Log In</h1>
                <div class="input-field">

                  <label class="label">Email</label><br>
                  <input type="email" name="email" placeholder="Enter your email" value="<?php echo $email; ?>"> <br>
                  <p><?php echo " ".$emailErr; ?></p>
                  
                  <label class="label">Password</label><br>
                  <input type="password" name="password" placeholder="Enter your password" required>
                  <p><?php echo " ".$passErr; ?></p>
                </div>

                <div class="button-group">
                  <button class="signin" type="submit" name="login" href="HomePage.php">Log In</button>
                </div>



                <div class="newAcc">
                  <a href="Sign-up.php">Make new account?</a>
                </div>
            </form>

        </div>

        <footer>
            <h1>RKG Hotel</h1>
            <p>Dumaguete City, Negros Oriental 6200, Philippines</p>
            <p>rkghotel@gmail.com</p>
        </footer>
    </body>
</html>
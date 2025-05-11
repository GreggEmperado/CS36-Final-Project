<?php
    $servername = "localhost"; $username = "root"; $password = ""; $database = "hotelDB";
    $conn = new mysqli($servername, $username, $password, $database);   
    if ($conn->connect_error)   
        die("Connection failed ".$conn->connect_error); 
    
    $memberID = NULL;
    $msg = $fName = $lName = $phone = $email = $password = $confirm = "";
    $msgErr = $fNameErr = $lNameErr = $phoneErr = $emailErr = $passwordErr = $confirmErr = "";
    $fNameTemp = $lNameTemp = $phoneTemp = $emailTemp = $passTemp = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){   
        if (empty($_POST['fName']))
            $fNameErr = "First name is required.";
        else{
            $fNameTemp = clean_input($_POST['fName']);
            if (preg_match("/[0123456789]/", $fNameTemp))
                $fNameErr = "No numbers allowed.";
            else
                $fName = $fNameTemp;
        }
            
        if (empty($_POST['lName']))
            $lNameErr = "Last name is required.";
        else{
            $lNameTemp = clean_input($_POST['lName']);
            if (preg_match("/[0123456789]/", $lNameTemp))
                $lNameErr = "No numbers allowed.";
            else
                $lName = $lNameTemp;
        }            

        if (empty($_POST['phone']))
            $phoneErr = "Phone number is required.";
        else{
            $phoneTemp = clean_input($_POST['phone']);
            if (!preg_match("/[0123456789]/", $phoneTemp))
                $phoneErr = "No letters allowed.";
            else
                $phone = $phoneTemp;
        }            

        if (empty($_POST['email']))
            $emailErr = "Email is required.";
        else{
            $emailTemp = clean_input($_POST["email"]);
            if (!filter_var($emailTemp, FILTER_VALIDATE_EMAIL))
                $emailErr = "Invalid email.";
            else
                $email = $emailTemp;
        }            
    
        if (empty($_POST['password']))
            $passwordErr = "Password is required.";
        else{
            $passTemp = clean_input($_POST['password']); 
            if (!preg_match('/^(?=.*\d).{6,}$/', $passTemp))
                $passwordErr = "Password must be at least 6 characters and contain at least a number."; 
            else
                $password = $passTemp;
        }   
        
        if (empty($_POST['confirmPassword']))
            $confirmErr = "Confirmation is required.";
        else
            $confirm = clean_input($_POST['confirmPassword']);   
        
        if (!empty($password) && !empty($confirm) && $password !== $confirm) {
            $confirmErr = "Passwords do not match.";
    }
        
        if (empty($fNameErr) && empty($lNameErr) && empty($phoneErr) && empty($emailErr) && empty($passwordErr) && empty($confirmErr)){ 
                       
            $add = $conn->prepare("INSERT INTO members (memberID, firstName, lastName, phoneNumber, email, password) VALUES (?,?,?,?,?,?)");
            $add->bind_param("isssss", $memberID, $fName, $lName, $phone, $email, $password);
            $add->execute();           
            $conn->close();             
            header("Location: LogIn.php");
            exit();
                      
        }
        else
            echo $memberID.$fName.$lName.$phone.$email.$password; //TEMPORARY TEMPORTARY RTERSMEP
        $conn->close();        
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
            <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo-login" href="HomePage.Php">
        </header>

        <div class="form-log-in">
            <form class="form" method="POST">
                <h1>Sign Up</h1>

                <div class="input-field">
                    <label class="label">First Name</label><br>
                    <input type="firstName" name="fName" placeholder="Enter your first name" value="<?php echo $fName; ?>" required> <br>
                    <p><?php echo " ".$fNameErr; ?></p>
                  
                    <label class="label">Last Name</label><br>
                    <input type="lastName" name="lName" placeholder="Enter your last name" value="<?php echo $lName; ?>" required> <br>
                    <p><?php echo " ".$lNameErr; ?></p>

                    <label class="label">Email</label><br>
                    <input type="email" name="email" placeholder="Enter your email" value="<?php echo $email; ?>" required> <br>
                    <p><?php echo " ".$emailErr; ?></p>

                    <label class="label">Phone Number</label><br>
                    <input type="firstName" name="phone" placeholder="Enter your phone number" value="<?php echo $phone; ?>" required> <br>
                    <p><?php echo " ".$phoneErr; ?></p>
                  
                    <label class="label">Password</label><br>
                    <input type="password" name="password" placeholder="Enter your password" required> <br>
                    <p><?php echo " ".$passwordErr; ?></p>

                    <label class="label">Confirm Password</label><br>
                    <input type="password" name="confirmPassword" placeholder="Confirm your password" required> <br>
                    <p><?php echo " ".$confirmErr; ?></p>
                </div>
                <div class="button-group">
                  <button class= "signin" type="submit" class="button" href="LogIn.Php">Sign up</button>
                </div> 
                <div class="newAcc">
                  <a href="LogIn.php">Already Have an Account?</a>
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
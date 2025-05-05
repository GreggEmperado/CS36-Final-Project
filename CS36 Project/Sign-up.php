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
            <form class="form">
                <h1>Sign Up</h1>
                <div class="input-field">
                    <label class="label">First Name</label><br>
                    <input type="firstName" name="firstName" placeholder="Enter your firt name" required> <br>
                  
                    <label class="label">Last Name</label><br>
                    <input type="lastName" name="lastName" placeholder="Enter your last name" required> <br>

                    <label class="label">Email</label><br>
                    <input type="email" name="email" placeholder="Enter your email" required> <br>

                    <label class="label">Phone Number</label><br>
                    <input type="phone" name="phone" placeholder="Enter your phone number" required> <br>
                  
                    <label class="label">Password</label><br>
                    <input type="password" name="password" placeholder="Enter your password" required>

                    <label class="label">Confirm Password</label><br>
                    <input type="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div>

                <div class="button-group">
                  <button class= "signin" type="submit" class="button">Sign In</button>
                </div>



                <div class="newAcc">
                  <a href="#">Already Have an Account?</a>
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
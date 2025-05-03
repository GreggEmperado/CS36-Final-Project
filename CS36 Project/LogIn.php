<!DOCTYPE html>
<html>
    <head>
        <title>RKG Hotel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="Hotel.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <header>
            <img src="resources/RKGlogo.png" alt="RKG Hotel Logo" class="logo">

            <div class="navBar">
                <ul>

                    <li><a href="news.asp">Rooms & Accommodations</a></li>
                    <li><a href="contact.asp">Book Now</a></li>
                </ul> 
            </div>

            <div class="account dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="resources/person.png" alt="Account Photo" class="accountPhoto"> Hi Gregg
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="signout.php">Sign Out</a></li>
                </ul>
            </div>
        </header>

        <div class="form-log-in">
            <form class="form">
                <h1>Log In</h1>
                <div class="input-field">
                  <div class="label">Email</div>
                  <div class="input">
                    <input type="email" name="email" placeholder="Enter your email" required>
                  </div>
                </div>
                <div class="input-field">
                  <div class="label">Password</div>
                  <div class="input">
                    <input type="password" name="password" placeholder="Enter your password" required>
                  </div>
                </div>
                <div class="button-group">
                  <button type="submit" class="button">Sign In</button>
                </div>

                <div class="forgotPass">
                  <a href="#">Forgot password?</a>
                </div>

                <div class="newAcc">
                  <a href="#">Make new account?</a>
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
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
                <h1>Log In</h1>
                <div class="input-field">

                  <label class="label">Email</label><br>
                    <input type="email" name="email" placeholder="Enter your email" required> <br>
                  
                    <label class="label">Password</label><br>
                  <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="forgotPass">
                  <a href="#">Forgot password?</a>
                </div>

                <div class="button-group">
                  <button class= "signin" type="submit" class="button">Sign In</button>
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
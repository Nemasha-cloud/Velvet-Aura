<?php require_once 'config.php'?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="src/css/register.css" type="text/css">
    <link rel="shortcut icon" type="image/jpg" href="src/img/favi.jpg">

    <script type="text/javascript">
        // View password
        function checkPw() {
            var y = document.getElementById('password');
            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }
        }
    </script>
</head>
<body>
    <center>
        <img src="src/img/logo1.jpg">
        <div class="reg_overlay">
            <form name="regform" id="regform" method="post" action="enterReg.php" onsubmit="regValdiate(event);">
                <input type="text" name="fname" id="fname" placeholder="Full name"><br>
                <input type="date" name="dob" id="dob"><br>
                <input type="text" name="username" id="username" placeholder="Username"><br>
                <input type="password" name="password" id="password" placeholder="Password"><br>
                <input type="checkbox" name="" onclick="checkPw()">Check Password<br>
                <input type="submit" name="submit" value="Sign Up"><br>
                <b><i><div id="error_msg"></div></i></b>
            </form>

            <!-- Facebook Login Button with Icon -->
            <div style="margin-top: 20px;">
                <button id="fb-login-btn" style="background-color: #3b5998; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    <img src="src/img/facebook01.png" alt="Facebook" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 10px;">Sign Up with Facebook
                </button>
            </div>
        </div>
    </center>

    <!-- Facebook SDK and JavaScript -->
    <script type="text/javascript">
        // Load Facebook SDK asynchronously
        window.fbAsyncInit = function() {
            FB.init({
                appId      : 'YOUR_APP_ID_HERE', // Replace with your Facebook App ID
                cookie     : true,
                xfbml      : true,
                version    : 'v20.0' // Use the latest version as of March 2025
            });
            FB.AppEvents.logPageView();   
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // Handle Facebook Login Button Click
        document.getElementById('fb-login-btn').addEventListener('click', function() {
            FB.login(function(response) {
                if (response.authResponse) {
                    // User logged in successfully
                    FB.api('/me', {fields: 'name,email'}, function(response) {
                        if (response && !response.error) {
                            // For demo: Show a simple alert
                            alert('Logged in as: ' + response.name + '\nEmail: ' + response.email);
                            // In a full implementation, you'd redirect or process the data here
                        } else {
                            document.getElementById('error_msg').innerHTML = 'Error retrieving Facebook data';
                        }
                    });
                } else {
                    document.getElementById('error_msg').innerHTML = 'Facebook login cancelled or failed';
                }
            }, {scope: 'public_profile,email'}); // Request basic permissions
        });
    </script>
    
    <script type="text/javascript" src="src/js/regCheck.js"></script>
</body>
</html>
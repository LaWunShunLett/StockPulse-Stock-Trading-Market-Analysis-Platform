<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'includes/buildDatabase.php';

buildDatabase();
require_once 'includes/dbh.inc.php';
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!--Script for icons-->
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>

    <!--Script for Validation-->
    <script src="js/signUpValidate.js"></script>
    <script src="js/signInValidate.js"></script>

    <!-- Style -->
    <link rel="stylesheet" href="css/signInSignUp.css" />

    <title>Sign in & Sign up Form</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">

          <!-- Sign In Form -->
          <form action="includes/login.inc.php" method="POST" class="sign-in-form" onsubmit="validateSignInForm(event)">
            <h2 class="title">Sign in</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" name="usernameSignIn" id="usernameSignIn"/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" name="passwordSignIn" />
            </div>
            <div>
              <p id="showErrSignIn" style="color:red"></p> <!--To show error in Sign In Form-->
            </div>
            <input type="submit" value="Login" class="btn solid" />
            
          </form> <!--End here-->

          <!-- Sing Up Form -->
          <form action="includes/signup.inc.php" method="POST"  class="sign-up-form" 
          id = "signUpForm" onsubmit="validateSignUpForm(event)">
            <h2 class="title">Sign up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" name="username" id="username" />
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="text" placeholder="Email" name="email" id="email"/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" name="password"/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Confirm Password" name="confirm_password"/>
            </div>
            <div>
              <p id="showErrSignUp" style="color:red"></p> <!--To show error in Sign Up Form-->
            </div>
            <input type="submit" class="btn" value="Sign up" name="submit_btn" id="submit_btn"/>
           
          </form> <!--End here-->


        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>New here ?</h3>
            <p>
            Sign up for StockPulse to stay on top of market trends
            </p>
            <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button>
           <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 

    <dotlottie-player src="https://lottie.host/e16313af-01e1-4f81-aa33-0cb062debc44/3CJ5Jbytqz.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player></div>
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>Already have an account ?</h3>
            <p>
            If you already have an account, click here to log in.
            </p>
            <button class="btn transparent" id="sign-in-btn">
              Sign in
            </button>
         <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 

    <dotlottie-player src="https://lottie.host/98fc0ac8-f7d8-4174-b01a-6915da9699d7/3uvW0V76YK.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
        </div>
          </div>
          
        </div>
      </div>
    </div>

    <!-- Script for animation-->
    <script>
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".container");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });
    </script>


    <!-- Script to handle server error-->
    <script>
        window.onload = function() {
            // Check if the form data is available in the URL parameters
            let urlParams = new URLSearchParams(window.location.search);
            let error = urlParams.get('error');
            if (error === 'userNameTaken') {
                let username = urlParams.get('username');
                let email = urlParams.get('email');
                container.classList.add("sign-up-mode");
                document.getElementById('username').value = username;
                document.getElementById('email').value = email;
                document.getElementById('showErrSignUp').innerHTML = "Username or Email already exists";
            } else if (error === 'userDoesNotExist') {
                let username = urlParams.get('username');
                document.getElementById('usernameSignIn').value = username;
                document.getElementById('showErrSignIn').innerHTML = "User does not exist";
            } else if (error === 'wrongPassword') {
                let username = urlParams.get('username');
                document.getElementById('usernameSignIn').value = username;
                document.getElementById('showErrSignIn').innerHTML = "Incorrect Password";
            }
        };
    </script>
     
  </body>
</html>
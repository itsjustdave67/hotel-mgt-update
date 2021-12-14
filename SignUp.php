<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up</title>
  </head>

  <body>
    <div class="signupbox box">
      <img src="./assets/profile.png" alt="profile png" class="avatar">
      <h1 class="title">Staff Sign Up</h1>
      <p class="txt">Name</p>
      <input type="text" class="name1" name="name" id="full-name" placeholder="Enter Full Name">
      <p class="txt">Username</p>
      <input type="text" name="username" id="username" placeholder="Enter Username">
      <p class="txt">Email</p>
      <input type="text" name="email" id="email" placeholder="Enter Email">
      <p class="txt">Password</p>
      <input type="password" name="password1" id="password" placeholder="Enter Password">
      <input type="password" name="password2" id="password2" placeholder="Confirm Password">

      <input type="submit" value="SignUp" class="signup-btn">

    </div>

    <script type="module">
      // Import the functions you need from the SDKs you need
      import {
        initializeApp
      } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-app.js";
      import {
        getAuth,
        createUserWithEmailAndPassword
      } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-auth.js";
      import {
        getDatabase,
        ref,
        set,
        child
      } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-database.js";
      // TODO: Add SDKs for Firebase products that you want to use
      // https://firebase.google.com/docs/web/setup#available-libraries

      // Your web app's Firebase configuration
      const firebaseConfig = {
        apiKey: "AIzaSyCBfTvBzPLVcz4ExL4vvbXuE8ZYCALd5Ec",
        authDomain: "hotel-management-app-users.firebaseapp.com",
        projectId: "hotel-management-app-users",
        storageBucket: "hotel-management-app-users.appspot.com",
        messagingSenderId: "319356092792",
        appId: "1:319356092792:web:becf309499d9954958888d"
      };

      // Initialize Firebase
      const app = initializeApp(firebaseConfig);
      var auth = getAuth(app);

      var signUpBtn = document.querySelector('.signup-btn');

      signUpBtn.addEventListener('click', function register() {
        //get all our input fields
        var email = document.getElementById("email").value;
        var password = document.getElementById('password').value;
        var password2 = document.getElementById('password2').value;
        var full_name = document.getElementById('full-name').value;
        var username = document.getElementById('username').value;
        // Validate input fields

        if (validateEmail(email) == false || validatePassword(password) == false) {
          alert('Email and password is outta line')
          return
          //dont continue running the code
        }
        if (validateField(full_name) == false) {
          alert('outta line fields!')
          return
        }
        if (confirmPassword(password,password2) == false) {
          alert('Passwords do not match');
          return
        } 

        //Move on with auth

        createUserWithEmailAndPassword(auth, email, password)
          .then(function() {
            // Get the Current User
            var user = auth.currentUser
            //Add this user to Firebase Database
            var dbRef = ref(getDatabase())
            // Get the user Id
            var userId = user.uid;
            //Create user data

            var staff_data = {
              email: email,
              full_name: full_name,
              username: username,
              last_login: Date.now()
            }
            // Set the data once
            set(child(dbRef, `users/${userId}`), staff_data)
              .then(() => {
                console.log(" Data saved successfully!")
                location.replace("http://localhost/php_programs/class project/index.php")
              })
              .catch((error) => {
                console.log("The write failed...")
              });
          })
          .catch(function(error) {
            var error_code = error.code
            var error_message = error.message

            alert(error_message)
          })
      })

      function validateEmail(email) {
        var expression = /^[^@]+@\w+(\.\w+)+\w$/;
        if (expression.test(email) == true) {
          return true
        } else {
          return false
        }
      }

      function validatePassword(password) {
        //firebase only accepts lengths greater than 6
        if (password < 0) {
          return false;
        } else {
          return true;
        }
      }

      function validateField(field) {
        if (field == null) {
          return false;
        }
        if (field.length <= 0) {
          return false
        } else {
          return true;
        }
      }

      function confirmPassword(password, password2) {
        if (password === password2) {
          return true;
        }
        else {
          return false;
        }
      }
    </script>
  </body>

</html>
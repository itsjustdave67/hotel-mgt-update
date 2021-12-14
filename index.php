<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <header>
        <h1>Welcome to the JTND Hotel Management App</h1>
    </header>
        
    <main>
        <div class="ot-logins">
            <button class="admin-button">Admin Login</button>
            <button class="super-button">Supervisor Login</button>
            <button class="staff-button">Staff Login</button>
        </div>

        <div class="staff loginbox box">
            <img src="./assets/profile.png" alt="" class="avatar">
            <h1 class="title">Staff Login</h1>
                <p class="txt">Email</p>
                <input type="text" name="email" id="stf-email" placeholder="Enter Email">
                <p class="txt">Password</p>
                <input type="password" name="password" id="stf-password" placeholder="Enter Password">

                <button class="staffbtn" id="staff-login">Login</button>

                <a href="#">Forgotten password?</a>
                <a href="http://localhost/php_programs/class project/SignUp.php">Create an account?</a>
        </div>
    
        <div class="admin loginbox box">
            <img src="./assets/profile.png" alt="" class="avatar">
            <h1 class="title">Admin Login</h1>
                <p class="txt">Email</p>
                <input type="text" name="email" id="adm-email" placeholder="Enter Email">
                <p class="txt">Password</p>
                <input type="password" name="password" id="adm-password" placeholder="Enter Password">

                <button class="adminbtn" id="admin-login">Proceed</button>

                <a href="#">Forgotten password?</a>
        </div>
    
        <div class="supervisor loginbox box">
            <img src="./assets/profile.png" alt="" class="avatar">
            <h1 class="title">Supervisor Login</h1>
                <p class="txt">Email</p>
                <input type="text" name="email" id="sup-email" placeholder="Enter Email">
                <p class="txt">Password</p>
                <input type="password" name="password" id="sup-password" placeholder="Enter Password">

                <button class="supvbtn" id="supervisor-login">Proceed</button>

                <a href="#">Forgotten password?</a>
        </div>
    </main>

    <script type="module">
        // Import the functions you need from the SDKs you need
            import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-app.js";
            import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-auth.js";
            import { getDatabase, ref, child, update } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-database.js";
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
            const firebaseConfig1 = {

                apiKey: "AIzaSyBA3PAj516x8BBly1QOnnSfFNQYmL2yOR4",
                authDomain: "hotel-mgt-app-supervisors.firebaseapp.com",
                projectId: "hotel-mgt-app-supervisors",
                storageBucket: "hotel-mgt-app-supervisors.appspot.com",
                messagingSenderId: "894470471289",
                appId: "1:894470471289:web:5f60364d93c7a7286edea8"
            };
            const firebaseConfig2 = {
                apiKey: "AIzaSyAd5n-4pGUcoPv0E-VdoRnBkBO6x6OgHsg",
                authDomain: "hotel-management-app-admins.firebaseapp.com",
                projectId: "hotel-management-app-admins",
                storageBucket: "hotel-management-app-admins.appspot.com",
                messagingSenderId: "828426967308",
                appId: "1:828426967308:web:4c4ecf697e8f43f79b9129"
            }; 
            
            // var inputs = document.querySelectorAll("input");
            // inputs.setAttribute('autocomplete', 'off');

            var adminBtn = document.getElementById('admin-login');
            var supervisorBtn = document.getElementById('supervisor-login');
            var staffBtn = document.getElementById('staff-login');
            
        staffBtn.addEventListener('click',function login () {
            const app = initializeApp(firebaseConfig);
            var auth = getAuth(app);
            var email = document.getElementById('stf-email').value;
            var password = document.getElementById('stf-password').value;
            console.log(email);
            //Validate input fields
            if (validateEmail(email) == false || validatePassword(password) == false) {
                alert('Email and password is outta line')
                return
                //dont continue running the code
            }
           signInWithEmailAndPassword(auth,email,password)
           .then(function() {
            // Get current user
            var user = auth.currentUser
            //Add this user to Firebase Database
            var dbRef = ref(getDatabase(app))
            //Create user data
            var staff_data = {
                last_login : Date.now()
            }
            // Get the user Id
            var userId = user.uid;
        
            update(child(dbRef, `users/${userId}`), staff_data)
            .then(() => {
                console.log(" Logged In");
                location.href = "http://localhost/php_programs/class project/Staff.php";
            })
            .catch((error) => {
                console.log ("The update failed...")
            });
            //Done
            alert('Staff Member logged in!!')
           })
           .catch(function(error) {
            var error_code = error.code
            var error_message = error.message 
            console.log(error_message)
            alert(error_message) 
           })
        })  

        supervisorBtn.addEventListener('click',function login () {
            const app1 = initializeApp(firebaseConfig1);
            var auth1 = getAuth(app1);
            var email = document.getElementById('sup-email').value;
            var password = document.getElementById('sup-password').value;
            console.log(email);
            //Validate input fields
            if (validateEmail(email) == false || validatePassword(password) == false) {
                alert('Email and password is outta line')
                return
                //dont continue running the code
            }
           signInWithEmailAndPassword(auth1,email,password)
           .then(function() {
            // Get current user
            var user = auth1.currentUser
            //Add this user to Firebase Database
            var dbRef = ref(getDatabase(app1))
            //Create user data
            var supervisor_data = {
                last_login : Date.now()
            }
            // Get the user Id
            var userId = user.uid;
        
            update(child(dbRef, `supervisors/${userId}`), supervisor_data)
            .then(() => {
                console.log(" Logged In");
                location.replace("http://localhost/php_programs/class project/Supervisor.php")
            })
            .catch((error) => {
                console.log ("The update failed...")
            });
            //Done
            alert('Supervisor logged in!!')
           })
           .catch(function(error) {
            var error_code = error.code
            var error_message = error.message 
            
            alert(error_message) 
           })
        })
        
        adminBtn.addEventListener('click',function login () {
            const app2 = initializeApp(firebaseConfig2);
            var auth2 = getAuth(app2);
            var email = document.getElementById('adm-email').value;
            var password = document.getElementById('adm-password').value;
            console.log(email);
            //Validate input fields
            if (validateEmail(email) == false || validatePassword(password) == false) {
                alert('Email and password is outta line')
                return
                //dont continue running the code
            }
           signInWithEmailAndPassword(auth2,email,password)
           .then(function() {
            // Get current user
            var user = auth2.currentUser
            //Add this user to Firebase Database
            var dbRef = ref(getDatabase(app2))
            //Create user data
            var admin_data = {
                last_login : Date.now()
            }
            // Get the user Id
            var userId = user.uid;
        
            
            update(child(dbRef, `admins/${userId}`), admin_data)
            .then(() => {
                console.log(" Logged In");
                location.href = "http://localhost/php_programs/class project/Admin.php"
            })
            .catch((error) => {
                console.log ("The update failed...")
            });
            //Done
            alert('Administrator logged in!!')
           })
           .catch(function(error) {
            var error_code = error.code
            var error_message = error.message 
            
            alert(error_message) 
           })
        });
        
        function validateEmail(email) {
            var expression = /^[^@]+@\w+(\.\w+)+\w$/;
            if(expression.test(email) == true) {
                return true
            }else {
                return false
            }
        }

        function validatePassword(password) {
              //firebase only accepts lengths greater than 6
              if (password < 0) {
                  return false;
              }else {
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
        
    </script>

    <script src="Login.js"></script>
</body>
</html>
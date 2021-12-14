<?php
// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'todo');

$stf_tasks = mysqli_query($db, "SELECT * FROM staff_tasks");
$admin_tasks = mysqli_query($db, "SELECT * FROM all_tasks");

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css" />
  <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.13.0/css/all.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <title>Staff Dashboard</title>
</head>

<body class="body">
  <header>
    <div class="header-container">
      <div class="logo">
        <img src="" alt="logo" class="image" />
      </div>
      <p class="hotel-name">JTND Hotel Name</p>
    </div>
  </header>

  <main>
    <div class="logout">
      <button class="logout-btn btn" id="logout">LOG OUT</button>
    </div>
    <div class="submits-container">
      <div class="container-con">
        <div class="display assign-co">
          <div id="username"></div>
          <h3>Staff tasks</h3>
          <table>
            <thead>
              <tr>
                <th class="No">No</th>
                <th>Admin assigned Tasks</th>
              </tr>
            </thead>

            <tbody>
              <?php $i = 1; while ($row = mysqli_fetch_array($admin_tasks)) { ?>
              <tr>
                <td><?php echo $i; ?>
                </td>
                <td class="admin-task"><?php echo $row['admin_tasks']; ?>
                </td>
              </tr>
              <?php $i++; } ?>
            </tbody>
          </table>

          <table>
            <thead>
              <tr>
                <th class="No">No</th>
                <th>Staff Assigned Tasks</th>
              </tr>
            </thead>

            <tbody>
              <?php $i = 1; while ($row = mysqli_fetch_array($stf_tasks)) { ?>
              <tr>
                <td><?php echo $i; ?>
                </td>
                <td class="stf-task"><?php echo $row['stf_tasks']; ?>
                </td>
              </tr>
              <?php $i++; } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="container-psl">
      <form class="frm" method="POST" action="Staff.php" id="form">
        <div class="editor">
          <textarea 
          name="editor2" 
          id="editor2" 
          cols="30" 
          rows="10" 
          class="editor1"></textarea>
          
        </div>
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Personal Submit options
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <button class="dropdown-item" type="submit" id="submit-psl">Submit Personal</button>
          </div>
        </div>
      </form>

      <div class="display personal-co" id="display-container">
        <h3>Personal Staff Tasks</h3>
        
      </div>
    </div>

  </main>
  <script type="module">
    import {
      initializeApp
    } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-app.js";
    import {
      getAuth,
      onAuthStateChanged,
      signOut
    } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-auth.js";
    import {
      getDatabase,
      ref,
      child,
      get
    } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-database.js"
    import {
      getFirestore,
      doc,
      setDoc,
      collection,
      onSnapshot,
      query,
      deleteDoc
    } from "https://www.gstatic.com/firebasejs/9.6.0/firebase-firestore.js";

    const firebaseConfig = {
      apiKey: "AIzaSyCBfTvBzPLVcz4ExL4vvbXuE8ZYCALd5Ec",
      authDomain: "hotel-management-app-users.firebaseapp.com",
      projectId: "hotel-management-app-users",
      storageBucket: "hotel-management-app-users.appspot.com",
      messagingSenderId: "319356092792",
      appId: "1:319356092792:web:becf309499d9954958888d"
    };
    var app = initializeApp(firebaseConfig);

    var auth = getAuth(app);
    var db = getDatabase(app)
    var fs = getFirestore(app);

    var displayContainer = document.getElementById("display-container");

    // checking if the user is logged in or not
    onAuthStateChanged(auth, (user) => {
      if (user) {
        console.log('user is signed in at staff.php');

        const username = document.getElementById(`username`);

        var dbRef = ref(db)
        var userId = user.uid;

        get(child(dbRef, `users/${userId}/username`))
          .then((snapshot) => {
            if (snapshot.exists()) {
              console.log(snapshot.val());
              username.innerText = snapshot.val()
            } else {
              console.log(`users value does not exist`);
            }
          })
          .catch(err => {
            console.log(err.message);
          })
      } else {
        alert('Your login session has expired or you have logged out, login again to continue');
        location.href = "http://localhost/php_programs/class project/index.php";
      }
    })

    // retreiving the todo tasks one by one
    function renderData(individualDoc) {
      // parent element for the tasks
      var parentRow = document.createElement("div")
      parentRow.className = "psl-task parent-row";
      var dataId = parentRow.setAttribute('data-id', individualDoc.id);
      

      // task domain
      let taskDomain = document.createElement("div");
      taskDomain.className = "task-domain"
      taskDomain.innerHTML = individualDoc.data().todos;
      taskDomain.style.display = "inline-block";

      // button to delete todos
      let deleteBtn = document.createElement("button");
      deleteBtn.className = "delete-btn";

      //font-awesome trash icon
      let i = document.createElement("i");
      i.className = 'fas fa-trash';

      // appending
      deleteBtn.appendChild(i);

      parentRow.appendChild(taskDomain);
      parentRow.appendChild(deleteBtn);

      displayContainer.appendChild(parentRow);

      // adding click event to the delete button
      deleteBtn.addEventListener('click', (e) => {
        let id = e.target.parentElement.parentElement.getAttribute('data-id');
        console.log(id);
        onAuthStateChanged(auth, (user) => {
          if (user) {
            deleteDoc(doc(fs, user.uid, id));
          }
        })
      })
    
    }

    // adding personal todo list on submission of form

    const form = document.getElementById('form')
    const date = new Date();
    let counter = date.getTime();

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const todos = CKEDITOR.instances.editor2.getData();
      console.log(todos);

      let id = counter += 1;

      form.reset();
      onAuthStateChanged(auth, (user) => {
        if (user) {
          const tdRef = collection(fs, user.uid)
          const todoRef = doc(tdRef, `_` + id);
          setDoc(todoRef, {
            id: '_' + id,
            todos
          }).then(() => {
            console.log("data went through")
          }).catch((err) => {
            console.log(err.message)
          });
        }
      })
    });

    // logout button functionality
    var logoutBtn = document.getElementById('logout');
    logoutBtn.addEventListener('click', () => {
      auth.signOut()
    })

    // realtime events for the document changes
    onAuthStateChanged(auth, (user) => {
      if (user) {
        const q = query(collection(fs, user.uid));

        onSnapshot(q, (snapshot) => {
          let changes = snapshot.docChanges();

          changes.forEach((change) => {
            if (change.type === "added") {
              console.log("New personal task: ", change.doc.data());
              renderData(change.doc);
            } else if (change.type === "removed") {
              console.log("Removed task: ", change.doc.data());
              let li = displayContainer.querySelector(`[data-id='${change.doc.id}']`);
              console.log(li)
              displayContainer.removeChild(li);
            }
          });
        });

      }
    });

  </script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="./node_modules/ckeditor4/ckeditor.js"></script>
  <script type="text/javascript" src="Staff.js"></script>
</body>

</html>
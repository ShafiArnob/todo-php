<?php
  // Connect to the Database 
$servername = "localhost";
$username = "root";
$password = "";
$database = "todo-vip";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

//TEST: DB C0nnection
if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}
else{
  echo "Connection successful <br>";
}
//DELETE
if(isset($_GET['delete'])){
  $sid = $_GET['delete'];
  
  $sql = "DELETE FROM `todo` WHERE `sid` = $sid";
  $result = mysqli_query($conn, $sql);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDo List</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="style.css">

</head>
<body>
  <div class="display">
    
    <!-- Title -->
    <div class="todo-container">
      <div class="title-container">
        <h2>ToDo List</h2>
      </div>

      <!-- insert -->
      <div class="insert-container">
        <form action="">
          <input class="input-item" type="text" placeholder="Enter todo item">
          <input class="input-tag" type="text" placeholder="Enter tag">
          <button class="btn-insert" type="submit">Insert</button>
        </form>
      </div>

      <!-- LIST ITEMS -->
      <div class="list-container">
        <div class="list-items">

          <div class="list-item-header">
            <p>Title</p>
            <p>Tag</p>
            <div class="list-item-header-action">
              <p>Action</p>
            </div>
          </div>

        <?php
          $sql = "SELECT * FROM `todo`";
          $result = mysqli_query($conn, $sql);

          while($row = mysqli_fetch_assoc($result)){
            
            echo '<div class="list-item">
              <p>' . $row['item'] . '</p>
              <p>' . $row['tag'] . '</p>
              <div class="list-item-action">
                <button class="btn-edit">Edit</button>
                <button class="delete btn-delete"  id="d'.$row['sid'].'">X</button>
              </div>
            </div>';
          }
        ?>

        </div>
      </div>
    </div>
  </div>
  
  <script>

    //DELETE: Detect delete and where it is coming from
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        sno = e.target.id.substr(1);
        if (confirm("Are you sure you want to delete this note!")) {
          console.log("yes");
          window.location = `/php_workspace/todo-vip/index.php?delete=${sno}`;
        }
        else {
          console.log("no");
        }
      })
    })
  </script>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="bootstrap.js"></script>
</body>
</html>
<?php
  // Connect to the Database 
$servername = "localhost";
$username = "root";
$password = "";
$database = "todo-vip";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

//TEST: DB Connection
if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}
// else{
//   echo "Connection successful <br>";
// }

//DELETE
if(isset($_GET['delete'])){
  $sid = $_GET['delete'];
  
  $sql = "DELETE FROM `todo` WHERE `sid` = $sid";
  $result = mysqli_query($conn, $sql);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST["sidEdit"])){
    $sid = $_POST["sidEdit"];
    $itemName = $_POST["itemEdit"];
    $tag = $_POST["tagEdit"];
    
    $sql = "UPDATE `todo` SET `item` = '$itemName' , `tag` = '$tag' WHERE `todo`.`sid` = $sid";
    $result = mysqli_query($conn, $sql);

  }
  else{
    $item = $_POST["item"];
    $tag = $_POST["tag"];

    $sql = "INSERT INTO `todo` (`item`, `tag`) VALUES ('$item', '$tag')";
    $result = mysqli_query($conn, $sql);

    
    if(!$result){ 
      echo "Inserted"; 
    }
  }
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

    <!--Edit  Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-container">

          <div class="modal-header">
            <h3 id="editModalLabel">Edit List Items</h3>
          </div>

          <form action="/php_workspace/todo-vip/index.php" method="POST">
            <div class="modal-body">

              <input type="hidden" name="sidEdit" id="sidEdit">

              <div class="form-group">
                <label for="title">Item Name</label>
                <input type="text" class="form-control" id="itemEdit" name="itemEdit" aria-describedby="emailHelp">
              </div>

              <div class="form-group">
                <label for="desc">Item Tag</label>
                <input class="form-control" id="tagEdit" name="tagEdit" rows="3"></input>
              </div> 
            </div>

            <div class="modal-footer d-block mr-auto">
              <button type="button" class="btn-close" data-dismiss="modal">Close</button>
              <button type="submit" class="btn-insert">Update</button>
            </div>
          </form>

        </div>
      </div>
    </div>

      <!-- insert -->
      <div class="insert-container">
        <form action="/php_workspace/todo-vip/index.php" method="POST">
          <input id = "item" name="item" class="input-item" type="text" placeholder="Enter todo item">
          <input id = "tag" name="tag" class="input-tag" type="text" placeholder="Enter tag">
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
              <p class="list-item-title">' . $row['item'] . '</p>
              <p>' . $row['tag'] . '</p>
              <div class="list-item-action">
                <button class="btn-edit edit" id="d'.$row['sid'].'">Edit</button>
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
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((e)=>{
      e.addEventListener("click", (e) => {
        console.log("Edit");
        tr = e.target.parentNode.parentNode
        
        //gets the data from the Edit button
        item = tr.getElementsByTagName("p")[0].innerText
        tag = tr.getElementsByTagName("p")[1].innerText
        console.log(item, " - ",tag);

        //adds value to modal input
        itemEdit.value = item
        tagEdit.value = tag
        sidEdit.value = e.target.id.substr(1);

        //opens modal
        $("#editModal").modal('toggle')
      })
    })

    //DELETE: Detect delete and where it is coming from and pass to PHP Delete
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        sno = e.target.id.substr(1);
        if (confirm("Are you sure you want to delete this Item?")) {
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
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="bootstrap.js"></script>
</body>
</html>
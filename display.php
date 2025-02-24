<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Home</title>
  </head>
  <?php
    session_start();
    if(isset($_SESSION['delete_success'])) {
    echo "
      <div class='alert alert-warning' id='warningAlert'>
        Deletion Successful!
      </div>

      <script>
          let alertElement = document.getElementById('warningAlert');
          setTimeout(function() {
              alertElement.style.display = 'none';
          }, 3500);
      </script>
    ";
      unset($_SESSION['delete_success']);
    }
  ?>
  <body>
    <main>
        <div class="mx-5">
            <button class="btn btn-primary m-3"><a href="user.php" class="text-light text-decoration-none">Add user</a></button>
            <table class="table fs-6">
              <thead>
                <tr>
                  <th scope="col">Id</th>
                  <th scope="col">Name</th>
                  <th scope="col">Mobile</th>
                  <th scope="col">Email</th>
                  <th scope="col">Password</th>
                  <th scope="col">Operation</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include 'connect.php';

                  $sqlStatement = "
                    SELECT id, name, email, mobile, password FROM crud;
                  ";

                  $result = $conn->query($sqlStatement);
                  if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id']) . "</td>";
                        echo '<td>' . htmlspecialchars($row['name']) . "</td>";
                        echo '<td>' . htmlspecialchars($row['mobile']) . "</td>";
                        echo '<td>' . htmlspecialchars($row['email']) . "</td>";
                        echo '<td>' . htmlspecialchars($row['password']) . "</td>";
                        echo '<td>' .
                          "<button class='btn btn-primary '>
                            <a href='update.php? updateId = " . $row['id'] . "' class='text-decoration-none'>
                              Update
                            </a>
                          </button>

                          <button class='btn btn-danger'>
                            <a href='delete.php? deleteId=" . $row['id'] . "'class='text-decoration-none text-light'>
                              Delete
                            </a>
                          </button>"
                        . "</td>";
                      echo '</tr>';
                    }
                  }
                ?>
              </tbody>
            </table>
        </div>
    </main>
  </body>
</html>
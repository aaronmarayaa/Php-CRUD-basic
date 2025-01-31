<?php
$passwordMismatch = false;
$emailAlreadyExist = false;
$isSuccessful = false;

if($_SERVER['REQUEST_METHOD'] == "POST") {
    include 'connect.php';

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
    $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT) ?? null;
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;

    $sqlStatement = $conn->prepare("
        SELECT name, mobile, email, password FROM crud WHERE email = ?;
    ");
    $sqlStatement->bind_param("s", $email);
    $sqlStatement->execute();
    
    $result = $sqlStatement->get_result();

    if ($confirmPassword !== $password) {
        $passwordMismatch = true;
    } elseif ($result->num_rows > 0) {
        $emailAlreadyExist = true;
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sqlStatement = $conn->prepare("
                INSERT INTO crud (name, mobile, email, password)
                VALUES (?, ?, ?, ?);
            ");

            if(!$sqlStatement) {
                throw new Exception("Preparation failed: " . $conn->error);
            }

            $sqlStatement->bind_param("ssss", $name, $mobile, $email, $password);
            if($sqlStatement->execute()) { $isSuccessful = true; }
        } catch (Exception $exception) {
            error_log("Error: " . $exception->getMessage());
            $isSuccessful = false;
        } finally {
            if(isset($sqlStatement)) $sqlStatement->close();
            if(isset($conn)) $conn->close();
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Crud</title>
  </head>
  <?php
    if($isSuccessful) {
        echo '
            <div class="alert alert-success fade" role="alert" id="successAlert">
                Signed Up Successfully!
            </div>

            <script>
                let alertElement = document.getElementById("successAlert");
                setTimeout(function() {
                    alertElement.style.display = "none"
                }, 3500);
            </script>
        ';
    }
  ?>
  <body>
    <main class="d-flex justify-content-center m-4">
        <div class="card w-50 p-4">
            <form action="user.php" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Name: </label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
                </div>

                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile: </label>
                    <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address: </label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email Address" name="email">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password: </label>
                    <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
                </div>

                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password: </label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-50" name="submit">Submit</button>
                </div>
            </form>
    </div>
    </main>
  </body>
</html>
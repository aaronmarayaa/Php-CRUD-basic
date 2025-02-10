<?php
$passwordMismatch = false;
$emailAlreadyExist = false;
$isSuccessful = false;
$mobileNumberAlreadyExist = false;

if($_SERVER['REQUEST_METHOD'] == "POST") {
    include 'connect.php';

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
    $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT) ?? null;
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;

    $sqlStatement = $conn->prepare("
        SELECT name, mobile, email, password FROM crud WHERE email = ? OR mobile = ?;
    ");
    $sqlStatement->bind_param("si", $email, $mobile);
    $sqlStatement->execute();
    
    $result = $sqlStatement->get_result();

    if ($confirmPassword !== $password) {
        $passwordMismatch = true;
    } elseif ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            if ($row['mobile'] === $mobile) {
                $mobileNumberAlreadyExist = true;
            } elseif ($row['email'] == $email) {
                $emailAlreadyExist = true;
            }
        }
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
            <div class="alert alert-success" role="alert" id="successAlert">
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
                    <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" required min="5">
                </div>

                <?php
                    if($mobileNumberAlreadyExist) {
                        echo '
                            <p style="color: red;" id="errorMessage"><i><strong>Mobile number</strong> already exist!</i></p>

                            <script>
                                let alertElement = document.getElementById("errorMessage");
                                setTimeout(function() {
                                    alertElement.style.display = "none"
                                }, 3500);
                            </script>
                        ';
                    }
                ?>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile: </label>
                    <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile" required minlength="11" maxlength="11">
                </div>
                <?php
                    if($emailAlreadyExist) {
                        echo '
                            <p style="color: red;" id="errorMessage"><i><strong>Email</strong> already exist!</i></p>
                            
                            <script>
                                let alertElement = document.getElementById("errorMessage");
                                setTimeout(function() {
                                    alertElement.style.display = "none"
                                }, 3500);
                            </script>
                        ';
                    }
                ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address: </label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email Address" name="email" required>
                </div>

                <?php
                    if($passwordMismatch) {
                        echo '
                            <p style="color: red;" id="errorMessage"><i><strong>Password</strong> didn\'t match!</i></p>

                            <script>
                                let alertMessage = document.getElementById("errorMessage");
                                setTimeout(function() {
                                    alertMessage.style.display = "none";
                                }, 3500);
                            </script>
                        ';
                    }
                ?>
                <div class="mb-3">
                    <label for="password" class="form-label">Password: </label>
                    <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required minlength="8">
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password: </label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword" required minlength="8">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-50" name="submit">Submit</button>
                </div>
            </form>
    </div>
    </main>
  </body>
</html>
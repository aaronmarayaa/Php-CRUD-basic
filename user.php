<?php
if($_SERVER['REQUEST_METHOD'] == "POST") {
    include 'connect.php';

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
    $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT) ?? null;
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;
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
  <body>
    <main class="d-flex justify-content-center m-4">
        <div class="card w-50 p-4">
            <form action="user.php" method="post ">
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
                <input type="confirmPassword" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-50" name="submit">Submit</button>
            </div>
        </form>
    </div>
    </main>
  </body>
</html>
<?php
    $passwordMismatch = false;
    $emailAlreadyExist = false;
    $isSuccessful = false;
    $mobileNumberAlreadyExist = false;
    if(isset($_GET['updateId'])) {
        $id = filter_input(INPUT_GET, 'updateId', FILTER_VALIDATE_INT);
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include 'connect.php';

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
        $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT) ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirmPassword'] ?? null;

        try {
            $sqlStatement = $conn->prepare("
                UPDATE crud 
                SET  name = ?, email = ?, mobile = ?, password = ?
                WHERE id = ?;
            ");
            $sqlStatement->bind_param("ssisi", $name, $email, $mobile, $password, $id);

            if($sqlStatement->execute()) {
                header("location: display.php");
                
            } else {
                throw new Exception("Execution Failed: " . $sqlStatement->error);
            }
        } catch(Exception $exception) {
            error_log("Error" . $exception->getMessage());
        } finally {
            if(isset($sqlStatement)) { $sqlStatement->close(); }
            if(isset($conn)) { $conn->close(); }
        }
    }
?>

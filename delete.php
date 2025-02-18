<?php
    $deletionSuccessful = false;
    include 'connect.php';

    if(isset($_GET['deleteId'])) {
        $id = $_GET['deleteId'];

        $sqlStatement = $conn->prepare("
            DELETE FROM crud WHERE id = ?;
        ");
        $sqlStatement->bind_param("i", $id);
        if($sqlStatement->execute()) {
            $result = $sqlStatement->get_result();

            session_start();
            $_SESSION['delete_success'] = true;
            header('location:display.php');
            exit();
        }
    }
?>
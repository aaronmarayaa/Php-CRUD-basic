<?php
    include 'connect.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_GET['updateId'])) {
            $id = $_GET['updateId'];
        }
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? null;
        $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT) ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirmPassword'] ?? null;
    }
?>
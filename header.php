<?php
session_write_close();
session_start();

$user = (isset($_SESSION['auth_user'])) ?  $_SESSION['auth_user'] : null;

if (isset($_GET['action']) && $_GET['action'] == "logout")
    logout();

function logout()
{
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time() - 3600, "/");
    }
    $_SESSION  = [];
    session_destroy();

    header("location: login.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Crud</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <?php
                if (!isset( $_SESSION['auth_user'])) { ?>
                    <a href="login.php">Login</a> &nbsp; &nbsp;
                    <a href="register.php">Register</a> &nbsp; &nbsp;
                <?php
                } else {  ?>
                    <a href="index.php">Dashboard</a> &nbsp; &nbsp;
                    <a href="add_course.php">Add Courses</a>&nbsp; &nbsp;
                    <a href="courses.php">Viewe Courses</a>&nbsp; &nbsp;                   
                    <a href="reset_password.php">Reset Password</a>&nbsp; &nbsp;
                    <a href="?action=logout">Logout</a>
                   
                <?php } ?>
            </ul>
        </nav>
    </header>
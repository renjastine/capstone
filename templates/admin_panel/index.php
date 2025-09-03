<?php
session_start();
include '../check_log.php';
include '../connection.php';
include '../function.php';

$db = new Database($con);

check_logV2();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Capstone Project </title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav id= "sidebar">
        <img src="../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="../computer_panel">
                    <img src="../svg_icon/computer.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Computer List </span>
                </a>
            </li>
            <li>
                <a href="../student_panel">
                    <img src="../svg_icon/student.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Student List </span>
                </a>
            </li>
            <li>
                <a href="../attend_list">
                    <img src="../svg_icon/attendance.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Attendance List </span>
                </a>
            </li>
            <li>
                <a href="../login">
                    <img src="../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Log Out </span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="wrap">
        <header>HOME</header>
        <div class="computer">
            <div class="comp_row">
                <div class="add_pc pc">
                    <img src="../svg_icon/add_to_queue.svg"> 
                    <a class='button' href="../computer_panel/reg_computer">
                        Add Computer
                    </a>
                </div>
                <div class="edit_pc pc">
                    <img src="../svg_icon/manage_pc.svg"> 
                    <a class='button' href="../computer_panel/manage_pc">
                        Edit Computer
                    </a>
                </div>
            </div>
            <div class="comp_row">
                <div class="edit_stud stud">
                    <img src="../svg_icon/manage_account.svg"> 
                    <a class='button' href="../student_panel/manage">
                        Edit Student
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
session_start();
include '../../../connection.php';
include '../../../function.php';

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
$stud = $_SESSION['stud_no'];
$image = $_SESSION['image'];
$college = $_SESSION['college'];
$course = $_SESSION['course'];

$init = getInitials($fname);

$db = new Database($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Upload</title>
</head>
<body>
    <!-- <nav> 
        <img src="../../../logo/udm_logo.png" alt="Logo" class="logo">
        <a href="../../../computer_panel">Computer List</a>
        <a href="../../">Student List</a>
        <a href="#">Attendance Record</a>
        <a href="../../../">Log out</a>
    </nav> -->
    <nav id= "sidebar">
    <img src="../../../logo/udm_logo.png" alt="Logo" class="logo">
    <ul>
    </li>
        <li>
        <a href="../../../attendance">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m438-240 226-226-58-58-169 169-84-84-57 57 142 142ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>
        <span> Attendance </span>
    </a>
    </li>
        <li>
        <a href="../../../login">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
        <span> Log Out </span>
    </a>
    </li>
        </ul>
    </nav>

    <div class='wrapper'>
        <h1>
            <p>
                <?php 
                    if(isset($image)){
                        $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
                        $imageData = base64_decode($image);
                        $imageName = "{$stud}.jpeg";
                        $dir = '../../img/known/';
                        
                        $db->get_col_code($college);
                        $db->get_cour_code($course);
                        echo $db->registerStudent($dir, $imageName, $imageData);
                    }
                ?> 
            </p>
        </h1>
    </div>
</body>
</html>


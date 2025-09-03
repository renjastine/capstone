<?php 
session_start();
include '../../check_log.php';
include '../../function.php';

check_logV2();

$_SESSION['fname'] = null;
$_SESSION['lname'] = null;
$_SESSION['stud_no'] = null;
$_SESSION['year'] = null;
$_SESSION['college'] = null;
$_SESSION['course'] = null;
$_SESSION['image'] = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
<body onload="configure();">
    <div class="content">
        <div class="header">
            <header>Please take a picture for registration</header>
        </div>
        <nav id= "sidebar">  
            <img src="../../logo/udm_logo.png" alt="Logo" class="logo">
            <ul>
                <li>
                    <a href="../../attendance">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M360-390q-21 0-35.5-14.5T310-440q0-21 14.5-35.5T360-490q21 0 35.5 14.5T410-440q0 21-14.5 35.5T360-390Zm240 0q-21 0-35.5-14.5T550-440q0-21 14.5-35.5T600-490q21 0 35.5 14.5T650-440q0 21-14.5 35.5T600-390ZM480-160q134 0 227-93t93-227q0-24-3-46.5T786-570q-21 5-42 7.5t-44 2.5q-91 0-172-39T390-708q-32 78-91.5 135.5T160-486v6q0 134 93 227t227 93Zm0 80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-54-715q42 70 114 112.5T700-640q14 0 27-1.5t27-3.5q-42-70-114-112.5T480-800q-14 0-27 1.5t-27 3.5ZM177-581q51-29 89-75t57-103q-51 29-89 75t-57 103Zm249-214Zm-103 36Z"/></svg>
                        <span> Attendance </span>
                    </a>
                </li>
                <li>
                    <a href="../../attendance/register" id="darken">
                    <img src="../../svg_icon/person_add.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Register </span>
                    </a>
                </li>
                <li> 
                    <a href="../../login">
                    <img src="../../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                    <span> Log Out </span>
                    </a>
                </li>
            </ul>
        </nav>
        <form action="./form/index.php" method="POST">
            <div id="results">
                <img id = "webcam" src = "">
                <input type="hidden" name="image" id="image">
            </div>
            <div id="my_camera">   
            </div>
            <button name="save" id="save" type="button" onclick="saveSnap();">Capture</button>
            <button id="retry" type="button" onclick="configure();">Retry</button>
            <button name="proceed" id="proceed" type="submit">Proceed</button>
        </form>
    </div>

    <!-- webcam library -->
    <script type="text/javascript" src="../../assets/webcam.min.js">
    </script>
    
    <script type="text/javascript" src="script.js">
    </script>
    
</body>
</html>
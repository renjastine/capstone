<?php
session_start();
include '../../../function.php';

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];

$name = "{$fname} {$lname}";

$stud = $_SESSION['stud_no'];
$year = $_SESSION['year'];
$college = $_SESSION['college'];
$course = $_SESSION['course'];
$image = $_SESSION['image'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary</title>
</head>
<body>
    <!-- <nav> 
        <img src="../../../logo/udm_logo.png" alt="Logo" class="logo">
        <a href="../form">Back to Form</a>
        <a href="../">Retake Picture</a>
        <a href="../../">Student List</a>
        <a href="../../../admin_panel">Main Menu</a>
        <a href="../../">Log out</a>
    </nav> -->
    <nav id= "sidebar">
            <img src="../../../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
        <li>   
            <a href="../form">
            <img src="../../../svg_icon/arrow_back.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">            
            <span>Back to Form</span>    
        </a>
        </li>
        <li>
            <a href="../../../login">
            <img src="../../../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
            <span> Log Out </span>
        </a>
        </li>
        </ul>
        </nav>
    <div class="summary">
        <img id="image" src="<?php echo $image; ?>" alt="<?php echo $name;?>">
        <div class="info">
            <div id="data">Name: <?php echo $name; ?></div>
            <div id="data">Student No: <?php echo $stud; ?></div>
            <div id="data">Year: <?php echo $year; ?></div>
            <div id="data">College: <?php echo $college; ?></div>
            <div id="data">Course: <?php echo $course; ?></div>
        </div>
        <input type="button" id="button" onclick="redirect();" value="Submit">
    </div>

    <script type="text/javascript" src="script.js">
    </script>
</body>
</html>
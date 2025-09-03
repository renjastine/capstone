<?php 
session_start();
include '../check_log.php';
include '../function.php';
include '../connection.php';

$db = new Database($con);

$_SESSION['sn'] = '';

$path = '../student_panel/img/unknown/unknown.jpeg';
if (file_exists($path)){
    unlink($path);
}

if(isset($_POST['logout'])){
    $studno = $_POST['studno'];
    $pcName = $_POST['pcName'];
    $query = "UPDATE `attendance` SET `logout_time`='{$time}',`logged`='no' WHERE student_no = '{$studno}' AND computer_name = '{$pcName}' AND logged = 'yes'";
    $run = mysqli_query($con, $query);
}

$username = $_SESSION['uname'];
$row = $db->comp_is_logged($username);
if($row){
    header("Location: ./face_recog/index.php?sn={$row['student_no']}");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
</head>
<body onload="configure();">
    <div class="content">
        <nav id= "sidebar">  
            <img src="../logo/udm_logo.png" alt="Logo" class="logo">
            <ul>
                <li>
                    <a id="darken">
                        <img src="../svg_icon/student.svg">
                        <span> Attendance </span>
                    </a>
                </li>
                <li>
                    <a href="./register">
                        <img src="../svg_icon/person_add.svg">
                        <span>Register</span>
                    </a>
                </li>
                <li>
                    <a href="../login">
                        <img src="../svg_icon/logout.svg">
                        <span> Log out </span>
                    </a>
                </li>
            </ul>
        </nav>
        <form action="./face_recog/index.php" method="POST">
            <div id="box_loader">
                <div class="loader"></div>
                <div class="recog">Recognizing...</div> 
            </div>
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
    <script type="text/javascript" src="../assets/webcam.min.js">
    </script>
    
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
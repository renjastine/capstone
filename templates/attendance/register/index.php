<?php 
session_start();

$path = '../student_panel/img/unknown/unknown.jpeg';
if (file_exists($path)){
    unlink($path);
}

$msg = "Please use face recognition first to verify that you are not already registered.";
$studNum = $_SESSION['sn'];

if($studNum == '1'){
    $msg = "Error: No face found.";
}
else if($studNum == '2'){
    $msg = "Error: No face detected in known directory";
}
else if($studNum == '3'){
    $msg = "Error: 'Unknown' directory is empty.";
}
else if($studNum == '4'){
    $msg = "Error: 'Known' directory is empty.";
}
else if(strlen($studNum) > 1){
    $msg = 'You are already registered!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Register</title>
</head>
<body onload="configure();">
    <div class="content">
        <nav class="flex-c" id="sidebar">  
            <img src="../../logo/udm_logo.png" alt="Logo" class="logo">
            <ul class="flex-c">
                <li class="flex-c">
                    <a class="flex-r" href="../">
                        <img src="../../svg_icon/student.svg">
                        <span class="label"> Attendance </span>
                    </a>
                </li>
                <li class="flex-c darken">
                    <a class="flex-r">
                        <img src="../../svg_icon/person_add.svg">
                        <span class="label">Register</span>
                    </a>
                </li>
                <li class="flex-c">
                    <a class="flex-r" href="../../login">
                        <img src="../../svg_icon/logout.svg">
                        <span class="label"> Log out </span>
                    </a>
                </li>
            </ul>
        </nav>
        <form class="form flex-c" action="../face_recog/index.php" method="POST">
            <div class="header">  
                <p><?php echo $msg;?></p>
            </div>
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
            <button name="proceed-register" id="proceed" type="submit">Proceed</button>
            
        </form>
    </div>

    <!-- webcam library -->
    <script type="text/javascript" src="../../assets/webcam.min.js">
    </script>
    
    <script type="text/javascript" src="../script.js"></script>
</body>
</html>
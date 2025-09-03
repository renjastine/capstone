<?php
session_start();
include '../../connection.php';
include '../../function.php';

$db = new Database($con);

if(isset($_POST['image'])){
    $image = $_POST['image'];
    $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
    $imageData = base64_decode($image);
    $imageName = "unknown.jpeg";
    $dir = '../../student_panel/img/unknown/';
    file_put_contents($dir . $imageName, $imageData);

    if(isset($_POST['proceed-register'])){
        $_SESSION['register'] = true;
    } else {
        $_SESSION['register'] = false;
    }

    header("Location: ../../../../flaskapp/fr-result");
}
else if(isset($_GET['sn'])){
    $_SESSION['sn'] = $_GET['sn'];
    $studNo = $_SESSION['sn'];

    $_SESSION['button'] = '';

    $row = $db->student_is_logged($studNo);
    $currentPC = $db->get_computer_info($_SESSION['uname']);
    $currentPC = $currentPC['comp_name'];

    if(!$row){
        $_SESSION['button'] = 0;
    }else{
        $loggedPC = $row['computer_name'];
        if ($currentPC == $loggedPC){
            $_SESSION['button'] = 1;
        }
        else{
            $_SESSION['message'] = "Already logged in $loggedPC";
        }
    }

    if($_SESSION['register'] && $_SESSION['sn'] == '0'){
        header("Location: ../../student_panel/regStudent");
    } else if($_SESSION['register']) {
        header('Location: ../register');
    } else {
        header("Location: ./result/index.php");
    }

}
else {
    echo "No image";
}

if(isset($_POST['logout'])){
    $studno = $_POST['studno'];
    $pcName = $_POST['pcName'];
    $query = "UPDATE `attendance` SET `logout_time`='{$time}',`logged`='no' WHERE student_no = '{$studno}' AND computer_name = '{$pcName}' AND logged = 'yes'";
    $run = mysqli_query($con, $query);
}

?>
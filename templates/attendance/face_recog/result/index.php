<?php 
session_start();
include '../../../function.php';
include '../../../connection.php';

$db = new Database($con);

date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d');
$time = date('H:i:s');


$pcUser = $_SESSION['uname'];
$studNum = $_SESSION['sn'];

$pcInfo = $db->get_computer_info($pcUser);

$msg = '';

if($studNum == '1' or $studNum == '2' or $studNum == '3' or $studNum == '4' or $studNum == '0'){
    if($studNum == '1'){
        $msg = "Error: No face found.";
    }
    else if($studNum == '2'){
        $msg = "Error: No face was registered";
    }
    else if($studNum == '3'){
        $msg = "Error: 'Unknown' directory is empty.";
    }
    else if($studNum == '4'){
        $msg = "Error: 'Known' directory is empty.";
    }
    else{
        $msg = 'Error: No face matched.';
    }

    $name = '-';
    $studNum = '-';
    $course = '-';
    $college = '-';
    $_SESSION['button'] = '';
    $_SESSION['message'] = '';
}
else {
    $studInfo = $db->get_student_info($studNum);
    $name = $studInfo['firstname'] . " " . $studInfo['lastname'];
    $course = $studInfo['cour_code'];
    $college = $studInfo['col_code'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
</head>
<body>
    <div class="content">
        <nav id= "sidebar">  
            <img src="../../../logo/udm_logo.png" alt="Logo" class="logo">
            <ul>
                <li>
                    <a href="../../" id="darken">
                        <img src="../../../svg_icon/student.svg">
                        <span> Attendance </span>
                    </a>
                </li>
                <li>
                    <a href="../../../login">
                    <img src="../../../svg_icon/logout.svg">
                        <span> Log out </span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="bigger_box">
            <form class="boxForm" action="#" method="POST">
                <div class="image">
                    <img id="pic" src = "../../../student_panel/img/unknown/unknown.jpeg" alt="Confidential">
                    <div class="buttons">
                        <?php 
                            $bnt = "";
                            if(isset($_SESSION['button'])){
                                if($_SESSION['button'] == 1){
                                    $bnt = "logout";
                                }
                                else if($_SESSION['button'] == 0){
                                    $bnt = "login";
                                }
                            }
                            
                            if(isset($_POST['login'])){
                                $bnt = 'logout';
                                $_SESSION['button'] = 1;
                                $logint = $_POST['logint'];
                                $studno = $_POST['studno'];
                                $pcName = $_POST['pcName'];
                                
                                $row = $db->student_is_logged($studno);

                                if($row){
                                    echo "<script> alert('Already logged in!') </script>";
                                }
                                else{
                                    $query = "INSERT INTO `attendance`(`student_no`, `computer_name`, `date_`, `login_time`, `logged`) VALUES ('{$studno}','{$pcName}','{$date}','{$logint}','yes')";
                                    $run = mysqli_query($con, $query);
                                }

                            }
                            
                            if(isset($_POST['logout'])){
                                $studno = $_POST['studno'];
                                $pcName = $_POST['pcName'];
                                $query = "UPDATE `attendance` SET `logout_time`='{$time}',`logged`='no' WHERE student_no = '{$studno}' AND computer_name = '{$pcName}' AND logged = 'yes'";
                                $run = mysqli_query($con, $query);

                                header("Location: ../../");
                            }

                            if($bnt=="login"){
                                echo '<button name="login" id="btn" type="submit">Login</button>';
                            }else if($bnt=="logout"){
                                echo '<button name="logout" id="btn" type="submit">Logout</button>';
                            }else{
                                echo $_SESSION['message'];
                            }
                        ?>
                    </div>
                </div>
                <div class="box">
                    <header>Information</header>
                    <div class="info">
                        <div>
                            <label for="date">Date:</label>
                            <input type="text" id="date" name="date" value="<?php echo $date;?>" readonly> 
                        </div>
                        <div>
                            <label for="logint">Login Time:</label>
                            <input type="text" id="logint" name="logint" value="<?php echo $time;?>" readonly> 
                        </div>
                        <div>
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $name;?>" readonly> 
                        </div>
                        <div>
                            <label for="studno">Student No</label>
                            <input type="text" id="studno" name="studno" value="<?php echo $studNum;?>" readonly> 
                        </div>
                        <div>
                            <label for="course">Course</label>
                            <input type="text" id="course" name="course" value="<?php echo $course;?>" readonly> 
                        </div>
                        <div>
                            <label for="college">College</label>
                            <input type="text" id="college" name="college" value="<?php echo $college;?>" readonly> 
                        </div>
                        <div>
                            <label for="pcName">Computer</label>
                            <input type="text" id="pcName" name="pcName" value="<?php echo $pcInfo['comp_name'];?>" readonly> 
                        </div>
                    </div>
                    <div class="msg">
                        <?php echo $msg; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>    
</body>
</html>
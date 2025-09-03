<?php 
session_start();
include '../../../connection.php';
include '../../../function.php';

$db = new Database($con);

if(isset($_POST['image'])){
    $_SESSION['image'] = $_POST['image'];
}


if (!isset($_POST['fname']) and !isset($_POST['lname']) and !isset($_POST['stud_no']) and !isset($_POST['year']) and !isset($_POST['college']) and !isset($_POST['course'])){
    $_POST['fname'] = null;
    $_POST['lname'] = null;
    $_POST['stud_no'] = null;
    $_POST['year'] = null;
    $_POST['college'] = null;
    $_POST['course'] = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Information</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <nav id= "sidebar">
            <img src="../../../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>   
                <a href="../">
                    <img src="../../../svg_icon/reset.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Retake Photo </span> 
                </a>
            </li>
            <li>
                <a href="../../../login">
                    <img src="../../../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                    <span> Log Out </span>
                </a>
            </li>
        </ul>
        </nav>
        <div class="wrap">
        <header>REGISTER STUDENT</header>
        <div class="content">
            <form class="info" action="#" method="post">
        <div class="row">
            <label for="">Firstname</label><br>
            <input type="text" name="fname" id="fname" value="<?php echo $_SESSION['fname'];?>" required autofocus>
        </div>
        <div class="row">
            <label for="">Lastname</label><br>
            <input type="text" name="lname" id="lname" value="<?php echo $_SESSION['lname'];?>" required>
        </div>
        <div class="row">
            <label for="">Student Number</label><br>
            <input type="text" name="stud_no" id="stud_no" value="<?php echo $_SESSION['stud_no'];?>" required>
        </div>                
                <div class="yr">
                    <label for="">Year</label>
                    <select name="year" id="year" >
                        <option value="1st">1st</option>
                        <option value="2nd" <?php echo ($_SESSION['year'] == '2nd') ? 'selected' : ''?> >2nd</option>
                        <option value="3rd" <?php echo ($_SESSION['year'] == '3rd') ? 'selected' : ''?> >3rd</option>
                        <option value="4th" <?php echo ($_SESSION['year'] == '4th') ? 'selected' : ''?> >4th</option>
                        <option value="5th" <?php echo ($_SESSION['year'] == '5th') ? 'selected' : ''?> >5th</option>
                    </select>
                </div>
         <div class="row">
             <label for="">College</label><br>
             <input list="collegeList" name="college" value="<?php echo $_SESSION['college'];?>" required>
                <datalist id="collegeList">
                    <?php 
                        $db->getCollege();
                    ?>
                </datalist>
         </div>
         <div class="row">
             <label for="">Course</label><br>
             <input list="courseList" id="course" name="course" value="<?php echo $_SESSION['course'];?>" required>
                <datalist id="courseList">
                    <?php 
                        $db->getCourse();
                    ?>
                </datalist>
         </div>
                <button type="submit" id="pro" name="proceed">Proceed</button>
            
                <?php 
                    if(isset($_POST['proceed'])){
                        getData();
                        if(isset($_SESSION['course'])){
                            $db->match_college_course();
                        }  
                    }
                ?>
            </form>
        </div>
    </div>
</body>
</html>





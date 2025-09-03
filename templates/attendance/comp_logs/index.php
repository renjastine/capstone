<?php 
session_start();
include '../../connection.php';
include '../../function.php';

$comp = $_SESSION['uname'];

$db = new Database($con);
$comp = $db->get_computer_info($comp);
$comp = $comp['comp_name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Student List</title>
</head>
<body>
    <nav id= "sidebar">  
        <img src="../../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="../">
                    <img  href="./comp_logs" src="../../svg_icon/student.svg">
                    <span> Attendance </span>
                </a>
            </li>
            <li>
                <a id="darken">
                    <img src="../../svg_icon/logs.svg">
                    <span>Computer Logs</span>
                </a>
            </li>
            <li>
                <a href="../../login">
                    <img src="../../svg_icon/logout.svg">
                    <span> Log out </span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="wrap">
        <header><?php echo $comp; ?></header>
        <div class="sl_table">
            <table class="alternating-row-table">
                <tr class="header">
                    <th>Student No</th>
                    <th>Year</th>
                    <th>Course</th>
                    <th>College</th>
                    <th>PC Name</th>
                    <th>Date</th>
                    <th>Login</th>
                    <th>Logout</th>
                </tr>
                <?php 
                    

                    $query = "SELECT a.student_no, s.year, s.cour_code, s.col_code, a.computer_name, a.date_, a.login_time, a.logout_time
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no
                    WHERE a.computer_name='$comp'";

                    $run = mysqli_query($con, $query);
                    mysqli_data_seek($run, 0);
                    while($row = mysqli_fetch_array($run)){
                ?>
                <tr>
                    <td><?php echo $row['student_no']; ?> </td>
                    <td><?php echo $row['year']; ?> </td>
                    <td><?php echo $row['cour_code']; ?> </td>
                    <td><?php echo $row['col_code']; ?> </td>
                    <td><?php echo $row['computer_name'];?> </td>
                    <td><?php echo $row['date_']; ?> </td>
                    <td><?php echo $row['login_time']; ?> </td>
                    <td><?php echo $row['logout_time']; ?> </td>
                </tr> 
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
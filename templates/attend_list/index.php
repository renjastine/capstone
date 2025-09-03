<?php 
include '../connection.php';
include '../function.php';


$db = new Database($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Attendance List</title>
</head>
<body>
    <nav id= "sidebar"> 
        <img src="../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="./" id="darken">
                    <img src="../svg_icon/attendance.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M360-390q-21 0-35.5-14.5T310-440q0-21 14.5-35.5T360-490q21 0 35.5 14.5T410-440q0 21-14.5 35.5T360-390Zm240 0q-21 0-35.5-14.5T550-440q0-21 14.5-35.5T600-490q21 0 35.5 14.5T650-440q0 21-14.5 35.5T600-390ZM480-160q134 0 227-93t93-227q0-24-3-46.5T786-570q-21 5-42 7.5t-44 2.5q-91 0-172-39T390-708q-32 78-91.5 135.5T160-486v6q0 134 93 227t227 93Zm0 80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-54-715q42 70 114 112.5T700-640q14 0 27-1.5t27-3.5q-42-70-114-112.5T480-800q-14 0-27 1.5t-27 3.5ZM177-581q51-29 89-75t57-103q-51 29-89 75t-57 103Zm249-214Zm-103 36Z"/></svg>
                    <span> Attendance List </span>
                </a>
            </li>
            <li>
                <a href="../admin_panel">
                    <img src="../svg_icon/main_menu.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z"/></svg>
                    <span> Main Menu </span>
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
        <header>ATTENDANCE LIST</header>
        
        <div class="filters">
            <form action="./download_csv.php" method="POST">
                <select name="studNo" id="studNo">
                    <option value="-">Student No</option>
                    <?php $db->attendance_stud_no(); ?>
                </select>
        
                <select name="year" id="year">
                    <option value="-">Year</option>
                    <?php $db->attendance_year(); ?>
                </select>
        
                <select name="cour" id="cour">
                    <option value="-">Course</option>
                    <?php $db->attendance_cour(); ?>
                </select>
        
                <select name="col" id="col">
                    <option value="-">College</option>
                    <?php $db->attendance_col(); ?>
                </select>
        
                <select name="comp_name" id="comp_name">
                    <option value="-">PC Name</option>
                    <?php $db->attendance_comp(); ?>
                </select>
        
                <select name="date" id="date">
                    <option value="-">Date</option>
                    <?php $db->attendance_date(); ?>
                </select>
                
                <select name="login" id="login">
                    <option value="-">Login</option>
                    <?php time_();?>
                </select>
                
                <select name="logout" id="logout">
                    <option value="-">Logout</option>
                    <?php time_();?>
                </select>
                
                <input type="submit" name="csv" value="CSV FILE">
            </form>
        </div>

        <div class="table">
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
                $run = $db->get_attendance_list();
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
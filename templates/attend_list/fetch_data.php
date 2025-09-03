<?php 

include "../connection.php";

$query = "SELECT a.student_no, s.year, s.cour_code, s.col_code, a.computer_name, a.date_, a.login_time, a.logout_time
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no WHERE true";

if($_POST['studNo'] != '-'){
    $studNo = $_POST['studNo'];
    $query .= " AND a.student_no='{$studNo}'";
}

if($_POST['year'] != '-'){
    $year = $_POST['year'];
    $query .= " AND s.year='{$year}'";
}

if($_POST['cour'] != '-'){
    $cour = $_POST['cour'];
    $query .= " AND s.cour_code='{$cour}'";
}

if($_POST['col'] != '-'){
    $col = $_POST['col'];
    $query .= " AND s.col_code='{$col}'";
}

if($_POST['comp_name'] != '-'){
    $comp_name = $_POST['comp_name'];
    $query .= " AND a.computer_name='{$comp_name}'";
}

if($_POST['date'] != '-'){
    $date = $_POST['date'];
    $query .= " AND a.date_='{$date}'";
}

if($_POST['login'] != '-'){
    $login = $_POST['login'];
    $query .= " AND a.login_time LIKE '{$login}%'";
}

if($_POST['logout'] != '-'){
    $logout = $_POST['logout'];
    $query .= " AND a.logout_time LIKE '{$logout}%'";
}

$run = mysqli_query($con, $query);

?>

<table class="alternating-row-table">
    <?php $count = mysqli_num_rows($run);
        if($count){
    ?>
    <tr class="header">
        <th>Student No</th>
        <th>Year</th>
        <th>Course</th>
        <th>College</th>
        <th>Computer Name</th>
        <th>Date</th>
        <th>Login</th>
        <th>Logout</th>
    </tr>
    <?php 
        }else{
            echo "<p id='msg'>No Result</p>";
        }
        mysqli_data_seek($run, 0);
        while($row = mysqli_fetch_array($run)){
    ?>
    <tr>
        <td><?php echo $row['student_no']; ?></td>
        <td><?php echo $row['year']; ?></td>
        <td><?php echo $row['cour_code']; ?></td>
        <td><?php echo $row['col_code']; ?></td>
        <td><?php echo $row['computer_name'];?></td>
        <td><?php echo $row['date_']; ?> </td>
        <td><?php echo $row['login_time']; ?></td>
        <td><?php echo $row['logout_time']; ?></td>
    </tr> 
    <?php } ?>
</table>
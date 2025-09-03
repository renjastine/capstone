<?php 
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=attendance.csv");

include "../connection.php";

$query = "SELECT s.lastname, s.firstname, a.student_no, s.year, s.cour_code, s.col_code, a.computer_name, a.date_, a.login_time, a.logout_time FROM `attendance` a INNER JOIN students s ON a.student_no=s.stud_no WHERE true";

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

echo "Lastname,Firstname,Student No,Year,Course,College,Computer Name,Date,Login,Logout\n";

mysqli_data_seek($run, 0);
while($row = mysqli_fetch_array($run)){
    $lname= $row['lastname'];
    $fname = $row['firstname'];
    $studNo = $row['student_no'];
    $year = $row['year'];
    $cour = $row['cour_code'];
    $col = $row['col_code'];
    $comp = $row['computer_name'];
    $date = $row['date_'];
    $login = $row['login_time'];
    $logout = $row['logout_time'];

    echo "$lname, $fname, $studNo, $year, $cour, $col, $comp, $date, $login, $logout\n";
}
?>
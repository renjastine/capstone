<?php
include '../../function.php';
include '../../connection.php';

$db = new Database($con);

$comp;

if(isset($_GET['usr'])){
    $comp = $_GET['usr'];
}

$row = $db->get_computer_info($comp);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="./script.js"></script>
    <title> Capstone Project </title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <nav id= "sidebar">
        <img src="../../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="../">
                    <img src="../../svg_icon/computer.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Computer List </span>
                </a>
            </li>
            <li>
                <a href="../../student_panel">
                    <img src="../../svg_icon/student.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Student List </span>
                </a>
            </li>
            <li>
                <a href="../../attend_list">
                    <img src="../../svg_icon/attendance.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Attendance List </span>
                </a>
            </li>
            <li>
                <a href="../../login">
                    <img src="../../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Log Out </span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="wrap">
        <header><?php echo $row['comp_name'];?></header>
        <div class="options">
            <select name="hardware" id="hardware">
                <option value="cpu">CPU</option>
                <option value="hdd">HDD</option>
            </select>
            <select name="sensor" id="sensor">
                <option id='sensor_temp' value='temperature'>Temperature</option>
                <option id='sensor_clock' value='clock'>Clock</option>
                <option id='sensor_power' value='power'>Watts</option>
                <option id='sensor_used' value='used'>Used Space</option>
            </select>
            <button id='forecast'>FORECAST</button>
            <div class="loader"></div>
        
        </div>
        <div class="analysis">
            <div class="ts">
                <input type="hidden" id="comp" value="<?php echo $comp; ?>">
                <div class="graph" id="graph">
                </div>
                <div class="details">
                    <div class="r">Min: <span id="min"></span></div>
                    <div class="r">Max: <span id="max"></span></div>
                    <div class="r">Avg: <span id="avg"></span></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

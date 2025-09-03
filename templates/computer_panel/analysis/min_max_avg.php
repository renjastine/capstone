<?php 
include '../../connection.php';


if(isset($_POST['comp'])){
    $hardware = $_POST['hardware'];
    $sensor = $_POST['sensor'];
    $comp = $_POST['comp'];

    $value = '';
    $table = '';
    $sens = '';
    $divide = '';

    if($hardware == 'cpu'){
        $value = 'cpu_value';
        $table = 'cpu_status';
        $sens = 'cpu_sensor';
    }
    else{
        $value = 'hdd_value';
        $table = 'hdd_status';
        $sens = 'hdd_sensor';
    }

    $min = "MIN($value)";
    $max = "MAX($value)";
    $avg = "ROUND(AVG($value))";

    if ($sensor == 'clock'){
        $min = "ROUND(MIN($value)/1000, 2)";
        $max = "ROUND(MAX($value)/1000, 2)";
        $avg = "ROUND(AVG($value)/1000, 2)";
    }
    else if($sensor == 'power'){
        $min = "ROUND(MIN($value))";
        $max = "ROUND(MAX($value))";
        $avg = "ROUND(AVG($value))";
    }
    else if($sensor == 'used'){
        $min = "ROUND(MIN($value))";
        $max = "ROUND(MAX($value))";
        $avg = "ROUND(AVG($value))";
    }


    $query = "SELECT $min min, $max max, $avg avg FROM `$table` WHERE comp_name='$comp' and $sens='$sensor'";
    $run = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($run);
    $data = array();
    $data[] = $row;
    echo json_encode($data);

}

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
        if ($sensor == 'clock'){
            $divide = '/1000,2';
        }
    }
    else{
        $value = 'hdd_value';
        $table = 'hdd_status';
        $sens = 'hdd_sensor';
    }


    $query = "SELECT DATE_FORMAT(date_time, '%Y-%m-%d') as month, ROUND(AVG($value)$divide) as value FROM `$table` WHERE $sens='$sensor' and comp_name='$comp' GROUP BY month";
    $run = mysqli_query($con, $query);
    
    $data = array();
    mysqli_data_seek($run, 0);
    while($row = mysqli_fetch_assoc($run)){
        $data[] = $row;
    }

    echo json_encode($data);
}
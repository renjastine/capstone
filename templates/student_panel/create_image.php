<?php
include '../connection.php';

$query = "SELECT * FROM students";
$run = mysqli_query($con, $query);
mysqli_data_seek($run, 0);
while($row = mysqli_fetch_array($run)){
    $stud = $row['stud_no'];
    $image = $row['image'];

    if(!file_exists("./img/known/{$stud}.jpeg")){
        $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
        $imageData = base64_decode($image);
        $imageName = "{$stud}.jpeg";
        $dir = './img/known/';

        file_put_contents($dir . $imageName, $imageData);
        
    }    
}

echo "Images loaded.";
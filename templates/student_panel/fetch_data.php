<?php 

include "../connection.php";
include '../function.php';

$search = $_POST['search'];

$safe_search = mysqli_real_escape_string($con, $search);
$query = "SELECT * FROM `students` WHERE lastname LIKE '%{$safe_search}%' OR firstname LIKE '%{$safe_search}%' OR stud_no LIKE '%{$safe_search}%' ORDER BY lastname";
$run = mysqli_query($con, $query);

?>

<table class="alternating-row-table">
    <?php $count = mysqli_num_rows($run);
        if($count){
    ?>
    <tr class="header">
        <th>Name</th>
        <th>Student No.</th>
        <th>Year</th>
        <th>Course</th>
        <th>College</th>
    </tr>
    <?php 
        }else{
            echo "<p id='msg'>No Result</p>";
        }
        mysqli_data_seek($run, 0);
        while($row = mysqli_fetch_array($run)){
            $fname = $row['firstname'];
            $lname = $row['lastname'];
                                
            $name = "{$lname}, {$fname}";
    ?>
    <tr>
        <td><?php echo $name; ?> </td>
        <td><?php echo $row['stud_no']; ?> </td>
        <td><?php echo $row['year']; ?> </td>
        <td><?php echo $row['cour_code']; ?> </td>
        <td><?php echo $row['col_code']; ?> </td>
    </tr> 
    <?php } ?>
</table>
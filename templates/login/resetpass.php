<?php

include '../connection.php';

$query = "UPDATE `account` SET `password`='admin' WHERE acc_type='admin'";
$run = mysqli_query($con, $query);

echo "Admin Default\nUsername: pc\nPassword: admin";

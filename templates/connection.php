<?php
    // $servername = "172.16.35.127";
    // $userName = "capstone";
    // $password = "capstone";
    // $dbName = "capstone";
    
    $servername = "localhost";
    $userName = "root";
    $password = "";
    $dbName = "uhm";

    $con = mysqli_connect($servername, $userName, $password, $dbName);

    if (!$con) {
        die("Failed to connect!");
    }
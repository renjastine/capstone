<?php
session_start();
include '../connection.php';
include '../function.php';

logout();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
</head>
<body>  
    <div class="bg">
        <div class="title">
            <img src="../logo/udm_logo.png" alt="udmlogo">
            <h1>
                <p id="pa">Predictive Analytics</p> 
                <p id="cs">of Computer Status with</p>
                <p id="am">Attendance Monitoring</p>
                <p id="ifr">Integrating Facial Recognition</p>    
            </h1>
        </div>
        <form action="#" method="POST">
            <p id="text">Computer Login</p>

            <div class="row">
                <input type="text" id="username" name="username" autocomplete="off" placeholder="Username" required autofocus>
            </div>
            <div class="row">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="row">
                <input type="submit" id="login" name="login" value="LOGIN">
            </div>

            <p id="err">
                <?php 
                    if(isset($_POST['login'])){ // check if the user click the login button
                        $user = $_POST['username'];
                        $pass = $_POST['password'];
        
                        $login = new Login($user, $pass, $con); // object that we got from function.php
                        echo $login->check_valid(); // method that we got from function.php
                    }
                ?>
            </p>
        </form>        
    </div>
</body>
</html>

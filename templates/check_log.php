<?php

//check if there's a user logged in
function check_logV1($con){
    if(!isset($_SESSION['uname'])){ // isset checks if the value being check has a value or not. Return true if there's a value, otherwise false.
        header("Location: ./login"); // if isset returns true, this will redirect the website to login page
        die; // close the index.php
    }
    else{
        // echo "{$_SESSION['uname']} is logged in."; // this is just to check if there's already logged in.
        $user = $_SESSION['uname'];
        $query = "SELECT * FROM account WHERE username='$user'";
        $run = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($run);

        $acc_type = $row['acc_type'];
        if($acc_type == 'admin'){
            header("Location: ./admin_panel");
            die;
        }
        else {
            header("Location: ./attendance");
            die; 
        }
    }
}

// the same as the check_logV1 but will step back one directory first before redirecting to the file
function check_logV2(){
    if (!isset($_SESSION['uname'])){
        header("Location: ../login"); // notice the 2 dots before frontslash. This is what it means by stepping back 
    }
}

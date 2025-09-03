<?php

    $servername = "localhost";
    $userName = "root";
    $password = "";
    $dbName = "uhm";

    // connection to database
    $con = mysqli_connect($servername, $userName, $password, $dbName);

    if (!$con) {
        die("Failed to connect!");
    }

    // checking if the student number is already registered
    function isStudentNoRegistered($con, $studentNo) {
        $query = "SELECT * FROM students WHERE stud_no=$studentNo";
        $run = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($run);

        return $row;
    }

    // checking if the student number is genuine or registered in school
    function isGenuineStudentNo($studNo){
        // Example data extracted from the main server of the school
        $json = '{
            "studentData": [
                { "studentNo": "21-17-028", "studentName": "Ren Jastine Timajo" },
                { "studentNo": "21-17-036", "studentName": "Aldrin Apuyan" },
                { "studentNo": "21-17-207", "studentName": "Christopher Arceo" },
                { "studentNo": "21-17-046", "studentName": "Leika Mae Delfin" }
            ]
        }';
    
        $data = json_decode($json, true);
        
        // matching...
        foreach ($data['studentData'] as $student) {
            return $match = ($student['studentNo'] == $studNo) ? true : false ;
        }
    }


    // This is the example of the student number that the student is trying to register 
    $enteredStudentNo = '21-17-028';

    $row = isStudentNoRegistered($con, $enteredStudentNo);
    if($row){
        if(isGenuineStudentNo($enteredStudentNo)){
            // The student number is genuine
        } else {
            // The student number is not genuine
        }
    } else {
        // "Not registered yet"
    }
        

    ?>
<?php
function logout(){
    session_unset();
}

function getData(){
    $_SESSION['fname'] = ucwords($_POST['fname']);
    $_SESSION['lname'] = ucwords($_POST['lname']);
    $_SESSION['stud_no'] = $_POST['stud_no'];
    $_SESSION['year'] = $_POST['year'];
    $_SESSION['college'] = $_POST['college'];
    $_SESSION['course'] = $_POST['course'];
}

function getInitials($fn){
    $name_parts = explode(" ", $fn); 
    $initials = "";
    
    foreach($name_parts as $parts){
        $initials .= substr($parts, 0, 1);
    }
    
    return $initials;
}

function time_(){
    $time = array(7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19);
    foreach ($time as $t){
        echo "<option value='{$t}'>{$t}</option>";
    }
}

class Database {
    public $con;
    
    function __construct($con){
        $this->con = $con;
    }
    
    function getCollege(){
        $query = "SELECT * FROM `college`";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['col_desc']}'>{$row['col_code']}</option>";
        }
    }
    
    function getCourse(){
        $query = "SELECT * FROM `course`";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['cour_desc']}'>{$row['cour_code']}</option>";
        }
    }

    function match_college_course(){
        $course = $_SESSION['course'];
        $college = $_SESSION['college'];
    
        $query = "SELECT * FROM college cl 
                  LEFT JOIN course cr ON cl.col_code = cr.dep_code
                  WHERE (cl.col_code = '{$college}' OR cl.col_desc = '{$college}')
                  AND (cr.cour_code = '{$course}' OR cr.cour_desc = '{$course}')
                  LIMIT 1";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            echo "<script>document.location.href = '../summary';</script>";
        } else {
            echo "<script>alert('No relation between the course and college.');</script>";
        }
    }
    
    function get_cour_code($cour){
        $query = "SELECT * FROM `course` WHERE cour_code = '{$cour}' OR cour_desc = '{$cour}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            $_SESSION['course'] = $row['cour_code'];
        }
    }
    
    function get_col_code($col){
        $query = "SELECT * FROM `college` WHERE col_code = '{$col}' OR col_desc = '{$col}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            $_SESSION['college'] = $row['col_code'];
        }
    }

    function registerStudent($dir, $imageName, $imageData){
        $fname = $_SESSION['fname'];
        $lname = $_SESSION['lname'];
        $stud = $_SESSION['stud_no'];
        $year = $_SESSION['year'];
        $college = $_SESSION['college'];
        $course = $_SESSION['course'];
        $image = $_SESSION['image'];

        $query = "SELECT * FROM `students` WHERE stud_no='{$stud}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if(!$row){  
            $query = "INSERT INTO `students`(`lastname`, `firstname`, `stud_no`, `year`, `col_code`, `cour_code`, `image`) 
                      VALUES ('{$lname}','{$fname}','{$stud}','{$year}','{$college}','{$course}','{$image}')";
            $run = mysqli_query($this->con, $query);

            file_put_contents($dir . $imageName, $imageData);

            return "Registration Success!";
        } else {
            return "The student is already registered!";
        }
    }

    function regComputer($cname, $uname, $pass){
        $query = "INSERT INTO `account`(`comp_name`, `username`, `password`, `acc_type`) VALUES ('{$cname}','{$uname}','{$pass}','pc')";
        $run = mysqli_query($this->con, $query);
    }

    function isRegistered($uname, $cname){
        $query = "SELECT username FROM `account` WHERE username='{$uname}' OR comp_name='{$cname}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        return $row ? true : false;
    }

    function get_student_info($studNo){
        $query = "SELECT * FROM `students` WHERE stud_no = '{$studNo}' LIMIT 1";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        return $row;
    }

    function get_computer_info($pcUser){
        $query = "SELECT * FROM `account` WHERE username='{$pcUser}' LIMIT 1";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        return $row;
    }

    function search_student($search){
        $safe_search = mysqli_real_escape_string($this->con, $search);
        $query = "SELECT * FROM `students` WHERE lastname LIKE '%{$safe_search}%' OR firstname LIKE '%{$safe_search}%' OR stud_no LIKE '%{$safe_search}%' LIMIT 1";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);

        return $row;
    }

    function update_student($search, $studNo, $ln, $fn, $yr, $col, $cour, $oldStudNo){
        // check if the input college and course are related
        $query = "SELECT * FROM college cl 
                  LEFT JOIN course cr ON cl.col_code = cr.dep_code
                  WHERE (cl.col_code = '{$col}' OR cl.col_desc = '{$col}')
                  AND (cr.cour_code = '{$cour}' OR cr.cour_desc = '{$cour}')
                  LIMIT 1";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            // update the college and course
            $query = "UPDATE `students` SET `col_code`='{$row['col_code']}',`cour_code`='{$row['cour_code']}' WHERE lastname LIKE '%{$search}%' OR firstname LIKE '%{$search}%' OR stud_no LIKE '%{$search}%' LIMIT 1";
            $run = mysqli_query($this->con, $query);
        } else {
            echo "<script>alert('No relation between the course and college.');</script>";
        }            
        
        // check if student number is already taken
        $query = "SELECT * FROM `students` WHERE stud_no='{$studNo}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if(!$row){
            // rename the registered image
            $oldName = "../img/known/{$oldStudNo}.jpeg";
            $newName = "../img/known/{$studNo}.jpeg";
            rename($oldName, $newName);

            // update the student number
            $query = "UPDATE `students` SET `stud_no`='{$studNo}' WHERE lastname LIKE '%{$search}%' OR firstname LIKE '%{$search}%' OR stud_no LIKE '%{$search}%' LIMIT 1";
            $run = mysqli_query($this->con, $query);
        }
        
        // update lastname, firstname and year
        $query = "UPDATE `students` SET `lastname`='{$ln}',`firstname`='{$fn}',`year`='{$yr}' WHERE lastname LIKE '%{$search}%' OR firstname LIKE '%{$search}%' OR stud_no LIKE '%{$search}%' LIMIT 1";
        $run = mysqli_query($this->con, $query);   
    }

    function delete_student($studNo){
        $query = "DELETE FROM `students` WHERE stud_no='{$studNo}'";
        $run = mysqli_query($this->con, $query);
    }

    function search_computer($search){
        $safe_search = mysqli_real_escape_string($this->con, $search);
        $query = "SELECT * FROM `account` WHERE computer_id='{$safe_search}' OR comp_name LIKE '%{$safe_search}%' OR username LIKE '%{$safe_search}%'LIMIT 1";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);

        return $row;
    }

    function is_username_taken($username){
        $query = "SELECT * FROM `account` WHERE username='{$username}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        return $row;
    }

    function is_comp_name_already_taken($comp){
        $query = "SELECT * FROM `account` WHERE comp_name='{$comp}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        return $row;
    }

    function update_computer_username($search, $username){
        $query = "UPDATE `account` SET `username`='{$username}' WHERE computer_id='{$search}' OR comp_name LIKE '%{$search}%' OR username LIKE '%{$search}%' LIMIT 1";
        $run = mysqli_query($this->con, $query);
    }

    function update_comp_name($search, $comp){
        $query = "UPDATE `account` SET `comp_name`='{$comp}' WHERE computer_id='{$search}' OR comp_name LIKE '%{$search}%' OR username LIKE '%{$search}%' LIMIT 1";
        $run = mysqli_query($this->con, $query);
    }

    function update_computer_password($search, $password){
        $query = "UPDATE `account` SET `password`='{$password}'  WHERE computer_id='{$search}' OR comp_name LIKE '%{$search}%' OR username LIKE '%{$search}%' LIMIT 1";
        $run = mysqli_query($this->con, $query);
    }

    function update_computer($search, $comp, $username, $password){
        // check if username is already taken
        $row = $this->is_username_taken($username);
        if(!$row){
            $this->update_computer_username($search, $username);
        }
        
        $row = $this->is_comp_name_already_taken($comp);
        if(!$row){
            // update comp_name
            $this->update_comp_name($search, $comp);
        }

        $this->update_computer_password($search, $password);

    }

    function delete_computer($username){
        $query = "DELETE FROM `account` WHERE username='{$username}'";
        $run = mysqli_query($this->con, $query);
    }

    function get_status($comp){
        $query = "SELECT * FROM `attendance` WHERE computer_name='{$comp}' AND logged='yes'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            return "Active";
        }
        else{
            return "Off";
        }
    }

    function get_cpu_temp($user){
        $query = "SELECT ROUND(AVG(cpu_value)) as temperature FROM cpu_status WHERE cpu_sensor='temperature' AND comp_name='{$user}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            return $row['temperature'];
        } 
    }

    function get_cpu_clock($user){
        $query = "SELECT ROUND(AVG(cpu_value)/1000, 2) as clock FROM cpu_status WHERE cpu_sensor='clock' AND comp_name='{$user}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            return $row['clock'];
        } 
    }

    function get_cpu_power($user){
        $query = "SELECT ROUND(AVG(cpu_value)) as power FROM cpu_status WHERE cpu_sensor='power' AND comp_name='{$user}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            return $row['power'];
        } 
    }

    function get_hdd_temp($user){
        $query = "SELECT ROUND(AVG(hdd_value)) as temperature FROM hdd_status WHERE hdd_sensor='temperature' AND comp_name='{$user}'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            return $row['temperature'];
        } 
    }

    function get_hdd_used($user){
        $query = "SELECT ROUND(hdd_value) as used FROM `hdd_status` WHERE hdd_sensor='used' and comp_name='{$user}' ORDER by date_time DESC LIMIT 1";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);
        if($row){
            return $row['used'];
        } 
    }

    function attendance_stud_no(){
        $query = "SELECT DISTINCT(a.student_no)
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no
                    ORDER BY a.student_no";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['student_no']}'>{$row['student_no']}</option>";
        }
    }

    function attendance_year(){
        $query = "SELECT DISTINCT(s.year)
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no
                    ORDER BY s.year";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['year']}'>{$row['year']}</option>";
        }
    }

    function attendance_cour(){
        $query = "SELECT DISTINCT(s.cour_code)
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no
                    ORDER BY cour_code";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['cour_code']}'>{$row['cour_code']}</option>";
        }
    }

    function attendance_col(){
        $query = "SELECT DISTINCT(s.col_code)
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no
                    ORDER BY col_code";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['col_code']}'>{$row['col_code']}</option>";
        }
    }

    function attendance_comp(){
        $query = "SELECT DISTINCT(a.computer_name)
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no
                    ORDER BY LENGTH(computer_name), computer_name";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['computer_name']}'>{$row['computer_name']}</option>";
        }
    }

    function attendance_date(){
        $query = "SELECT DISTINCT(a.date_)
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no";
        $run = mysqli_query($this->con, $query);
        while($row = mysqli_fetch_array($run)){
            echo "<option value='{$row['date_']}'>{$row['date_']}</option>";
        }
    }

    function get_attendance_list(){
        $query = "SELECT a.student_no, s.year, s.cour_code, s.col_code, a.computer_name, a.date_, a.login_time, a.logout_time
                    FROM `attendance` a 
                    INNER JOIN students s 
                    ON a.student_no=s.stud_no";
        $run = mysqli_query($this->con, $query);
        return $run;
    }

    function comp_is_logged($username){
        $comp = $this->get_computer_info($username);
        $comp = $comp['comp_name'];
        
        $query = "SELECT * FROM attendance WHERE computer_name='$comp' and logged='yes'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);

        return $row;
    }

    function student_is_logged($studno){
        $query = "SELECT * FROM attendance WHERE student_no='$studno' and logged='yes'";
        $run = mysqli_query($this->con, $query);
        $row = mysqli_fetch_assoc($run);

        return $row;
    }
}

// Login class
class Login {
    public $uname;
    public $password;
    public $con;

    function __construct($uname = "", $password = "", $con = ""){
        $this->uname = $uname;
        $this->password = $password;
        $this->con = $con;
    }

    function check_valid(){
        $query = "SELECT username, password, acc_type FROM account WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, "s", $this->uname);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $username, $password, $acc_type);
        mysqli_stmt_fetch($stmt);

        if ($username) {
            if ($password == $this->password) {
                $_SESSION['uname'] = $this->uname;
                if ($acc_type == 'admin') {
                    header("Location: ../admin_panel");
                } else {
                    header("Location: /flaskapp/monitor?pc={$_SESSION['uname']}");
                }
            } else {
                return "Incorrect Password!";
            }
        } else {
            return "User not found!";
        }

        mysqli_stmt_close($stmt);
    }
}

?>

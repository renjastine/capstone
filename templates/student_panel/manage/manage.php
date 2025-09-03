<?php 
session_start();
include '../../connection.php';
include '../../function.php';

$db = new Database($con);
$student = array('firstname'=>'', 'lastname'=>'', 'stud_no'=>'', 'year'=>'', 'cour_code'=>'', 'col_code'=>'',);

$msg = "";
if(isset($_GET['search'])){
    $_SESSION['search'] = $_GET['search'];
    
    if($db->search_student($_SESSION['search'])){
        $student = $db->search_student($_SESSION['search']);
    }
    else {
        $msg = "{$_SESSION['search']} not found";
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Manage Student</title>
</head>
<body>
    <nav id= "sidebar">  
            <img src="../../logo/udm_logo.png" alt="Logo" class="logo">
            <ul>
                <li>
                    <a href="../">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M360-390q-21 0-35.5-14.5T310-440q0-21 14.5-35.5T360-490q21 0 35.5 14.5T410-440q0 21-14.5 35.5T360-390Zm240 0q-21 0-35.5-14.5T550-440q0-21 14.5-35.5T600-490q21 0 35.5 14.5T650-440q0 21-14.5 35.5T600-390ZM480-160q134 0 227-93t93-227q0-24-3-46.5T786-570q-21 5-42 7.5t-44 2.5q-91 0-172-39T390-708q-32 78-91.5 135.5T160-486v6q0 134 93 227t227 93Zm0 80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-54-715q42 70 114 112.5T700-640q14 0 27-1.5t27-3.5q-42-70-114-112.5T480-800q-14 0-27 1.5t-27 3.5ZM177-581q51-29 89-75t57-103q-51 29-89 75t-57 103Zm249-214Zm-103 36Z"/></svg>
                        <span> Student List </span>
                    </a>
                </li>
                <li>
                    <a href="../regStudent">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/></svg>
                        <span> Add Student </span>
                    </a>
                </li>
                <li>
                    <a href="../manage" id="darken">
                    <img src="../../svg_icon/manage_account.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M480-240Zm-320 80v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q37 0 73 4.5t72 14.5l-67 68q-20-3-39-5t-39-2q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32h240v80H160Zm400 40v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-340L683-120H560Zm300-263-37-37 37 37ZM620-180h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19ZM480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Z"/></svg>
                        <span> Edit Student </span>
                    </a>
                </li>
                <li>
                    <a href="../../admin_panel">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z"/></svg>
                        <span> Main Menu </span>
                    </a>
                </li>
                <li> 
                    <a href="../">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                        <span> Log Out </span>
                    </a>
                </li>
            </ul>
        </nav>
    <div class="wrap">
        <header>MANAGE STUDENT</header>
        
        <!-- Search Form -->
        <form id="formSearch" action="manage.php?search=<?php echo $_SESSION['search']; ?>" method="GET">
            <h1 id="notFound"> <?php echo $msg;?></h1>
            <input type="text" name="search" placeholder="Enter student number, first name, or last name" value="<?php echo $_SESSION['search']; ?>" required>
            <button type="submit">Search</button>
        </form>

        <form class="info" id="info" action="./index.php" method="POST">
            <input type="hidden" id="confirmation" name="confirmation" value="1">
            <ol>
                <li>
                    <div class="label">
                        <label>First Name</label>
                    </div>
                    <div class="input">
                        <input type="text" name="fname" value="<?php echo $student['firstname'];?>" required>
                    </div>
                </li>
                <li>
                    <div class="label">
                        <label>Last Name</label>
                    </div>
                    <div class="input">
                        <input type="text" name="lname" value="<?php echo $student['lastname'];?>" required>
                    </div>
                </li>
                <li>
                    <div class="label">
                        <label>Student No.</label>
                    </div>
                    <div class="input">
                        <input type="text" name="stud_no" value="<?php echo $student['stud_no'];?>" required>
                    </div>
                </li>
                <li>
                    <div class="label">
                        <label>Year</label>
                    </div>
                    <div class="input">
                        <select  name="year" required>
                            <option value="1st" <?php echo ($student['year'] == '1st') ? 'selected' : ''?>>1st</option>
                            <option value="2nd" <?php echo ($student['year'] == '2nd') ? 'selected' : ''?>>2nd</option>
                            <option value="3rd" <?php echo ($student['year'] == '3rd') ? 'selected' : ''?>>3rd</option>
                            <option value="4th" <?php echo ($student['year'] == '4th') ? 'selected' : ''?>>4th</option>
                            <option value="5th" <?php echo ($student['year'] == '5th') ? 'selected' : ''?>>5th</option>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="label">
                        <label>College</label>
                    </div>
                    <div class="input">
                        <input type= "text" list="collegeList" name="college" value="<?php echo $student['col_code'];?>" required>
                            <datalist id="collegeList">
                                <?php 
                                    $db->getCollege();
                                ?>
                            </datalist>
                    </div>
                </li>
                <li>
                    <div class="label">
                        <label>Course</label>
                    </div>
                    <div class="input">
                        <input type= "text" list="courseList" name="course" value="<?php echo $student['cour_code'];?>" required>
                            <datalist id="courseList">
                                <?php 
                                    $db->getCourse();
                                ?>
                            </datalist>
                    </div>
                </li>
            </ol>
            
            <div class="btn">
                <!-- Update Button -->
                <button type="submit" name="update">Update</button>
    
                <!-- Delete Button -->
                <button type="submit" name="delete" onclick='confirm_delete("<?php echo $student["lastname"]; ?>");'>Delete</button>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="script.js">
    </script>
</body>
</html>

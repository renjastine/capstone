<?php 
include '../connection.php';
include '../function.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Student List</title>
</head>
<body>
    <nav id= "sidebar"> 
        <img src="../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="./" id="darken">
                    <img src="../svg_icon/student.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Student List </span>
                </a>
                <a href="./manage">
                    <img src="../svg_icon/manage_account.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M480-240Zm-320 80v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q37 0 73 4.5t72 14.5l-67 68q-20-3-39-5t-39-2q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32h240v80H160Zm400 40v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-340L683-120H560Zm300-263-37-37 37 37ZM620-180h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19ZM480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Z"/></svg>
                    <span> Edit Student </span>
                </a>
            </li>
                <a href="../admin_panel">
                    <img src="../svg_icon/main_menu.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z"/></svg>
                    <span> Main Menu </span>
                </a>
            </li>
                <a href="../login">
                    <img src="../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                    <span> Log Out </span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="wrap">
        <header>STUDENT LIST</header>

        <!-- Search Form -->
        <form id="formSearch" action="manage.php" method="GET">
            <input type="text" id='search' name="search" placeholder="Enter student number or name" required>
        </form>

        <div class="sl_table">
            <table class="alternating-row-table">
                <tr class="header">
                    <th>Name</th>
                    <th>Student No.</th>
                    <th>Year</th>
                    <th>Course</th>
                    <th>College</th>
                </tr>
                <?php 
                $query = "SELECT * FROM students ORDER BY lastname";
                $run = mysqli_query($con, $query);
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
        </div>
    </div>
</body>
</html>
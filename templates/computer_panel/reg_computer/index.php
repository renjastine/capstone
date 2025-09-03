<?php 
session_start();
include '../../connection.php';
include '../../function.php';

$db = new Database($con);

if(!isset($_POST['cname']) and !isset($_POST['compUser']) and !isset($_POST['pass']) and !isset($_POST['cpass'])){
    $_POST['cname'] = null;
    $_POST['compUser'] = null;
    $_POST['pass'] = null;
    $_POST['cpass'] = null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Register Computer</title>
</head>
<body>
    <nav id= "sidebar"> 
        <img src="../../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="../">
                    <img src="../../svg_icon/computer.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Computer List </span>
                </a>
            </li>
                <a href="" id="darken">
                    <img src="../../svg_icon/add_to_queue.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Add Computer </span>
                </a>
            </li>
                <a href="../manage_pc" >
                    <img src="../../svg_icon/manage_pc.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404-360h-4q-71 0-127.5 18T180-306q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584-204l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628-460l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732-180l-12 60h-80Zm40-120q33 0 56.5-23.5T760-320q0-33-23.5-56.5T680-400q-33 0-56.5 23.5T600-320q0 33 23.5 56.5T680-240ZM400-560q33 0 56.5-23.5T480-640q0-33-23.5-56.5T400-720q-33 0-56.5 23.5T320-640q0 33 23.5 56.5T400-560Zm0-80Zm12 400Z"/></svg>
                    <span> Edit Computer </span>
                </a>
            </li>
                <a href="../../admin_panel">
                    <img src="../../svg_icon/main_menu.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z"/></svg>
                    <span> Main Menu </span>
                </a>
            </li>
                <a href="../../login">
                    <img src="../../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                    <span> Log Out </span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="wrap">
        <header>REGISTER COMPUTER</header>
        <form id="formRegComp"action="#" method="POST">
            <div class="row">
                <label for="">Computer Name</label><br>
                <input type="text" name="cname" value="<?php echo $_POST['cname']; ?>" autofocus required>
            </div>
            <div class="row">
                <label for="">Username</label><br>
                <input type="text" name="compUser" value="<?php echo $_POST['compUser']; ?>" required>
            </div>
            <div class="row">
                <label for="">Password</label><br>
                <input type="password" name="pass" value="<?php echo $_POST['pass']; ?>" required>
            </div>
            <div class="row">
                <label for="">Confirm Password</label><br>
                <input type="password" name="cpass" value="<?php echo $_POST['cpass']; ?>" required>
            </div>
            <input type="submit" id="reg" name="reg" value="REGISTER">

            <?php 
                if(isset($_POST["reg"])){
                    $cname = $_POST['cname'];
                    $username = $_POST['compUser'];
                    $pass = $_POST['pass'];
                    $cpass = $_POST['cpass'];

                    if($pass == $cpass){
                        $isReg = $db->isRegistered($username, $cname);
                        if($isReg){
                            echo "<p id='msg'>Already registered</p>";
                        }else{
                            $db->regComputer($cname, $username, $pass);

                            echo "<p style='color: green;'> Registered Success! <p>";
                        }
                    }else{
                        echo "<p id='msg'>Password and Confirm Password aren't matched!</p>";
                    }
                }
            ?>                
        </form>
    </div>
</body>
</html>
<?php 
session_start();
include '../check_log.php';
include '../function.php';
include '../connection.php';

// object
$db = new Database($con); 

check_logV2();

?>

<script>
    function status_design(usr){
        const stats = document.getElementById("status" + usr);
        const stats_light = document.getElementById("status_light" + usr);

        if (stats.innerHTML == "Status: Active"){
            stats_light.style.color = "#05dc05";
        }
        
        let cpu_temp = document.getElementById("cpu_temp" + usr);
        cpu_temp = cpu_temp.innerHTML.replace('°C', '');
        cpu_temp = parseInt(cpu_temp);
        const cpu_temp_light = document.getElementById("cpu_temp_light" + usr);
        if(cpu_temp < 70){
            cpu_temp_light.style.color = "#05dc05";
        }
        else if(cpu_temp >= 70 && cpu_temp <= 80){
            cpu_temp_light.style.color = "#daba07";
        }
        else if(cpu_temp >= 81){
            cpu_temp_light.style.color = '#e63737';
        }
        else{
            document.getElementById("cpu_temp" + usr).innerHTML = "-°C";
        }

        const cpu_clock = document.getElementById("cpu_clock" + usr);
        if (cpu_clock.innerHTML == ' GHz'){
            cpu_clock.innerHTML = '- GHz';
        }
        
        const cpu_power = document.getElementById("cpu_power" + usr);
        if (cpu_power.innerHTML == " W"){
            cpu_power.innerHTML = '- W';
        }
        
        let hdd_temp = document.getElementById("hdd_temp" + usr);
        hdd_temp = hdd_temp.innerHTML.replace('°C', '');
        hdd_temp = parseInt(hdd_temp);

        const hdd_temp_light = document.getElementById("hdd_temp_light" + usr);
        
        if (hdd_temp <= 4 || hdd_temp >= 51){
            hdd_temp_light.style.color = "#e63737";
        }
        else if((hdd_temp >= 5 && hdd_temp <= 19) || (hdd_temp >= 45 && hdd_temp <= 50)){
            hdd_temp_light.style.color = "#daba07";
        }
        else if (hdd_temp >= 20 && hdd_temp <= 45){
            hdd_temp_light.style.color = "#05dc05";
        }
        else {
            document.getElementById("hdd_temp" + usr).innerHTML = "-°C";
        }

        let hdd_used = document.getElementById("hdd_used" + usr);
        hdd_used = hdd_used.innerHTML.replace('%', '');
        hdd_used = parseInt(hdd_used);
        const hdd_used_light = document.getElementById("hdd_used_light" + usr);
        if(hdd_used < 65){
            hdd_used_light.style.color = "#05dc05";
        }
        else if(hdd_used >= 70 && hdd_used <= 80){
            hdd_used_light.style.color = "#daba07";
        }
        else if(hdd_used >= 81){
            hdd_used_light.style.color = '#e63737';
        }
        else{
            document.getElementById("hdd_used" + usr).innerHTML = "-%";
        }
        
    }
    
    function go_to_analysis(usr){
        document.location.href = "./analysis/index.php?usr=" + usr;
    }
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title> Capstone Project </title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav id= "sidebar"> 
        <img src="../logo/udm_logo.png" alt="Logo" class="logo">
        <ul>
            <li>
                <a href="./" id="darken">
                    <img src="../svg_icon/computer.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Computer List </span>
                </a>
            </li>
            <li>
                <a href="./reg_computer">
                    <img src="../svg_icon/add_to_queue.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <span> Add Computer </span>
                </a>
            </li>
            <li>
                <a href="./manage_pc">
                    <img src="../svg_icon/manage_pc.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404-360h-4q-71 0-127.5 18T180-306q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584-204l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628-460l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732-180l-12 60h-80Zm40-120q33 0 56.5-23.5T760-320q0-33-23.5-56.5T680-400q-33 0-56.5 23.5T600-320q0 33 23.5 56.5T680-240ZM400-560q33 0 56.5-23.5T480-640q0-33-23.5-56.5T400-720q-33 0-56.5 23.5T320-640q0 33 23.5 56.5T400-560Zm0-80Zm12 400Z"/></svg>
                    <span> Edit Computer</span>
                </a>
            </li>
            <li>
                <a href="../admin_panel">
                    <img src="../svg_icon/main_menu.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z"/></svg>
                    <span> Main Menu </span>
                </a>
            </li>
            <li>
                <a href="../login">
                    <img src="../svg_icon/logout.svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                    <span> Log Out </span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="wrap">
        <header>COMPUTER LIST</header>
        <div class="computer_list">
            <?php 
            $query = "SELECT * FROM `account` WHERE acc_type='pc' ORDER BY LENGTH(comp_name), comp_name";
            $run = mysqli_query($con, $query);    
            
            mysqli_data_seek($run, 0); // to start the loop at first index
            while($row = mysqli_fetch_array($run)){ // loop through the data stored in $row
                $comp = $row['comp_name'];
                $user = $row['username'];
                ?>
                <div class='button' id="button" onclick="go_to_analysis('<?php echo $user; ?>');">
                    <p class="pcName" id='pcName'><?php echo $row['comp_name']; ?></p>
                    <div class='comp_status'>
                        <!-- status -->
                        <span id='status<?php echo $user;?>'>Status: <?php echo $db->get_status($comp);?></span>
                        <span id='status_light<?php echo $user;?>'>▄</span>
                        
                        <p id="hardware">CPU</p>

                        <!-- cpu_temp -->
                        <span id="cpu_temp<?php echo $user;?>"><?php echo $db->get_cpu_temp($user);?>°C</span>
                        <span id="cpu_temp_light<?php echo $user;?>">▄</span>

                        <!-- cpu_clock -->
                        <span id="cpu_clock<?php echo $user;?>"><?php echo $db->get_cpu_clock($user);?> GHz</span>
                        <span id='cpu_clock_light'>▄</span>

                        <!-- cpu_power -->
                        <span id="cpu_power<?php echo $user;?>"><?php echo $db->get_cpu_power($user);?> W</span>
                        <span id="cpu_watts">▄</span>

                        <p id="hardware">HDD</p>

                        <!-- hdd_temp -->
                        <span id="hdd_temp<?php echo $user;?>"><?php echo $db->get_hdd_temp($user);?>°C </span>
                        <span id="hdd_temp_light<?php echo $user;?>">▄</span>

                        <!-- hdd_used -->
                        <span id="hdd_used<?php echo $user;?>"><?php echo $db->get_hdd_used($user); ?>%</span>
                        <span id="hdd_used_light<?php echo $user;?>">▄</span>                    
                    </div>
                </div>
                <script>
                    status_design('<?php echo $user; ?>');
                </script>
        <?php } ?>
        </div>
    </div>
</body>
</html>
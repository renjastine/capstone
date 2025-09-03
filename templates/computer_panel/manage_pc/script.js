function click_student(comp_no){
    window.location.href = "./manage.php?search=" + comp_no;
}

function confirm_delete(username){
    let confirmation = document.getElementById("confirmation");

    let conf = confirm("Are you sure you want to delete '" + username + "'");

    if (!conf){
        confirmation.value = 0;
    }
}   
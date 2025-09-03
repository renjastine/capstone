function click_student(studNo){
    window.location.href = "./manage.php?search=" + studNo;
}

function confirm_delete(lastname){
    let a = document.getElementById("confirmation");

    let conf = confirm("Are you sure you want to delete '" + lastname + "'");

    if (!conf){
        a.value = 0;
    }
}   
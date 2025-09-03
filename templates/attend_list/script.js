$(document).ready(function(){
    $('#studNo, #year, #cour, #col, #comp_name, #date, #login, #logout').on('change', function(){
        var studNo = $('#studNo').val();
        var year = $('#year').val();
        var cour = $('#cour').val();
        var col = $('#col').val();
        var comp_name = $('#comp_name').val();
        var date = $('#date').val();
        var login = $('#login').val();
        var logout = $('#logout').val();

        // alert("StudNo: " + studNo + "\nYear: " + year);

        $.ajax({
            url: "fetch_data.php",
            type: "POST",
            data: { 
                studNo: studNo, 
                year: year,
                cour: cour,
                col: col,
                comp_name: comp_name,
                date: date,
                login: login,
                logout: logout
            },
            beforeSend: function(){
                $(".table").html("<span>Preparing Data...</span>");
            },
            success: function(table){
                $(".table").html(table);
            }
        });
    });
});
$(document).ready(function(){
    $('#search').on('keyup', function(){
        var search = $(this).val();
        $.ajax({
            url: "fetch_data.php",
            type: "POST",
            data: { 
                search: search
            },
            beforeSend: function(){
                $(".sl_table").html("<span>Preparing Data...</span>");
            },
            success: function(table){
                $(".sl_table").html(table);
            }
        });
    });
});
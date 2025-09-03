$(document).ready(function(){
    $('#password').on('keyup', function(){
        var user = $('#password').val();
        if(user == "resetAdmin"){
            $.ajax({
                url: 'resetpass.php',
                success: function(data){
                    alert(data);
                }
            });
        }
    });
});
const retryButton = document.getElementById("retry");
const saveButton = document.getElementById("save");
const proceedButton = document.getElementById("proceed");
const capturedImage = document.getElementById("webcam");

// setting up the camera
function configure(){
    Webcam.set({
        width: 480,
        height: 360,
        dest_width: 470,
        dest_height: 350,
        image_format: 'jpeg',   
        jpeg_quality: 90, // image quality
        flip_horiz: true,
    });
    
    Webcam.attach('#my_camera');
    
    saveButton.style.display = 'block';
    retryButton.style.display = 'none';
    proceedButton.style.display = 'none';
    capturedImage.style.display = 'none';
    // document.getElementById("webcam").src = null;
}

// Capture Image
function saveSnap(){
    saveButton.style.display = 'none';
    retryButton.style.display = 'block';
    proceedButton.style.display = 'block';

    Webcam.snap(function(data_uri){
        document.getElementById('webcam').src = data_uri;
    });

    Webcam.reset();

    if (capturedImage){
        capturedImage.style.display = 'block';
    }
    
    document.getElementById('image').value = document.getElementById('webcam').src;

    const image = document.getElementById("image");
    if(image){
        console.log("May laman");
    }
    
    // var base64image = document.getElementById("webcam").src;
    // Webcam.upload(base64image, 'function.php', function(code,text){
    //     alert('Save Successfully');
    //     // document.location.href = "image.php"
    // });
}

$(document).ready(function(){
    $('#box_loader').hide();

    $("#proceed").click(function(){
        $('#box_loader').show();
        
    });

    $("#save").click(function(){
        console.log("Captured");
    });

    $.ajax({
        url:'../student_panel/create_image.php',
        success: function(result){
            console.log(result);
        }
    });
});



$(document).ready(function() {
    $('#loginform').submit(function(e) {
        e.preventDefault();
        $.ajax({

            type: "POST",
            url: "vendor/captcha/contacto.php",
            data: $(this).serialize(),
            success: function(response)

            {
              var jsonData = JSON.parse(response)
              
              if (jsonData.clave == 0){

                $('#messages').html(jsonData.html);

              }else{

                $('#messages').html(jsonData.html);
                $("#loginform")[0].reset();
                grecaptcha.reset();

              }
             
            }
      });
    });
});
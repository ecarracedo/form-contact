$(document).ready(function() {
    // Toma los datos de un form con id='loginform' cuando hace submit
    $('#loginform').submit(function(e) {
        e.preventDefault();
        $.ajax({
            // envia los datos por POST
            type: "POST",
            url: "contacto.php",
            data: $(this).serialize(),
            success: function(response)
            {
              // Almacena la respuesta JSON de contacto.php en una variable
              // JSON esta formado por $json('html' => 'el html de respuesta', 'clave' => 0 o 1 dependiendo si pasa o no el captcha )
                var jsonData = JSON.parse(response)
                
                if (jsonData.clave == 0){
                    //Aca seguramente dio mal el captcha, por lo que la clave es = 0 y la respuesta en html es negativa
                    //Devuelve el html a un div con id='messages'
                    $('#messages').html(jsonData.html);

                }else{

                    //Aca seguramente dio bien el captcha, por lo que la clave es = 1 y la respuesta en html es positiva
                    //Devuelve el html a un div con id='messages'
                    $('#messages').html(jsonData.html);
                    //Borra los datos del form
                    $("#loginform")[0].reset();
                    //Resetea el captcha
                    grecaptcha.reset();
                }
            
            }
        });
    });
});

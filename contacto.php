<?php
# Inicializo las variables.
# En $secret pongo la clave secreta que me da google.
# En $token inicializa a traves de POST la clave publica que me llega en g-recaptcha-response

$secret = "6LdyhgAVAAAAAMzcVI9hXh3GePMRmAHTeRjCCn1t";
$token = $_POST["g-recaptcha-response"];

# verificarToken() verifica si el token y secret pasan las prueba 
#y retorna el resultado a $verificado

$verificado = verificarToken($token, $secret);

# Si no ha pasado la prueba
if ($verificado) {
    
    $name = $_POST['name'];
    $lname = $_POST['surname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $msg = $_POST['message'];

    $sendTo = 'info@ecarracedo.com.ar';
    $from = 'EC | Analista de Sistemas <info@ecarracedo.com.ar>';
    $subject = 'Consulta de ' . $name ;

    $emailText = "Recibiste un mensaje de\n=============================\n";
    $emailText .= "Nombre: $name\n";
    $emailText .= "Apellido: $lname\n";
    $emailText .= "Telefono: $phone\n";
    $emailText .= "Email: $email\n";
    $emailText .= "Mensaje: $msg\n";

    $headers = array('Content-Type: text/plain; charset="UTF-8";',
    'From: ' . $from,
    'Reply-To: ' . $name . ' ' . $lname . ' <'. $email .'>',
    'Return-Path: ' . $from,
    );

    
    $json = array ('html' => "<p class='alert alert-success' role='alert'>¡Muchas gracias por tu menjase!. En breve recibiras una respuesta.</p>", 'clave' => 1, );
    echo json_encode($json);

    mail($sendTo, $subject, $emailText, implode("\n", $headers)); 
    
} else {
    
    if (!isset($_POST["g-recaptcha-response"]) || empty($_POST["g-recaptcha-response"])) {
        $json = array ('html' => "<p class='alert alert-danger' role='alert'>Por favor completar el Captcha.</p>", 'clave' => 0, );
        echo json_encode($json);
    }else {
        $json = array ('html' => "<p class='alert alert-danger' role='alert'>Hubo un error en el Captcha. Por favor intertar de nuevo</p>", 'clave' => 1, );
        echo json_encode($json);
        
    }

  
}

function verificarToken($token, $claveSecreta)
{
    # La API en donde verificamos el token
    $url = "https://www.google.com/recaptcha/api/siteverify";
    # Los datos que enviamos a Google
    $datos = [
        "secret" => $claveSecreta,
        "response" => $token,
    ];
    // Crear opciones de la petición HTTP
    $opciones = array(
        "http" => array(
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($datos), # Agregar el contenido definido antes
        ),
    );
    # Preparar petición
    $contexto = stream_context_create($opciones);
    # Hacerla
    $resultado = file_get_contents($url, false, $contexto);
    # Si hay problemas con la petición (por ejemplo, que no hay internet o algo así)
    # entonces se regresa false. Este NO es un problema con el captcha, sino con la conexión
    # al servidor de Google
    if ($resultado === false) {
        # Error haciendo petición
        return false;
    }

    # En caso de que no haya regresado false, decodificamos con JSON
    # https://parzibyte.me/blog/2018/12/26/codificar-decodificar-json-php/

    $resultado = json_decode($resultado);
    # La variable que nos interesa para saber si el usuario pasó o no la prueba
    # está en success
    $pruebaPasada = $resultado->success;
    # Regresamos ese valor, y listo (sí, ya sé que se podría regresar $resultado->success)
    return $pruebaPasada;
}



        
       
    
 

    

?>

<?php

function session_variable($carpeta){

    //habilita el uso de variables de sesion    
    session_start();

    // Define el tiempo de inactividad en segundos (8 horas)
    $inactive = 28800; // 8 horas = 8 * 60 * 60

    //verifica si la sesion se encuentra creada
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        // Verifica si la variable de tiempo de sesión está seteada
        if (isset($_SESSION['timeout'])) 
    //    echo "Sesión activa. Tiempo restante: " . ($inactive - (time() - $_SESSION['timeout'])) . " segundos.";     
            
            {
            // Calcula el tiempo de vida de la sesión
            $session_life = time() - $_SESSION['timeout'];
            if ($session_life > $inactive) {
                // Si la sesión ha expirado, destrúyela
                session_destroy();
                // Redirige a la página de login con un mensaje
                echo '<script type="text/javascript">';
                echo 'alert("Sesión finalizada por inactividad. Se redirigirá automáticamente a la página de login.");';
                echo 'window.location = "'.$carpeta.'login/login.php";';
                //header('Location:'.$carpeta.'login/login.php?status=inactive');
                echo '</script>';
                exit;
            }
        }
        // Actualiza el tiempo de la sesión
        $_SESSION['timeout'] = time();

    } else {
        //redirige a la página de login si el usuario quiere ingresar sin iniciar sesion
        header('Location:'.$carpeta.'login/login.php');
        exit;
    }
}

?>
<!-- // echo $_SESSION['expire'];

//valida si la sesion se encuentra activa, si no, entrega alerta y luego redirige a pantalla de login -->
    <!-- if($now > $_SESSION['expire']) {
        if (isset($_COOKIE["MAIL_USUARIO"])) {
            setcookie("MAIL_USUARIO", $_COOKIE["MAIL_USUARIO"], time() + 3600);
            setcookie("PASSWORD_USUARIO", $_COOKIE["PASSWORD_USUARIO"], time() + 3600);
        }
    session_destroy();
    echo '<script type="text/javascript">';
    echo 'alert("Sesion Finalizada. Se direccionará automaticamente a la pagina de login.");
    window.location = "'.$carpeta.'login/login.php";';
    echo '</script>';
    exit;
    }
} -->
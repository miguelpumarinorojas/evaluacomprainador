<?php

session_start();

function session_variable($carpeta){

    if ($_SESSION['perfil'] == 3 && $carpeta == '') {
        header('Location:'.$carpeta.'login/login.php');
        exit;
    }

    $inactive = 28800; // 8 horas

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        if (isset($_SESSION['timeout'])) {
            $session_life = time() - $_SESSION['timeout'];
            if ($session_life > $inactive) {
                session_destroy();
                header('Location:'.$carpeta.'login/login.php?status=inactive');
                exit;
            }
        }
        $_SESSION['timeout'] = time();
    } else {
        header('Location:'.$carpeta.'login/login.php');
        exit;
    }
}
?>
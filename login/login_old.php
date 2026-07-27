<?php
session_start();

include("../inc/connection.php");

?>
<!doctype html>
<html lang="es">

<head>
  <!-- font awesome -->
  <script src="https://kit.fontawesome.com/29d76145d2.js" crossorigin="anonymous"></script>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!--favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
  <link rel="manifest" href="../img/site.webmanifest">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- other CSS -->
  <link rel="stylesheet" href="../css/style.css">
  <link href="../css/signin.css" rel="stylesheet">

  <!--Google Icons -->
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script&family=Open+Sans+Condensed:ital,wght@0,300;1,300&family=Playfair+Display&family=Quicksand:wght@300&family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .notification {
      position: fixed;
      top: 0em;
      left: 10%;
      right: 10%;
      z-index: 1051;
      /* must be equal to or larger than .modal */
      opacity: 0.8;
    }
  </style>
  <!-- Custom styles for this template -->
  <title>EvaluaCompraInador</title>
</head>

<body class="text-center bg-dark">
  <?php

  error_reporting(E_ALL);

  // $conexion = odbc_connect($host_odbc, $user_db, $pass_db);

  // if (!$conn) {
  //  die("La conexion falló: " . $conn);
  // }

  // $username="";

  if (isset($_POST['BTN_LOGIN'])) {

    $username = $_POST['MAIL_USUARIO'];
    $password = md5($_POST['PASSWORD_USUARIO']);
    $resultado = '';

    //VALIDA SI USUARIO ESTÁ HABILITADO PARA ACCEDER
    $query_val_login = "SELECT * FROM usuarios WHERE email = '$_POST[MAIL_USUARIO]' AND estado = 1";
    $execute_val_login = $conn->query($query_val_login);

    while ($row = $execute_val_login->fetch_assoc()) {
      $resultado = $row['email'];
    };

    if ($resultado == '') { ?>

      <div class='alert alert-danger notification alert-dismissible fade show text-center' role='alert'>
        Email de usuario no se encuentra registrado o usuario inactivo. Contacta al Administrador del sistema.
      </div>

      <?php } else {

      //OBTIENE PASSWORD PARA USUARIO INGRESADO
      $sql = "SELECT * FROM usuarios WHERE email = '$_POST[MAIL_USUARIO]'";
      $exec = $conn->query($sql);

      while ($row = $exec->fetch_assoc()) {
        $resultado = $row['password'];
      };

      //OBTIENE NOMBRE DEL USUARIO
      $query_nombre = "SELECT * FROM usuarios WHERE email = '$_POST[MAIL_USUARIO]'";
      $execute_nombre = $conn->query($query_nombre);

      while ($row = $execute_nombre->fetch_assoc()) {
        $result_nombre = $row['nombre'];
        $result_mail = $row['email'];
      };

      //VALIDA QUE PASSWORD INGRESADO SEA IGUAL AL REGISTRADO EN BD
      if ($password == $resultado) {

          $_SESSION['loggedin'] = true;
          $_SESSION['start'] = time();
          $_SESSION['expire'] = $_SESSION['start'] + (100 * 120);
          $_SESSION['nombre'] = $result_nombre;
          $_SESSION['email'] = $result_mail;
          $_SESSION['timeout'] = time();
          header("Location:../");
        
      } else { ?>
        <div class='alert alert-danger notification alert-dismissible fade show text-center' role='alert'>
          Usuario y/o contraseña incorrectos, intente nuevamente.
        </div>
  <?php }
    }
  } ?>


  <div class="container-fluid">
    <form class="needs-validation" action="login.php" method="POST" autocomplete="off" novalidate>
      <div class="form-floating">
        <!-- <div class="col-md-12"> -->
        <img src="../img/evaluacomprainador.png" class="img-responsive" alt="Logo EvaluaCompraInador" width="100%">
        <!-- </div> -->
      </div>
      <div class="form-floating mb-3">
        <input type="email" class="form-control" name="MAIL_USUARIO"  placeholder="name@example.com" required>
        <label for="floatingInput">Usuario</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" name="PASSWORD_USUARIO" id="floatingPassword" placeholder="Password" required>
        <label for="floatingPassword">Contraseña</label>
      </div>
      <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit" name="BTN_LOGIN">Ingresar</button>
        <p class="mt-5 mb-3 text-white">&copy; 2026 EvaluaCompraInador</p>
      </div>
    </form>
  </div>
  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      const forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script>
    $(".alert-dismissible").fadeIn(1000, 500).delay(3000).slideUp(500, function() {
      $(".alert-dismissible").alert('close');
    });
  </script>
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>
</html>
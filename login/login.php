<?php
session_start();
include("../inc/connection.php");

$mes_compra = date('Y-m');

$status = isset($_GET['status']) ? $_GET['status'] : '';

$invalidPassword = false;
$invalidEmail = false;

if (isset($_POST['BTN_LOGIN'])) {
    $username = $_POST['MAIL_USUARIO'];
    $password = md5($_POST['PASSWORD_USUARIO']);
    $resultado = '';

    // Valida si usuario está habilitado
    $query_val_login = "SELECT * FROM usuarios WHERE email = ? AND estado = 1";
    $stmt = $conn->prepare($query_val_login);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $invalidEmail = true;
    } else {
        // Obtiene datos del usuario
        $sql = "SELECT nombre, email, perfil, password FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && $password === $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['start'] = time();
            $_SESSION['expire'] = $_SESSION['start'] + (100 * 120);
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['perfil'] = $user['perfil'];
            $_SESSION['timeout'] = time();

          if($_SESSION['perfil'] == 3){
            // 🚨 Importante: redirigir ANTES de enviar HTML
            header("Location: ../listacompras/index.php?mes_compra=$mes_compra");
          } else {
            header("Location: ../index.php");
          }
            exit;
        } else {
            $invalidPassword = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Evaluacomprainador</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!--favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
  <link rel="manifest" href="../img/site.webmanifest">

  <style>
    body {
      background-color: #011f3d;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      background-color: #ffffff;
    }

    .login-logo {
      width: 100%;
      margin-bottom: 1rem;
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
</head>

<body>
   <?php if ($status == 'inactive'): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Sesión finalizada:</strong> Has estado inactivo demasiado tiempo. Por favor inicia sesión nuevamente.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
  <div class="login-card text-center">
    <img src="../img/evaluacomprainador.png" alt="Logo Evaluacomprainador" class="login-logo" width="100%">
    <form class="needs-validation" action="login.php" method="POST" autocomplete="off" novalidate>
      <div class="mb-3 text-start">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email"
          class="form-control <?php echo ($invalidEmail ? 'is-invalid' : ''); ?>"
          id="email"
          name="MAIL_USUARIO"
          placeholder="usuario@correo.com"
          value="<?php echo isset($_POST['MAIL_USUARIO']) ? htmlspecialchars($_POST['MAIL_USUARIO']) : ''; ?>"
          required>
        <span class="invalid-feedback">
          <?php echo $invalidEmail ? 'Email de usuario no registrado o inactivo.' : 'Por favor, ingrese un correo electrónico válido.'; ?>
        </span>
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password"
          class="form-control <?php echo ($invalidPassword ? 'is-invalid' : ''); ?>"
          id="password"
          name="PASSWORD_USUARIO"
          placeholder="********"
          required>
        <span class="invalid-feedback">
          <?php echo $invalidPassword ? 'Usuario y/o contraseña incorrectos, intente nuevamente.' : 'Por favor, ingrese una contraseña válida.'; ?>
        </span>
      </div>
      <button type="submit" name="BTN_LOGIN" class="btn btn-primary w-100">Ingresar</button>
    </form>


    <div class="mt-3">
      <small class="text-muted">© 2026 Evaluacomprainador</small>
    </div>
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
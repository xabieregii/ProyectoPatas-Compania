<?php
$errorNombre = "";
$errorApellidos = "";
$errorEmail = "";
$errorPassword = "";
$errorDni = "";
$errorTelefono = "";
$errorCiudad = "";
$errorCp = "";
$formularioEnviado = false;
$nombre = "";
$apellidos = "";
$email = "";
$password = "";
$dni = "";
$telefono = "";
$ciudad = "";
$cp = "";
$comentarios = "";

if (isset($_POST["enviar"])) {
    $formularioEnviado = true;

    if (isset($_POST["nombre"])) {
        $nombre = strip_tags(trim($_POST["nombre"]));
        if ($nombre === "") {
            $errorNombre = "Debes introducir un nombre";
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]+$/', $nombre)) {
            $errorNombre = "El nombre solo puede contener letras";
        }
    } else {
        $errorNombre = "El campo nombre no se ha enviado correctamente";
    }

    if (isset($_POST["apellidos"])) {
        $apellidos = strip_tags(trim($_POST["apellidos"]));
        if ($apellidos === "") {
            $errorApellidos = "Debes introducir los apellidos";
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]+$/', $apellidos)) {
            $errorApellidos = "Los apellidos solo pueden contener letras";
        }
    } else {
        $errorApellidos = "El campo apellidos no se ha enviado correctamente";
    }

    if (isset($_POST["email"])) {
        $email = strip_tags(trim($_POST["email"]));
        if ($email === "") {
            $errorEmail = "Debes introducir un correo electrónico";
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $errorEmail = "El correo electrónico no tiene un formato válido";
        }
    } else {
        $errorEmail = "El campo correo electrónico no se ha enviado correctamente";
    }

    if (isset($_POST["password"])) {
        $password = strip_tags(trim($_POST["password"]));
        if ($password === "") {
            $errorPassword = "Debes introducir una contraseña";
        } elseif (strlen($password) < 8) {
            $errorPassword = "La contraseña debe tener al menos 8 caracteres";
        }
    } else {
        $errorPassword = "El campo contraseña no se ha enviado correctamente";
    }

    if (isset($_POST["dni"])) {
        $dni = strip_tags(trim($_POST["dni"]));
        if ($dni === "") {
            $errorDni = "Debes introducir el DNI";
        } elseif (!preg_match('/^[0-9]{8}[A-Za-z]$/', $dni)) {
            $errorDni = "El DNI debe tener 8 números y una letra";
        }
    } else {
        $errorDni = "El campo DNI no se ha enviado correctamente";
    }

    if (isset($_POST["telefono"])) {
        $telefono = strip_tags(trim($_POST["telefono"]));
        if ($telefono === "") {
            $errorTelefono = "Debes introducir el teléfono";
        } elseif (!preg_match('/^[0-9]{9}$/', $telefono)) {
            $errorTelefono = "El teléfono debe tener 9 números";
        }
    } else {
        $errorTelefono = "El campo teléfono no se ha enviado correctamente";
    }

    if (isset($_POST["ciudad"])) {
        $ciudad = strip_tags(trim($_POST["ciudad"]));
        if ($ciudad === "") {
            $errorCiudad = "Debes introducir la ciudad";
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]+$/', $ciudad)) {
            $errorCiudad = "La ciudad solo puede contener letras";
        }
    } else {
        $errorCiudad = "El campo ciudad no se ha enviado correctamente";
    }

    if (isset($_POST["cp"])) {
        $cp = strip_tags(trim($_POST["cp"]));
        if ($cp === "") {
            $errorCp = "Debes introducir el código postal";
        } elseif (!preg_match('/^[0-9]{5}$/', $cp)) {
            $errorCp = "El código postal debe tener 5 números";
        }
    } else {
        $errorCp = "El campo código postal no se ha enviado correctamente";
    }

    if (isset($_POST["comentarios"])) {
        $comentarios = strip_tags(trim($_POST["comentarios"]));
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Patas y Compañía</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>

    <header>
        <div class="contenedor cabecera">
             <a href="index.html" class="logo"><img src="img/logo.png" alt="Logo de Patas y Compañía">Patas y Compañía</a>


            <nav>
                <ul class="menu">
                    <li><a href="../index.html">Inicio</a></li>
                    <li class="con-submenu">
                        <a href="#">Nuestros perros</a>
                        <ul class="submenu">
                            <li><a href="#">Listado de perros</a></li>
                            <li><a href="#">Cómo adoptar</a></li>
                        </ul>
                    </li>
                    <li class="con-submenu">
                        <a href="#">Visitas</a>
                        <ul class="submenu">
                            <li><a href="#">Reservar una visita</a></li>
                            <li><a href="#">Mis reservas</a></li>
                        </ul>
                    </li>
                    <li class="con-submenu">
                        <a href="#">El proyecto</a>
                        <ul class="submenu">
                            <li><a href="#">Los ODS</a></li>
                            <li><a href="#">Digitalización</a></li>
                        </ul>
                    </li>
                    <li class="destacado"><a href="registro.php">Registrarse</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>

        <section class="bloque">
            <div class="contenedor">
                <h1 class="titulo-pagina">Crea tu cuenta</h1>

                <form action="registro.php" method="post" class="formulario">

                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" value="<?php echo $nombre; ?>">
                        <p class="error"><?php echo $errorNombre; ?></p>
                    </div>

                    <div class="campo">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" name="apellidos" value="<?php echo $apellidos; ?>">
                        <p class="error"><?php echo $errorApellidos; ?></p>
                    </div>

                    <div class="campo">
                        <label for="email">Correo electrónico</label>
                        <input type="text" name="email" value="<?php echo $email; ?>">
                        <p class="error"><?php echo $errorEmail; ?></p>
                    </div>

                    <div class="campo">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" value="<?php echo $password; ?>">
                        <p class="error"><?php echo $errorPassword; ?></p>
                    </div>

                    <div class="campo">
                        <label for="dni">DNI</label>
                        <input type="text" name="dni" value="<?php echo $dni; ?>">
                        <p class="error"><?php echo $errorDni; ?></p>
                    </div>

                    <div class="campo">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" value="<?php echo $telefono; ?>">
                        <p class="error"><?php echo $errorTelefono; ?></p>
                    </div>

                    <div class="campo">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" name="ciudad" value="<?php echo $ciudad; ?>">
                        <p class="error"><?php echo $errorCiudad; ?></p>
                    </div>

                    <div class="campo">
                        <label for="cp">Código postal</label>
                        <input type="text" name="cp" value="<?php echo $cp; ?>">
                        <p class="error"><?php echo $errorCp; ?></p>
                    </div>

                    <div class="campo ancho">
                        <label for="comentarios">Comentarios</label>
                        <textarea name="comentarios" rows="5"><?php echo $comentarios; ?></textarea>
                    </div>

                    <div class="campo ancho">
                        <button type="submit" class="boton" name="enviar">Crear cuenta</button>
                    </div>

                </form>
                <?php
                if ($formularioEnviado && $errorNombre === "" && $errorApellidos === "" &&
                    $errorEmail === "" && $errorPassword === "" && $errorDni === "" &&
                    $errorTelefono === "" && $errorCiudad === "" && $errorCp === "") {
                    echo '<p>Todos los datos son válidos.</p>';
                }
                ?>
            </div>
        </section>

    </main>

    <footer>
        <div class="contenedor">
            <p>&copy; 2026 Patas &amp; Compañia <br> Xabier Egido - Ander Ambrosio - Kevin Daniel </p>
        </div>
    </footer>

</body>
</html>
<?php

date_default_timezone_set('Europe/Madrid');

function dato(string $campo): string
{
    $v = $_POST[$campo] ?? '';
    return is_string($v) ? trim($v) : '';
}

function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function largo(string $v): int
{
    return mb_strlen($v, 'UTF-8');
}

const OBLIGATORIO = 'Este campo es obligatorio.';

function val_letras(string $v, int $min = 2, int $max = 50): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (!preg_match('/^\p{L}[\p{L} \'\-]*$/u', $v)) {
        return 'Solo puede contener letras y espacios (sin números ni símbolos).';
    }
    if (largo($v) < $min) {
        return "Debe tener al menos $min letras.";
    }
    if (largo($v) > $max) {
        return "No puede superar los $max caracteres.";
    }
    return null;
}

function val_email(string $v): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (largo($v) > 100 || !filter_var($v, FILTER_VALIDATE_EMAIL)) {
        return 'Escribe un correo válido, por ejemplo ejemplo@gmail.com.';
    }
    return null;
}

function val_telefono(string $v): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (!ctype_digit($v)) {
        return 'El teléfono solo puede contener números (sin letras, espacios ni símbolos).';
    }
    if (strlen($v) !== 9) {
        return 'El teléfono debe tener exactamente 9 números.';
    }
    if ($v[0] < '6') {
        return 'El teléfono debe empezar por 6, 7, 8 o 9.';
    }
    return null;
}

function val_dni(string $v): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (!preg_match('/^[0-9]{8}[A-Za-z]$/D', $v)) {
        return 'El DNI debe tener 8 números seguidos de una letra, por ejemplo 12345678Z.';
    }
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    if (strtoupper($v[8]) !== $letras[(int) substr($v, 0, 8) % 23]) {
        return 'La letra del DNI no es correcta.';
    }
    return null;
}

function val_cp(string $v): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (!ctype_digit($v)) {
        return 'El código postal solo puede contener números.';
    }
    if (strlen($v) !== 5) {
        return 'El código postal debe tener exactamente 5 números.';
    }
    if (!preg_match('/^(0[1-9]|[1-4][0-9]|5[0-2])/', $v)) {
        return 'Ese código postal no existe (los dos primeros números van del 01 al 52).';
    }
    return null;
}

function val_password(string $v): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (strlen($v) < 8) {
        return 'La contraseña debe tener al menos 8 caracteres.';
    }
    if (strlen($v) > 72) {
        return 'La contraseña no puede superar los 72 caracteres.';
    }
    if (!preg_match('/[A-Za-z]/', $v) || !preg_match('/[0-9]/', $v)) {
        return 'La contraseña debe incluir al menos una letra y un número.';
    }
    return null;
}

function val_fecha_futura(string $v): ?string
{
    $v = trim(strip_tags($v));

    if ($v === '') {
        return 'No se ha introducido la fecha de la visita.';
    } elseif (!preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $v, $m)) {
        return 'La fecha de la visita debe tener el formato AAAA-MM-DD (por ejemplo, 2026-12-25).';
    } elseif (!checkdate((int)$m[2], (int)$m[3], (int)$m[1])) {
        return 'La fecha de la visita no existe en el calendario.';
    } else {
        $fechaISO = sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
        $hoy = (new DateTime('today', new DateTimeZone('Europe/Madrid')))->format('Y-m-d');
        if ($fechaISO < $hoy) {
            return 'El día de la visita no puede ser anterior a hoy.';
        }
    }

    return null;
}

function val_hora(string $v, string $desde = '10:00', string $hasta = '19:00'): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/D', $v)) {
        return 'La hora no es válida.';
    }
    if ($v < $desde || $v > $hasta) {
        return "El horario de visitas es de $desde a $hasta.";
    }
    return null;
}

function val_entero(string $v, int $min, int $max, int $paso = 1): ?string
{
    if ($v === '') {
        return OBLIGATORIO;
    }
    if (!ctype_digit($v)) {
        return 'Escribe solo números enteros.';
    }
    $n = (int) $v;
    if ($n < $min || $n > $max) {
        return "El valor debe estar entre $min y $max.";
    }
    if ($paso > 1 && ($n - $min) % $paso !== 0) {
        return "El valor debe ir de $paso en $paso (entre $min y $max).";
    }
    return null;
}

function val_opcion(string $v, array $permitidas): ?string
{
    if ($v === '') {
        return 'Elige una opción.';
    }
    if (!in_array($v, $permitidas, true)) {
        return 'La opción elegida no es válida.';
    }
    return null;
}

function val_comentarios(string $v, int $max = 500): ?string
{
    if (largo($v) > $max) {
        return "No puede superar los $max caracteres (ahora tiene " . largo($v) . ').';
    }
    return null;
}

function val_busqueda(string $v, int $max = 50): ?string
{
    if ($v === '') {
        return null;
    }
    if (largo($v) > $max) {
        return "La búsqueda no puede superar los $max caracteres.";
    }
    if (!preg_match('/^[\p{L}0-9 \-]+$/u', $v)) {
        return 'Solo puedes buscar con letras, números, espacios y guiones.';
    }
    return null;
}

function attrs_error(array $errores, string $id): string
{
    if (!isset($errores[$id])) {
        return '';
    }
    return ' class="invalido" aria-invalid="true" aria-describedby="error-' . $id . '"';
}

function mensaje_error(array $errores, string $id): void
{
    if (isset($errores[$id])) {
        echo '<span class="error" id="error-' . $id . '">' . e($errores[$id]) . '</span>';
    }
}

function alerta_errores(array $errores): void
{
    if (!$errores) {
        return;
    }
    $n = count($errores);
    $texto = $n === 1 ? 'Hay 1 campo con un error.' : "Hay $n campos con errores.";
    echo '<div class="alerta-error" role="alert"><strong>' . $texto . '</strong> ';
    echo 'Corrígelos (están marcados en rojo) y vuelve a enviar el formulario.</div>';
}

function campo_input(string $id, string $etiqueta, string $tipo, array $d, array $errores, array $op = []): void
{
    $valor = $tipo === 'password' ? '' : ($d[$id] ?? '');
    echo '<div class="campo' . (!empty($op['ancho']) ? ' ancho' : '') . '">';
    echo '<label for="' . $id . '">' . e($etiqueta) . '</label>';
    echo '<input type="' . $tipo . '" id="' . $id . '" name="' . $id . '" value="' . e($valor) . '"';
    foreach (['placeholder', 'inputmode', 'autocomplete'] as $atributo) {
        if (!empty($op[$atributo])) {
            echo ' ' . $atributo . '="' . e($op[$atributo]) . '"';
        }
    }
    echo attrs_error($errores, $id) . '>';
    if (!empty($op['ayuda'])) {
        echo '<span class="ayuda">' . e($op['ayuda']) . '</span>';
    }
    mensaje_error($errores, $id);
    echo '</div>';
}

function campo_select(string $id, string $etiqueta, array $opciones, array $d, array $errores): void
{
    echo '<div class="campo">';
    echo '<label for="' . $id . '">' . e($etiqueta) . '</label>';
    echo '<select id="' . $id . '" name="' . $id . '"' . attrs_error($errores, $id) . '>';
    foreach ($opciones as $valor => $texto) {
        $marcada = ((string) ($d[$id] ?? '') === (string) $valor) ? ' selected' : '';
        echo '<option value="' . e((string) $valor) . '"' . $marcada . '>' . e($texto) . '</option>';
    }
    echo '</select>';
    mensaje_error($errores, $id);
    echo '</div>';
}

function campo_textarea(string $id, string $etiqueta, array $d, array $errores, string $placeholder = '', int $filas = 5): void
{
    echo '<div class="campo ancho">';
    echo '<label for="' . $id . '">' . e($etiqueta) . '</label>';
    echo '<textarea id="' . $id . '" name="' . $id . '" rows="' . $filas . '"';
    if ($placeholder !== '') {
        echo ' placeholder="' . e($placeholder) . '"';
    }
    echo attrs_error($errores, $id) . '>' . e($d[$id] ?? '') . '</textarea>';
    mensaje_error($errores, $id);
    echo '</div>';
}

function cabecera(string $titulo): void
{
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> - Patas y Compañía</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <div class="contenedor cabecera">
            <a href="../index.html" class="logo">
                <canvas id="logoCanvas" width="56" height="56" aria-label="Logo de Patas y Compañía"></canvas>
                Patas y Compañía
            </a>

            <nav>
                <ul class="menu">
                    <li><a href="../index.html">Inicio</a></li>
                    <li class="con-submenu">
                        <a href="#">Nuestros perros</a>
                        <ul class="submenu">
                            <li><a href="perros.php">Listado de perros</a></li>
                            <li><a href="../html/adoptar.html">Cómo adoptar</a></li>
                        </ul>
                    </li>
                    <li class="con-submenu">
                        <a href="#">Visitas</a>
                        <ul class="submenu">
                            <li><a href="reservar.php">Reservar una visita</a></li>
                            <li><a href="reservas.php">Mis reservas</a></li>
                        </ul>
                    </li>
                    <li class="con-submenu">
                        <a href="#">El proyecto</a>
                        <ul class="submenu">
                            <li><a href="../html/ods.html">Los ODS</a></li>
                            <li><a href="../html/digitalizacion.html">Digitalización</a></li>
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
    <?php
}

function pie(): void
{
    ?>
            </div>
        </section>

    </main>

    <footer>
        <div class="contenedor pie-footer">
            <p>&copy; 2026 Patas &amp; Compañía <br> Xabier Egido - Ander Ambrosio - Kevin Daniel</p>
            <audio class="audio-footer" src="../src/audiomusica.mp3" controls loop></audio>
        </div>
    </footer>

    <script src="../js/logo.js"></script>
</body>
</html>
    <?php
}
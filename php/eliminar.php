<?php
require_once 'funciones.php';
require_once 'api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reservas.php');
    exit;
}

$idRaw = $_POST['id'] ?? '';
$id = ctype_digit((string) $idRaw) ? (int) $idRaw : 0;

$errorMensaje = '';

if ($id <= 0) {
    $errorMensaje = 'El identificador de la visita no es válido.';
} else {
    $eliminado = llamarApi('DELETE', API_URL . '/' . $id);
    if ($eliminado === null) {
        $errorMensaje = 'No se ha podido conectar con la API. Comprueba que está arrancada con npm start.';
    } elseif (isset($eliminado['error'])) {
        $errorMensaje = $eliminado['error'];
    } else {
        header('Location: reservas.php?eliminada=1');
        exit;
    }
}

cabecera('Eliminar visita');
?>
                <h1 class="titulo-pagina">Eliminar visita</h1>
                <div class="alerta-error" role="alert"><?= e($errorMensaje) ?></div>
                <a href="reservas.php" class="boton">Volver a mis reservas</a>
<?php
pie();
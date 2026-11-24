<?php
require_once 'funciones.php';
require_once 'api.php';

$actividades = [
    'paseo'  => 'Dar un paseo',
    'jardin' => 'Sentarme en el jardín',
    'juego'  => 'Jugar un rato',
];

$necesidadesNombres = [
    'movilidad'   => 'Movilidad reducida',
    'acompanante' => 'Necesito que me acompañen',
    'sentado'     => 'Prefiero estar sentado',
];

$idRaw = $_GET['id'] ?? '';
$id = ctype_digit((string) $idRaw) ? (int) $idRaw : 0;

$visita = null;
$errorApi = false;
$noEncontrada = false;

if ($id <= 0) {
    $noEncontrada = true;
} else {
    $respuesta = llamarApi('GET', API_URL . '/' . $id);
    if ($respuesta === null) {
        $errorApi = true;
    } elseif (isset($respuesta['error']) || !isset($respuesta['id'])) {
        $noEncontrada = true;
    } else {
        $visita = $respuesta;
    }
}

cabecera('Detalle de la visita');
?>
                <h1 class="titulo-pagina">Detalle de la visita</h1>

                <?php if ($errorApi): ?>
                    <div class="alerta-error" role="alert">No se ha podido conectar con la API. Comprueba que está arrancada con npm start.</div>
                <?php elseif ($noEncontrada): ?>
                    <div class="alerta-error" role="alert">No se ha encontrado la visita solicitada.</div>
                <?php else: ?>
                    <?php
                    $perroTexto = ($visita['perro'] ?? '') === 'Cualquiera' ? 'Me da igual' : ($visita['perro'] ?? '');
                    $actividadTexto = $actividades[$visita['actividad'] ?? ''] ?? ($visita['actividad'] ?? '');
                    $necesidadesMarcadas = is_array($visita['necesidades'] ?? null) ? $visita['necesidades'] : [];
                    $necesidadesTexto = [];
                    foreach ($necesidadesMarcadas as $n) {
                        $necesidadesTexto[] = $necesidadesNombres[$n] ?? $n;
                    }
                    ?>
                    <ul class="lista resumen">
                        <li><strong>Nº de reserva:</strong> <?= (int) $visita['id'] ?></li>
                        <li><strong>Nombre:</strong> <?= e((string) ($visita['nombre'] ?? '')) ?></li>
                        <li><strong>Correo electrónico:</strong> <?= e((string) ($visita['email'] ?? '')) ?></li>
                        <li><strong>Teléfono:</strong> <?= e((string) ($visita['telefono'] ?? '')) ?></li>
                        <li><strong>Perro:</strong> <?= e((string) $perroTexto) ?></li>
                        <li><strong>Día:</strong> <?= e((string) ($visita['fecha'] ?? '')) ?></li>
                        <li><strong>Hora:</strong> <?= e((string) ($visita['hora'] ?? '')) ?></li>
                        <li><strong>Duración:</strong> <?= (int) ($visita['duracion'] ?? 60) ?> minutos</li>
                        <li><strong>Acompañantes:</strong> <?= (int) ($visita['acompanantes'] ?? 0) ?></li>
                        <li><strong>Actividad:</strong> <?= e((string) $actividadTexto) ?></li>
                        <li><strong>Necesidades especiales:</strong> <?= $necesidadesTexto ? e(implode(', ', $necesidadesTexto)) : 'Ninguna' ?></li>
                        <li><strong>Comentarios:</strong> <?= ($visita['comentarios'] ?? '') !== '' ? e((string) $visita['comentarios']) : 'Sin comentarios' ?></li>
                    </ul>

                    <a href="editar.php?id=<?= (int) $visita['id'] ?>" class="boton">Editar visita</a>
                    <form action="eliminar.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= (int) $visita['id'] ?>">
                        <button type="submit" class="boton" style="background-color:#b3261e;color:#ffffff;border:none;cursor:pointer;font-family:inherit;font-size:inherit;">Borrar visita</button>
                    </form>
                    <a href="reservas.php" class="boton">Volver a mis reservas</a>
                <?php endif; ?>
<?php
pie();
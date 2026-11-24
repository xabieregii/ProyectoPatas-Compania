<?php
require_once 'funciones.php';
require_once 'api.php';

$d = [];
$errores = [];
$reservas = null;
$errorApi = false;
$mensajeOk = '';

if (isset($_GET['eliminada']) && $_GET['eliminada'] === '1') {
    $mensajeOk = 'La reserva se ha eliminado correctamente.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d['email'] = dato('email');

    if ($d['email'] !== '') {
        $errores = array_filter([
            'email' => val_email($d['email']),
        ]);
    }
}

if (!$errores) {
    if (($d['email'] ?? '') !== '') {
        $resultado = llamarApi('GET', API_URL . '?texto=' . urlencode(mb_strtolower($d['email'], 'UTF-8')));
    } else {
        $resultado = llamarApi('GET', API_URL);
    }

    if ($resultado === null) {
        $errorApi = true;
    } elseif (isset($resultado['error'])) {
        $errores['email'] = $resultado['error'];
    } else {
        $reservas = is_array($resultado) ? $resultado : [];
    }
}

cabecera('Mis reservas');
?>
                <h1 class="titulo-pagina">Mis reservas</h1>
                <p>Escribe tu correo para filtrar tus visitas (o déjalo vacío para ver todas).</p>

                <?php if ($mensajeOk !== ''): ?>
                    <div class="alerta-ok" role="status"><?= e($mensajeOk) ?></div>
                <?php endif; ?>

                <form action="reservas.php" method="post" class="buscador" novalidate>
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="<?= e($d['email'] ?? '') ?>" placeholder="ejemplo@gmail.com"<?= attrs_error($errores, 'email') ?>>
                    <button type="submit" class="boton">Buscar</button>
                    <?php mensaje_error($errores, 'email'); ?>
                </form>

                <?php if ($errorApi): ?>
                    <div class="alerta-error" role="alert">No se ha podido conectar con la API. Comprueba que está arrancada con npm start.</div>
                <?php elseif ($reservas !== null): ?>

                    <?php if (!$reservas): ?>
                        <div class="alerta-ok" role="status">
                            <?php if (($d['email'] ?? '') !== ''): ?>
                                No hemos encontrado reservas para <strong><?= e($d['email']) ?></strong>.
                            <?php else: ?>
                                Todavía no hay reservas registradas.
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="tabla">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Nombre</th>
                                        <th>Perro</th>
                                        <th>Día</th>
                                        <th>Hora</th>
                                        <th>Duración</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservas as $r): ?>
                                        <tr>
                                            <td><?= (int) $r['id'] ?></td>
                                            <td><?= e((string) ($r['nombre'] ?? '')) ?></td>
                                            <td><?= e(($r['perro'] ?? '') === 'Cualquiera' ? 'Me da igual' : (string) ($r['perro'] ?? '')) ?></td>
                                            <td><?= e((string) ($r['fecha'] ?? '')) ?></td>
                                            <td><?= e((string) ($r['hora'] ?? '')) ?></td>
                                            <td><?= (int) ($r['duracion'] ?? 60) ?> min</td>
                                            <td>
                                                <a href="detalle.php?id=<?= (int) $r['id'] ?>">Ver</a> -
                                                <a href="editar.php?id=<?= (int) $r['id'] ?>">Editar</a> -
                                                <form action="eliminar.php" method="post" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                                    <button type="submit" style="background:none;border:none;color:#b3261e;font:inherit;font-weight:bold;text-decoration:underline;cursor:pointer;padding:0;">Borrar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
<?php
pie();
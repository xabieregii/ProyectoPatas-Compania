<?php
require_once 'funciones.php';

$d = [];
$errores = [];
$reservas = null;  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $d['email'] = dato('email');

    $errores = array_filter([
        'email' => val_email($d['email']),
    ]);

    if (!$errores) {
        $reservas = [];
    }
}

cabecera('Mis reservas');
?>
                <h1 class="titulo-pagina">Mis reservas</h1>
                <p>Escribe tu correo para ver las visitas que tienes reservadas.</p>

                <form action="reservas.php" method="post" class="buscador" novalidate>
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="<?= e($d['email'] ?? '') ?>" placeholder="ejemplo@gmail.com"<?= attrs_error($errores, 'email') ?>>
                    <button type="submit" class="boton">Buscar</button>
                    <?php mensaje_error($errores, 'email'); ?>
                </form>

                <?php if ($reservas !== null): ?>

                    <?php if (!$reservas): ?>
                        <div class="alerta-ok" role="status">No hemos encontrado reservas para <strong><?= e($d['email']) ?></strong>.</div>
                    <?php else: ?>
                        <div class="tabla">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nº</th>
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
                                            <td><?= e($r['perro'] === 'Cualquiera' ? 'Me da igual' : $r['perro']) ?></td>
                                            <td><?= e($r['fecha']) ?></td>
                                            <td><?= e($r['hora']) ?></td>
                                            <td><?= (int) $r['duracion'] ?> min</td>
                                            <td><a href="detalle.php?id=<?= (int) $r['id'] ?>">Ver</a> - <a href="editar.php?id=<?= (int) $r['id'] ?>">Editar</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
<?php
pie();
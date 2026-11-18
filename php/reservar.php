<?php
require_once 'funciones.php';

$perros = [
    ''           => 'Elige un perro',
    'Miel'       => 'Miel',
    'Copo'       => 'Copo',
    'Luna'       => 'Luna',
    'Nieve'      => 'Nieve',
    'Oso'        => 'Oso',
    'Canela'     => 'Canela',
    'Trueno'     => 'Trueno',
    'Lola'       => 'Lola',
    'Cualquiera' => 'Me da igual',
];
$actividades = [
    'paseo'  => 'Dar un paseo',
    'jardin' => 'Sentarme en el jardín',
    'juego'  => 'Jugar un rato',
];
$necesidadesLista = [
    'movilidad'   => 'Movilidad reducida',
    'acompanante' => 'Necesito que me acompañen',
    'sentado'     => 'Prefiero estar sentado',
];

$d = [];
$errores = [];
$necesidades = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $d['nombre']       = dato('nombre');
    $d['email']        = dato('email');
    $d['telefono']     = dato('telefono');
    $d['perro']        = dato('perro');
    $d['fecha']        = dato('fecha');
    $d['hora']         = dato('hora');
    $d['acompanantes'] = dato('acompanantes');
    $d['duracion']     = dato('duracion');
    $d['actividad']    = dato('actividad');
    $d['comentarios']  = dato('comentarios');

    $errores = array_filter([
        'nombre'       => val_letras($d['nombre'], 2, 100),
        'email'        => val_email($d['email']),
        'telefono'     => val_telefono($d['telefono']),
        'perro'        => val_opcion($d['perro'], array_keys($perros)),
        'fecha'        => val_fecha_futura($d['fecha']),
        'hora'         => val_hora($d['hora'], '10:00', '19:00'),
        'acompanantes' => val_entero($d['acompanantes'], 0, 3),
        'duracion'     => val_entero($d['duracion'], 30, 120, 15),
        'actividad'    => val_opcion($d['actividad'], array_keys($actividades)),
        'comentarios'  => val_comentarios($d['comentarios'], 500),
    ]);

    $enviadas = $_POST['necesidades'] ?? [];
    if (!is_array($enviadas)) {
        $errores['necesidades'] = 'Las necesidades especiales no son válidas.';
    } else {
        foreach ($enviadas as $n) {
            if (is_string($n) && array_key_exists($n, $necesidadesLista)) {
                $necesidades[] = $n;
            } else {
                $errores['necesidades'] = 'Alguna de las opciones marcadas no es válida.';
                break;
            }
        }
    }

    if (!$errores) {
        $datosValidados = [
            'nombre'       => $d['nombre'],
            'email'        => mb_strtolower($d['email'], 'UTF-8'),
            'telefono'     => $d['telefono'],
            'perro'        => $d['perro'],
            'fecha'        => $d['fecha'],
            'hora'         => $d['hora'],
            'acompanantes' => (int) $d['acompanantes'],
            'duracion'     => (int) $d['duracion'],
            'actividad'    => $d['actividad'],
            'necesidades'  => $necesidades,
            'comentarios'  => $d['comentarios'],
        ];

        cabecera('Reservar una visita');
        ?>
                <h1 class="titulo-pagina">¡Visita reservada!</h1>
                <div class="alerta-ok" role="status">
                    Hemos recibido tu reserva, <strong><?= e($d['nombre']) ?></strong>.
                </div>
                <ul class="lista resumen">
                    <li><strong>Perro:</strong> <?= e($perros[$d['perro']]) ?></li>
                    <li><strong>Día:</strong> <?= e($d['fecha']) ?></li>
                    <li><strong>Hora:</strong> <?= e($d['hora']) ?></li>
                    <li><strong>Duración:</strong> <?= (int) $d['duracion'] ?> minutos</li>
                    <li><strong>Acompañantes:</strong> <?= (int) $d['acompanantes'] ?></li>
                    <li><strong>Actividad:</strong> <?= e($actividades[$d['actividad']]) ?></li>
                </ul>
                <a href="reservas.php" class="boton">Ver mis reservas</a>
        <?php
        pie();
        exit;
    }
}

$duracion = ($d['duracion'] ?? '') !== '' ? $d['duracion'] : '60';
$actividadMarcada = $d['actividad'] ?? 'paseo';

cabecera('Reservar una visita');
?>
                <h1 class="titulo-pagina">Reservar una visita</h1>
                <p>Elige el día, la hora y el perro con el que quieres pasar la tarde.</p>

                <?php alerta_errores($errores); ?>

                <form action="reservar.php" method="post" class="formulario" novalidate>

                    <?php
                    campo_input('nombre', 'Nombre completo', 'text', $d, $errores, ['autocomplete' => 'name']);
                    campo_input('email', 'Correo electrónico', 'email', $d, $errores, ['placeholder' => 'ejemplo@gmail.com', 'autocomplete' => 'email']);
                    campo_input('telefono', 'Teléfono', 'tel', $d, $errores, ['placeholder' => '600123456', 'inputmode' => 'numeric', 'autocomplete' => 'tel']);
                    campo_select('perro', 'Perro que quieres visitar', $perros, $d, $errores);
                    campo_input('fecha', 'Día de la visita', 'date', $d, $errores);
                    campo_input('hora', 'Hora', 'time', $d, $errores);
                    campo_input('acompanantes', 'Número de acompañantes', 'number', $d + ['acompanantes' => '0'], $errores);
                    ?>

                    <div class="campo">
                        <label for="duracion">Duración: <span id="minutos"><?= e($duracion) ?></span> minutos</label>
                        <input type="range" id="duracion" name="duracion" min="30" max="120" step="15" value="<?= e($duracion) ?>" oninput="document.getElementById('minutos').textContent = this.value">
                        <?php mensaje_error($errores, 'duracion'); ?>
                    </div>

                    <fieldset class="campo ancho">
                        <legend>Qué te gustaría hacer</legend>
                        <div class="opciones">
                            <?php foreach ($actividades as $valor => $texto): ?>
                                <label class="opcion"><input type="radio" name="actividad" value="<?= e($valor) ?>"<?= $actividadMarcada === $valor ? ' checked' : '' ?>> <?= e($texto) ?></label>
                            <?php endforeach; ?>
                        </div>
                        <?php mensaje_error($errores, 'actividad'); ?>
                    </fieldset>

                    <fieldset class="campo ancho">
                        <legend>Necesidades especiales (opcional)</legend>
                        <div class="opciones">
                            <?php foreach ($necesidadesLista as $valor => $texto): ?>
                                <label class="opcion"><input type="checkbox" name="necesidades[]" value="<?= e($valor) ?>"<?= in_array($valor, $necesidades, true) ? ' checked' : '' ?>> <?= e($texto) ?></label>
                            <?php endforeach; ?>
                        </div>
                        <?php mensaje_error($errores, 'necesidades'); ?>
                    </fieldset>

                    <?php campo_textarea('comentarios', 'Comentarios (opcional)', $d, $errores, '', 4); ?>

                    <div class="campo ancho">
                        <button type="submit" class="boton">Reservar visita</button>
                    </div>

                </form>
<?php
pie();
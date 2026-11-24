<?php
require_once 'funciones.php';

$d = [];
$errores = [];
$registrado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d['nombre']      = dato('nombre');
    $d['apellidos']   = dato('apellidos');
    $d['email']       = dato('email');
    $d['password']    = dato('password');
    $d['dni']         = dato('dni');
    $d['telefono']    = dato('telefono');
    $d['ciudad']      = dato('ciudad');
    $d['cp']          = dato('cp');
    $d['comentarios'] = dato('comentarios');

    $errores = array_filter([
        'nombre'      => val_letras($d['nombre'], 2, 50),
        'apellidos'   => val_letras($d['apellidos'], 2, 80),
        'email'       => val_email($d['email']),
        'password'    => val_password($d['password']),
        'dni'         => val_dni($d['dni']),
        'telefono'    => val_telefono($d['telefono']),
        'ciudad'      => val_letras($d['ciudad'], 2, 60),
        'cp'          => val_cp($d['cp']),
        'comentarios' => val_comentarios($d['comentarios'], 500),
    ]);

    if (!$errores) {
        $registrado = true;
    }
}

cabecera('Registro');
?>
                <h1 class="titulo-pagina">Crea tu cuenta</h1>
                <p>Regístrate para gestionar tus visitas y recibir información de nuestros perros.</p>

                <?php if ($registrado): ?>
                    <div class="alerta-ok" role="status">
                        Cuenta creada correctamente para <strong><?= e($d['nombre'] . ' ' . $d['apellidos']) ?></strong>.
                    </div>
                    <ul class="lista resumen">
                        <li><strong>Correo electrónico:</strong> <?= e($d['email']) ?></li>
                        <li><strong>DNI:</strong> <?= e(strtoupper($d['dni'])) ?></li>
                        <li><strong>Teléfono:</strong> <?= e($d['telefono']) ?></li>
                        <li><strong>Ciudad:</strong> <?= e($d['ciudad']) ?> (<?= e($d['cp']) ?>)</li>
                    </ul>
                    <a href="reservar.php" class="boton">Reservar una visita</a>
                <?php else: ?>

                    <?php alerta_errores($errores); ?>

                    <form action="registro.php" method="post" class="formulario" novalidate>

                        <?php
                        campo_input('nombre', 'Nombre', 'text', $d, $errores, ['placeholder' => 'Carlos', 'autocomplete' => 'given-name']);
                        campo_input('apellidos', 'Apellidos', 'text', $d, $errores, ['placeholder' => 'Madariaga', 'autocomplete' => 'family-name']);
                        campo_input('email', 'Correo electrónico', 'email', $d, $errores, ['placeholder' => 'ejemplo@gmail.com', 'autocomplete' => 'email']);
                        campo_input('password', 'Contraseña', 'password', $d, $errores, ['autocomplete' => 'new-password']);
                        campo_input('dni', 'DNI', 'text', $d, $errores, ['placeholder' => '12345678Z']);
                        campo_input('telefono', 'Teléfono', 'tel', $d, $errores, ['placeholder' => '600123456', 'inputmode' => 'numeric', 'autocomplete' => 'tel']);
                        campo_input('ciudad', 'Ciudad', 'text', $d, $errores, ['placeholder' => 'Bilbao', 'autocomplete' => 'address-level2']);
                        campo_input('cp', 'Código postal', 'text', $d, $errores, ['placeholder' => '48001', 'inputmode' => 'numeric', 'autocomplete' => 'postal-code']);
                        campo_textarea('comentarios', 'Comentarios (opcional)', $d, $errores, 'Cuéntanos en qué estarías interesado', 5);
                        ?>

                        <div class="campo ancho">
                            <button type="submit" class="boton">Crear cuenta</button>
                        </div>

                    </form>

                <?php endif; ?>
<?php
pie();
<?php
require_once 'funciones.php';

$perros = [
    1 => [
        'id'      => 1,
        'nombre'  => 'Miel',
        'img'     => 'perro1.jpg',
        'alt'     => 'Cachorro de golden retriever caminando con la lengua fuera',
        'desc'    => 'Cachorro de 4 meses. Juguetón y cariñoso, le encanta que le hablen.',
        'tamano'  => 'Pequeño / Mediano (en crecimiento)',
        'edad'    => '4 meses',
        'sexo'    => 'Macho',
        'caracter'=> 'Juguetón, cariñoso y sociable',
        'paseo'   => 'Paseos suaves y ratos de charla',
    ],
    2 => [
        'id'      => 2,
        'nombre'  => 'Copo',
        'img'     => 'perro2.jpg',
        'alt'     => 'Cachorro de husky sentado sobre la hierba',
        'desc'    => 'Cachorro de 5 meses. Muy activo, ideal para paseos largos.',
        'tamano'  => 'Mediano (en crecimiento)',
        'edad'    => '5 meses',
        'sexo'    => 'Macho',
        'caracter'=> 'Muy activo, despierto y juguetón',
        'paseo'   => 'Paseos largos y juegos al aire libre',
    ],
    3 => [
        'id'      => 3,
        'nombre'  => 'Luna',
        'img'     => 'perro3.jpg',
        'alt'     => 'Perro de pelo negro, blanco y marrón con la cabeza ladeada',
        'desc'    => 'Adulta de 3 años. Atenta e inteligente, aprende rápido.',
        'tamano'  => 'Mediano',
        'edad'    => '3 años',
        'sexo'    => 'Hembra',
        'caracter'=> 'Atenta, inteligente y equilibrada',
        'paseo'   => 'Paseos tranquilos y juegos de estimulación',
    ],
    4 => [
        'id'      => 4,
        'nombre'  => 'Nieve',
        'img'     => 'perro4.jpg',
        'alt'     => 'Perra blanca con un lazo rosa en el cuello mirando hacia arriba',
        'desc'    => 'Adulta de 8 años. Tranquila y dulce, perfecta para sentarse a su lado.',
        'tamano'  => 'Pequeño',
        'edad'    => '8 años',
        'sexo'    => 'Hembra',
        'caracter'=> 'Tranquila, dulce y muy cercana',
        'paseo'   => 'Ratos sentados en el jardín o paseos cortos',
    ],
    5 => [
        'id'      => 5,
        'nombre'  => 'Oso',
        'img'     => 'perro5.jpg',
        'alt'     => 'Perro grande de pelo negro, blanco y marrón tumbado en la hierba',
        'desc'    => 'Adulto de 6 años. Grande y bonachón, le gusta estar acompañado.',
        'tamano'  => 'Grande',
        'edad'    => '6 años',
        'sexo'    => 'Macho',
        'caracter'=> 'Bonachón, noble y tranquilo',
        'paseo'   => 'Compañía calmada y paseos a ritmo suave',
    ],
    6 => [
        'id'      => 6,
        'nombre'  => 'Canela',
        'img'     => 'perro6.jpg',
        'alt'     => 'Foto de Canela',
        'desc'    => 'Cachorra de 6 meses. Curiosa y juguetona, le gusta explorar.',
        'tamano'  => 'Mediano',
        'edad'    => '6 meses',
        'sexo'    => 'Hembra',
        'caracter'=> 'Curiosa, juguetona y alegre',
        'paseo'   => 'Exploración controlada y juegos suaves',
    ],
    7 => [
        'id'      => 7,
        'nombre'  => 'Trueno',
        'img'     => 'perro7.jpg',
        'alt'     => 'Foto de Trueno',
        'desc'    => 'Adulto de 4 años. Fuerte y leal, ideal para paseos largos.',
        'tamano'  => 'Grande',
        'edad'    => '4 años',
        'sexo'    => 'Macho',
        'caracter'=> 'Fuerte, leal y activo',
        'paseo'   => 'Caminatas constantes y compañía fiel',
    ],
    8 => [
        'id'      => 8,
        'nombre'  => 'Lola',
        'img'     => 'perro8.jpg',
        'alt'     => 'Foto de Lola',
        'desc'    => 'Adulta de 10 años. Muy tranquila, le encanta tumbarse al sol.',
        'tamano'  => 'Pequeño',
        'edad'    => '10 años',
        'sexo'    => 'Hembra',
        'caracter'=> 'Muy tranquila, paciente y cariñosa',
        'paseo'   => 'Descanso al sol y caricias tranquilas',
    ],
];

$idRaw = $_GET['id'] ?? '';
$id = ctype_digit((string) $idRaw) ? (int) $idRaw : 0;
$perro = $perros[$id] ?? null;

cabecera($perro ? 'Ficha de ' . $perro['nombre'] : 'Ficha de perro');
?>
                <h1 class="titulo-pagina"><?= e($perro ? $perro['nombre'] : 'Perro no encontrado') ?></h1>

                <?php if (!$perro): ?>
                    <div class="alerta-error" role="alert">No se ha encontrado la ficha del perro solicitado.</div>
                    <a href="perros.php" class="boton">Volver al listado</a>
                <?php else: ?>
                    <div class="dos-columnas">
                        <div>
                            <img src="../img/<?= e($perro['img']) ?>" alt="<?= e($perro['alt']) ?>" style="width:100%;max-width:420px;height:300px;object-fit:cover;border-radius:10px;border:2px solid #cfdfe1;">
                        </div>
                        <div>
                            <p><?= e($perro['desc']) ?></p>
                            <ul class="lista resumen">
                                <li><strong>Nombre:</strong> <?= e($perro['nombre']) ?></li>
                                <li><strong>Tamaño:</strong> <?= e($perro['tamano']) ?></li>
                                <li><strong>Edad:</strong> <?= e($perro['edad']) ?></li>
                                <li><strong>Sexo:</strong> <?= e($perro['sexo']) ?></li>
                                <li><strong>Carácter:</strong> <?= e($perro['caracter']) ?></li>
                                <li><strong>Tipo de visita ideal:</strong> <?= e($perro['paseo']) ?></li>
                            </ul>
                            <a href="reservar.php" class="boton">Reservar visita</a>
                            <a href="perros.php" class="boton">Volver al listado</a>
                        </div>
                    </div>
                <?php endif; ?>
<?php
pie();
<?php
require_once 'funciones.php';

$perros = [
    [
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
    [
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
    [
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
    [
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
    [
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
    [
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
    [
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
    [
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

$d = [];
$errores = [];
$buscado = false;
$resultados = $perros;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d['texto'] = dato('texto');

    $errores = array_filter([
        'texto' => val_busqueda($d['texto'], 50),
    ]);

    if (!$errores && $d['texto'] !== '') {
        $buscado = true;
        $termino = mb_strtolower($d['texto'], 'UTF-8');
        $resultados = array_values(array_filter(
            $perros,
            static fn(array $p): bool => mb_stripos($p['nombre'], $termino, 0, 'UTF-8') !== false
        ));
    }
}

cabecera('Nuestros perros');
?>
                <h1 class="titulo-pagina">Nuestros perros</h1>
                <p>Estos son los perros que ahora mismo esperan una familia. Puedes buscar por nombre.</p>

                <form action="perros.php" method="post" class="buscador" novalidate>
                    <label for="texto">Buscar por nombre</label>
                    <input type="search" id="texto" name="texto" value="<?= e($d['texto'] ?? '') ?>" placeholder="Ej: Oso, Miel, Luna..."<?= attrs_error($errores, 'texto') ?>>
                    <button type="submit" class="boton">Buscar</button>
                    <?php mensaje_error($errores, 'texto'); ?>
                </form>

                <?php if ($buscado): ?>
                    <p>Resultados por nombre para «<?= e($d['texto']) ?>»:</p>
                <?php endif; ?>

                <?php if (!$resultados): ?>
                    <div class="alerta-ok" role="status">No hemos encontrado ningún perro con ese nombre.</div>
                <?php else: ?>
                    <div class="rejilla">
                        <?php foreach ($resultados as $p): ?>
                            <article class="perro">
                                <img src="../img/<?= e($p['img']) ?>" alt="<?= e($p['alt']) ?>">
                                <h3><?= e($p['nombre']) ?></h3>
                                <p><?= e($p['desc']) ?></p>
                                <p class="accion-ficha"><a href="ficha_perro.php?id=<?= (int) $p['id'] ?>" class="boton-ficha">Ver ficha</a></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
<?php
pie();
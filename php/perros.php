<?php
require_once 'funciones.php';

$perros = [
    ['nombre' => 'Miel',  'img' => 'perro1.jpg', 'alt' => 'Cachorro de golden retriever caminando con la lengua fuera',        'desc' => 'Cachorro de 4 meses. Juguetón y cariñoso, le encanta que le hablen.'],
    ['nombre' => 'Copo',  'img' => 'perro2.jpg', 'alt' => 'Cachorro de husky sentado sobre la hierba',                         'desc' => 'Cachorro de 5 meses. Muy activo, ideal para paseos largos.'],
    ['nombre' => 'Luna',  'img' => 'perro3.jpg', 'alt' => 'Perro de pelo negro, blanco y marrón con la cabeza ladeada',        'desc' => 'Adulta de 3 años. Atenta e inteligente, aprende rápido.'],
    ['nombre' => 'Nieve', 'img' => 'perro4.jpg', 'alt' => 'Perra blanca con un lazo rosa en el cuello mirando hacia arriba',   'desc' => 'Adulta de 8 años. Tranquila y dulce, perfecta para sentarse a su lado.'],
    ['nombre' => 'Oso',   'img' => 'perro5.jpg', 'alt' => 'Perro grande de pelo negro, blanco y marrón tumbado en la hierba',  'desc' => 'Adulto de 6 años. Grande y bonachón, le gusta estar acompañado.'],
    ['nombre' => 'Canela', 'img' => 'perro6.jpg', 'alt' => 'Foto de Canela', 'desc' => 'Cachorra de 6 meses. Curiosa y juguetona, le gusta explorar.'],
    ['nombre' => 'Trueno', 'img' => 'perro7.jpg', 'alt' => 'Foto de Trueno', 'desc' => 'Adulto de 4 años. Fuerte y leal, ideal para paseos largos.'],
    ['nombre' => 'Lola', 'img' => 'perro8.jpg', 'alt' => 'Foto de Lola', 'desc' => 'Adulta de 10 años. Muy tranquila, le encanta tumbarse al sol.'],
];

$d = [];
$errores = [];
$buscado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $d['texto'] = dato('texto');

    $errores = array_filter([
        'texto' => val_busqueda($d['texto'], 50),
    ]);

    if (!$errores && $d['texto'] !== '') {
        $buscado = true;
        $perros = array_filter($perros, fn($p) =>
            mb_stripos($p['nombre'] . ' ' . $p['desc'], $d['texto'], 0, 'UTF-8') !== false
        );
    }
}

cabecera('Nuestros perros');
?>
                <h1 class="titulo-pagina">Nuestros perros</h1>
                <p>Estos son los perros que ahora mismo esperan una familia. Puedes buscar por nombre o por carácter.</p>

                <form action="perros.php" method="post" class="buscador" novalidate>
                    <label for="texto">Buscar perro</label>
                    <input type="search" id="texto" name="texto" value="<?= e($d['texto'] ?? '') ?>" placeholder="Tranquilo, cachorro, Oso..."<?= attrs_error($errores, 'texto') ?>>
                    <button type="submit" class="boton">Buscar</button>
                    <?php mensaje_error($errores, 'texto'); ?>
                </form>

                <?php if ($buscado): ?>
                    <p>Resultados para «<?= e($d['texto']) ?>»:</p>
                <?php endif; ?>

                <?php if (!$perros): ?>
                    <div class="alerta-ok" role="status">No hemos encontrado ningún perro con esa búsqueda.</div>
                <?php else: ?>
                    <div class="rejilla">
                        <?php foreach ($perros as $p): ?>
                            <article class="perro">
                                <img src="../img/<?= e($p['img']) ?>" alt="<?= e($p['alt']) ?>">
                                <h3><?= e($p['nombre']) ?></h3>
                                <p><?= e($p['desc']) ?></p>
                                <p><a href="#">Ver ficha</a></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
<?php
pie();
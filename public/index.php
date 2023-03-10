<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Portal</title>
</head>

<body>
    <?php
    require '../vendor/autoload.php';
    $carrito = unserialize(carrito());

    $precio_min = obtener_get('precio_min');
    $precio_max = obtener_get('precio_max');
    $nombre = obtener_get('nombre');
    $categoria = obtener_get('categoria');
    $visible = obtener_get('visible');
    $creacion = obtener_get('creacion');

    $ordenar = obtener_get("ordenar");

    $pdo = conectar();

    $where = [];
    $execute = [];

    if (isset($categoria) && $categoria != '') {
        $where[] = 'categoria_id = :categoria';
        $execute[':categoria'] = $categoria;
    }
    if (isset($creacion) && $creacion != '') {
        $where[] = 'creacion = :creacion';
        $execute[':creacion'] = $creacion;
    }
    if (isset($precio_min) && $precio_min != '') {
        $where[] = '(precio - (precio * descuento / 100)) >= :precio_min';
        $execute[':precio_min'] = $precio_min;
    }
    if (isset($precio_max) && $precio_max != '') {
        $where[] = '(precio - (precio * descuento / 100)) <= :precio_max ';
        $execute[':precio_max'] = $precio_max;
    }
    if (isset($nombre) && $nombre != '') {
        $where[] = 'lower(descripcion) LIKE lower(:nombre)';
        $execute[':nombre'] = "%$nombre%";
    }

    if (isset($ordenar) && $ordenar != '') {
        if($ordenar == 'nombre') {
            $orderby[] = 'descripcion';
        } 
        if ($ordenar == 'precio') {
            $orderby[] = '(precio - (precio * descuento / 100))';
        }
    }


    $where = !empty($where) ?  'WHERE ' . implode(' AND ', $where) . ' AND visible = true' : 'WHERE visible = true';
    $orderby = !empty($orderby) ? 'ORDER BY ' . implode($orderby) : " ";

    try {
        $sent = $pdo->prepare("SELECT p.*, c.categoria, c.id as categoria_id
                            FROM articulos p
                            JOIN categorias c ON c.id = p.categoria_id
                            $where
                            $orderby");
        $sent->execute($execute);
    } catch (PDOException $e) {
        var_dump($e->getMessage());
        exit;
    }
    ?>
    <div class="container mx-auto">
        <?php require '../src/_menu.php' ?>
        <?php require '../src/_alerts.php' ?>
        <div>
            <form action="" method="GET">
                <fieldset>
                    <legend> <b>Criterios de b??squeda </b></legend><br>
                    <div class="flex mb-3 font-normal text-gray-700 dark:text-gray-400">
                        <label class="block mb-2 text-sm font-medium w-1/4 pr-4">
                            Categoria:
                            <select name="categoria" id="categoria" class="border text-sm rounded-lg w-full p-2.5">
                                <?php
                                $sent2 = $pdo->query("SELECT *
                                FROM categorias");
                                ?>
                                <option value="">Todas las categor??as</option>
                                <?php foreach ($sent2 as $fila) : ?>
                                    <option value=<?= hh($fila['id']) ?> <?= ($fila['id'] == $categoria) ? 'selected' : '' ?>>
                                        <?= hh($fila['categoria']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </label>
                        <label class="block mb-2 text-sm font-medium w-1/4 pr-4">
                            Ordenar por:
                            <form action="" method="GET">
                                <select id="ordenar" name="ordenar" class="border text-sm rounded-lg w-full p-2.5">
                                <option value= "">  </option>
                                    <option name="nombre" value=<?= "nombre" ?>> Nombre </option>
                                    <option name="precio" value=<?= "precio" ?>> Precio </option>
                                </select>
                        </label>

                        <label class="block mb-2 text-sm font-medium w-1/4 pr-4">
                            Precio m??nimo:
                            <input type="text" name="precio_min" value="<?= $precio_min ?>" class="border text-sm rounded-lg w-full p-2.5">
                        </label>
                        <label class="block mb-2 text-sm font-medium w-1/4 pr-4">
                            Precio m??ximo:
                            <input type="text" name="precio_max" value="<?= $precio_max ?>" class="border text-sm rounded-lg w-full p-2.5">
                        </label>
                        <label class="block mb-2 text-sm font-medium w-1/4 pr-4">
                            Nombre del articulo:
                            <input type="text" name="nombre" value="<?= $nombre ?>" class="border text-sm rounded-lg w-full p-2.5">
                        </label>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Buscar</button>
                </fieldset>
            </form>
            <a href="/descuentos.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                REBAJAS
            </a>
            </button>
            
        </div></br>
        <div class="flex">
            <main class="flex-1 grid grid-cols-3 gap-4 justify-center justify-items-center">
                <?php foreach ($sent as $fila) : ?>
                    <div class="p-6 max-w-xs min-w-full bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <?php if (isset($fila['descuento']) && $fila['descuento'] != '' && $fila['descuento'] > 0) : ?>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= hh($fila['descripcion']) ?> - <span class="mb-3 font-normal text-red-700 dark:text-red-400 "> <del><?= hh($fila['precio']) ?> ??? </del></span></h5>

                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-red-700 dark:text-red"> Precio Rebajado: <?= hh($fila['precio']) - hh(($fila['precio'] * $fila['descuento']) / 100) ?> ???</h5>
                        <?php else : ?>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= hh($fila['descripcion']) ?> - <?= hh($fila['precio']) ?> ??? </h5>
                        <?php endif ?>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= hh($fila['descripcion']) ?></p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= hh($fila['categoria']) ?></p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Existencias: <?= hh($fila['stock']) ?></p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Fecha: <?= hh($fila['creacion']) ?></p>
                        <?php if ($fila['stock'] > 0) : ?>
                            <a href="/insertar_en_carrito.php?id=<?= $fila['id'] ?>&categoria=<?= hh($categoria) ?>&nombre=<?= hh($nombre) ?>&precio_min=<?= hh($precio_min) ?>&precio_max=<?= hh($precio_max) ?>" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                A??adir al carrito
                                <svg aria-hidden="true" class="ml-3 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        <?php else : ?>
                            <a class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                Sin existencias
                            </a>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </main>

            <?php if (!$carrito->vacio()) : ?>
                <aside class="flex flex-col items-center w-1/4" aria-label="Sidebar">
                    <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
                        <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <th scope="col" class="py-3 px-6">Descripci??n</th>
                                <th scope="col" class="py-3 px-6">Cantidad</th>
                            </thead>
                            <tbody>
                                <?php foreach ($carrito->getLineas() as $id => $linea) : ?>
                                    <?php
                                    $articulo = $linea->getArticulo();
                                    $cantidad = $linea->getCantidad();
                                    ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6"><?= $articulo->getDescripcion() ?> <br>
                                        <?= $articulo->getCategoriaNombre()->categoria ?>

                                        </td>
                                        <td class="py-4 px-6 text-center"><?= $cantidad ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <a href="/vaciar_carrito.php" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Vaciar carrito</a>
                        <a href="/comprar.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Comprar</a>
                    </div>
                </aside>
            <?php endif ?>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>
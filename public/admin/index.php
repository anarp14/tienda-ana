<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <script>
        function cambiar(el, id) {
            el.preventDefault();
            const oculto = document.getElementById('oculto');
            oculto.setAttribute('value', id);
        }
        function cambiarModificar(el, id) {
            el.preventDefault();
            const ocultoModificar = document.getElementById('ocultoModificar');
            ocultoModificar.setAttribute('value', id);
        }
    </script>
    <title>Listado de artículos</title>
</head>

<body>
    <?php
    require '../../vendor/autoload.php';

    if ($usuario = \App\Tablas\Usuario::logueado()) {
        if (!$usuario->es_admin()) {
            $_SESSION['error'] = 'Acceso no autorizado.';
            return volver();
        }
    } else {
        return redirigir_login();
    }

    $pdo = conectar();
    $sent = $pdo->query("SELECT p.*, c.categoria FROM articulos p JOIN categorias c ON c.id = p.categoria_id ORDER BY codigo");
    ?>

    <div class="container mx-auto">
        <?php require '../../src/_menu.php' ?>
        <?php require '../../src/_alerts.php' ?>
        <?php require_once '../../src/_modales.php'?>
        <div class="overflow-x-auto relative mt-4">
        <a href="usuarios.php" target="_blank">
                <button class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900">Usuarios</button>
            </a>
            <a href="categorias.php" target="_blank">
                <button class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900">Categorias</button>
            </a>

            <button data-modal-toggle="insertar" href="/admin/insertar.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-green dark:bg-gray-900 rounded-md group-hover:bg-opacity-1">
                    Insertar artículo
                </span>
            </button>
            <br>
            <br>
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Código</th>
                    <th scope="col" class="py-3 px-6">Descripción</th>
                    <th scope="col" class="py-3 px-6">Precio</th>
                    <th scope="col" class="py-3 px-6">Precio rebajado</th>
                    <th scope="col" class="py-3 px-6">Stock</th>
                    <th scope="col" class="py-3 px-6">Categoria</th>
                    <th scope="col" class="py-3 px-6">Categoria id</th>
                    <th scope="col" class="py-3 px-6">Visibilidad</th>
                    <th scope="col" class="py-3 px-6 text-center">Acciones</th>
                </thead>
                <tbody>
                    <?php foreach ($sent as $fila) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6"><?= hh($fila['codigo']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['descripcion']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['precio']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['precio_rebajado']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['stock']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['categoria']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['categoria_id']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['visible']) ? 'Si' : 'No' ?></td>
                            <td class="px-6 text-center">
                                <?php $fila_id = hh($fila['id']) ?>
                            <form action="/admin/editar.php" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?= $fila_id ?>">
                                <button type="submit" onclick="cambiarModificar(event, <?= $fila_id ?>)" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900" data-modal-toggle="modificar">
                                    Editar
                                </button>
                            </form>
                                <form action="/admin/borrar.php" method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?= $fila_id ?>">
                                    <button type="submit" onclick="cambiar(event, <?= $fila_id ?>)" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" data-modal-toggle="popup-modal">Borrar</button>
                                </form>
                            </td>
                        </tr> 
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>
<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Perfil de usuario</title>
</head>

<body>
    <?php
    require '../vendor/autoload.php';
    require '../src/_modales.php';
    require '../src/_menu.php';
    require '../src/_alerts.php';


    if (!($usuario = \App\Tablas\Usuario::esta_logueado())) {
        return redirigir_login();
    }

    $pdo = conectar();

    $usuario = \App\Tablas\Usuario::logueado()->id;

    $sent = $pdo->query("SELECT * FROM usuarios WHERE  id = $usuario");


    ?>
    <div class="container mx-auto">
        <div class="overflow-x-auto relative mt-4">
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Usuario</th>
                    <th scope="col" class="py-3 px-6">Nombre</th>
                    <th scope="col" class="py-3 px-6">Apellidos</th>
                    <th scope="col" class="py-3 px-6">Email</th>
                    <th scope="col" class="py-3 px-6">Teléfono</th>
                    <th scope="col" class="py-3 px-6 text-center">Acciones</th>
                </thead>
                <tbody>
                    <?php foreach ($sent as $fila) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6"><?= hh($fila['usuario']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['nombre']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['apellidos']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['email']) ?></td>
                            <td class="py-4 px-6"><?= hh($fila['telefono']) ?></td>
                            <td class="px-6 text-center">
                                <?php require '../src/_alerts.php' ?>
                                    <button data-modal-toggle="insertar_datos_perfil" href="insertar_datos_perfil.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                                        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-green dark:bg-gray-900 rounded-md group-hover:bg-opacity-1">
                                            Insertar datos
                                        </span>
                                    </button>

                                    <a href="/cambiar_password.php" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Cambiar contraseña</a>
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
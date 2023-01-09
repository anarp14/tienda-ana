<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Listado de usuarios</title>
</head>

<body>
    <?php
    require '../vendor/autoload.php';

   
    if (!($usuario = \App\Tablas\Usuario::esta_logueado())) {
        return redirigir_login();
    }

    $pdo = conectar();
    $_USER = $_SESSION['login']; 

    $sent = $pdo->prepare("SELECT * FROM usuarios WHERE  usuario =  :usuario");
    $sent->execute(([':usuario' => $_USER]));
    var_dump($_USER);
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

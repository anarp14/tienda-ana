<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Cambiar contraseña</title>
</head>

<body>
    <?php

    require '../vendor/autoload.php';

    $password = obtener_post('password');
    $newpassword = obtener_post('newpassword');
    $passwordrepeat = obtener_post('passwordrepeat');

    $clases_label = [];
    $clases_input = [];
    $error = ['password' => [], 'newpassword' => [], 'passwordrepeat' => []];

    $clases_label_error = "text-red-700 dark:text-red-500";
    $clases_input_error = "bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 dark:bg-red-100 dark:border-red-400";

    foreach (['password', 'newpassword', 'passwordrepeat'] as $e) {
        $clases_label[$e] = '';
        $clases_input[$e] = '';
    }

    if (isset($password, $newpassword, $passwordrepeat)) {

        $pdo = conectar();
        $usuario = \App\Tablas\Usuario::logueado();
        $id = $usuario->obtenerId();

        // Recoge la contraseña actual
        $sent = $pdo->prepare("SELECT password
                        FROM usuarios
                        WHERE id = :id");
        $sent->execute([':id' => $id]);
        $origin = $sent->fetchColumn();

        if ($password == '') {
            $error['password'][] = 'La contraseña es obligatoria.';
        } else if (!empty($password)) {
            if ($password == $newpassword) {
                $error['password'][] = 'La contraseña no puede coincidir con la actual';
            }
            if (!(password_verify($password, $origin))) {
                $error['password'][] = 'La contraseña no coincide con la actual';
            }
        }

        if ($newpassword == '') {
            $error['newpassword'][] = 'La contraseña es obligatoria.';
        }

        if ($passwordrepeat == '') {
            $error['passwordrepeat'][] = 'La contraseña es obligatoria.';
        }
        if (mb_strlen($newpassword) < 8) {
            $error['newpassword'][] = 'Debe tener al menos 8 caracteres.';
        }
        if (preg_match('/[a-z]/', $newpassword) !== 1) {
            $error['newpassword'][] = 'Debe contener al menos una minúscula.';
        }
        if (preg_match('/[A-Z]/', $newpassword) !== 1) {
            $error['newpassword'][] = 'Debe contener al menos una mayúscula.';
        }
        if (preg_match('/[[:digit:]]/', $newpassword) !== 1) {
            $error['newpassword'][] = 'Debe contener al menos un dígito.';
        }

        if (preg_match('/[[:punct:]]/', $newpassword) !== 1) {
            $error['newpassword'][] = 'Debe contener al menos un signo de puntuación.';
        }

        $vacio = true;

        foreach ($error as $err) {
            if (!empty($err)) {
                $vacio = false;
                break;
            }
        }

        if ($vacio) {
            $usuario->cambiar_password($usuario, $newpassword, $pdo);
            $_SESSION['exito'] = 'La contraseña se ha modificado correctamente.';

            return volver_a("/perfil.php");
        }
    }




    ?>

    <!-- Esto es para modificar la contraseña-->
    <div class="container mx-auto">
        <?php require '../src/_menu.php' ?>
        <div class="mx-72">
            <form action="" method="POST">
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium <?= $clases_label['password'] ?>">Contraseña actual</label>
                    <input type="password" name="password" id="password" class="border text-sm rounded-lg block w-full p-2.5 <?= $clases_input['password'] ?>" value="<?= hh($password) ?>">
                    <?php foreach ($error['password'] as $err) : ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
                    <?php endforeach ?>
                </div>
                <div class="mb-6">
                    <label for="newpassword" class="block mb-2 text-sm font-medium <?= $clases_label['newpassword'] ?>">Nueva Contraseña</label>
                    <input type="password" name="newpassword" id="newpassword" class="border text-sm rounded-lg block w-full p-2.5  <?= $clases_input['newpassword'] ?>">
                    <?php foreach ($error['newpassword'] as $err) : ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
                    <?php endforeach ?>
                </div>
                <div class="mb-6">
                    <label for="password_repeat" class="block mb-2 text-sm font-medium <?= $clases_label['passwordrepeat'] ?>">Confirmar contraseña</label>
                    <input type="password" name="passwordrepeat" id="passwordrepeat" class="border text-sm rounded-lg block w-full p-2.5  <?= $clases_input['passwordrepeat'] ?>">
                    <?php foreach ($error['passwordrepeat'] as $err) : ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
                    <?php endforeach ?>
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cambiar</button>
            </form>
        </div>
    </div>

    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>
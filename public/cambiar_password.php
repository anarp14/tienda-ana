<?php

use App\Tablas\Usuario;

 session_start() ?>
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
    $new_password = obtener_post('new_password');
    $new_password_repeat = obtener_post('new_password_repeat');

    $clases_label = [];
    $clases_input = [];
    $error = ['password' => [], 'new_password' => [], 'new_password_repeat' => []];

    $clases_label_error = "text-red-700 dark:text-red-500";
    $clases_input_error = "bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 dark:bg-red-100 dark:border-red-400";

    foreach (['new_password', 'password_repeat'] as $e) {
        $clases_label[$e] = '';
        $clases_input[$e] = '';
    }

    if (isset($password,$new_password,$new_password_repeat)) {
        $pdo = conectar();

        if ($new_password != $new_password_repeat) {
            $error['password'][] = 'Las contraseñas no coinciden.';
        }

        if ($new_password == '') {
            $error['password'][] = 'La contraseña es obligatoria.';
        }

        if ($new_password_repeat == '') {
            $error['password_repeat'][] = 'La contraseña es obligatoria.';
        }
        if (mb_strlen($new_password ) < 8) {
            $error['password'][] = 'Debe tener al menos 8 caracteres.';
        }
        if (preg_match('/[a-z]/', $new_password ) !== 1) {
            $error['password'][] = 'Debe contener al menos una minúscula.';
        }
        if (preg_match('/[A-Z]/', $new_password ) !== 1) {
                $error['password'][] = 'Debe contener al menos una mayúscula.';
        }
        if (preg_match('/[[:digit:]]/', $new_password ) !== 1) {
            $error['password'][] = 'Debe contener al menos un dígito.';
        }

        if (preg_match('/[[:punct:]]/', $new_password ) !== 1) {
            $error['password'][] = 'Debe contener al menos un signo de puntuación.';
        }    
        
        $vacio = true;

        foreach ($error as $err) {
            if (!empty($err)) {
                $vacio = false;
                break;
            }
        }
        if ($vacio) {
            // Registrar
            Usuario::cambiar_contraseña($user, $password, $pdo);   
            $_SESSION['exito'] = 'Contraseña cambiada correctamente.';
        } else {
            foreach (['login', 'password', 'password_repeat'] as $e) {
                if (isset($error[$e])) {
                    $clases_input[$e] = $clases_input_error;
                    $clases_label[$e] = $clases_label_error;
                }
            }
        }
    }
    
    ?>
    <div class="container mx-auto">
        <?php require '../src/_menu.php' ?>
        <div class="mx-72">
            <form action="" method="POST">
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium <?= $clases_label['password'] ?>">Contraseña antigua</label>
                    <input type="password" name="password" id="password" class="border text-sm rounded-lg block w-full p-2.5  <?= $clases_input['password'] ?>">
                    <?php foreach ($error['password'] as $err): ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
                    <?php endforeach ?>
                </div>
                <div class="mb-6">
                div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium <?= $clases_label['new_password'] ?>">Contraseña nueva</label>
                    <input type="new_password" name="new_password" id="new_password" class="border text-sm rounded-lg block w-full p-2.5  <?= $clases_input['new_password'] ?>">
                    <?php foreach ($error['new_password'] as $err): ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
                    <?php endforeach ?>
                </div>
                    <label for="new_password_repeat" class="block mb-2 text-sm font-medium <?= $clases_label['password_repeat'] ?>">Confirmar contraseña</label>
                    <input type="new_password" name="new_password_repeat" id="new_password_repeat" class="border text-sm rounded-lg block w-full p-2.5  <?= $clases_input['new_password_repeat'] ?>">
                    <?php foreach ($error['password_repeat'] as $err): ?>
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-bold">¡Error!</span> <?= $err ?></p>
                    <?php endforeach ?>
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar</button>
            </form>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>
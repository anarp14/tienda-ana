<?php

use App\Tablas\Factura;
use App\Tablas\Usuario;

 session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Dashboard</title>
</head>

<body>
    <?php require '../vendor/autoload.php';

    if (!\App\Tablas\Usuario::esta_logueado()) {
        return redirigir_login();
    }

    $facturas = Factura::todosConTotal(
        ['usuario_id = :usuario_id'],
        [':usuario_id' => Usuario::logueado()->id]
    );
    ?>

    <div class="container mx-auto">
        <?php require_once '../src/_menu.php' ?>
        <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Fecha</th>
                    <th scope="col" class="py-3 px-6">Total</th>
                    <th scope="col" class="py-3 px-6 text-center">Acciones</th>
                </thead>
                <tbody>
                    <?php foreach ($facturas as $factura): ?>
                        <?php
                        $created_at = DateTime::createFromFormat(
                            'Y-m-d H:i:s',
                            $factura->getCreatedAt()
                        )->setTimezone(new DateTimeZone('Europe/Madrid'));
                        ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6">
                                <?= hh($created_at->format('d-m-Y H:i:s')) ?>
                            </td>
                            <td class="py-4 px-6">
                                <?= hh(dinero($factura->getTotal())) ?>
                            </td>
                            <td class="px-6 text-center">
                                <a href="/factura_pdf.php?id=<?= $factura->id ?>" target="_blank">
                                   <button class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900">PDF</button>
                                </a>
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

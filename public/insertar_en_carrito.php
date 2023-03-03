<?php

use App\Tablas\Articulo;

session_start();

require '../vendor/autoload.php';

$categoria = obtener_get('categoria');
$precio_min = obtener_get('precio_min');
$precio_max = obtener_get('precio_max');
$nombre = obtener_get('nombre');

try {
    $id = obtener_get('id');

    if ($id === null) {
        volver();
    }

    $articulo = Articulo::obtener($id);

    if ($articulo === null) {
        volver();
    }

    if ($articulo->getStock() <= 0) {
        $_SESSION['error'] = 'No hay existencias suficientes.';
        volver();
    }

    $carrito = unserialize(carrito());
    $carrito->insertar($id);
    $_SESSION['carrito'] = serialize($carrito);

    $params = "";
    if ($nombre !== null) {
        $params .= '&nombre=' . hh($nombre);
    }

        
    if ($categoria !== null) {
        $params .= '&categoria=' . hh($categoria);
    }

    if ($precio_max !== null) {
        $params .= '&precio_max=' . hh($precio_max);
    }

    if ($precio_min !== null) {
        $params .= '&precio_min=' . hh($precio_min);
    }

    header("Location: /index.php?$params");
} catch (ValueError $e) {
    // TODO: mostrar mensaje de error en un Alert
    volver();
}

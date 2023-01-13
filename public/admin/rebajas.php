<?php

session_start();

require '../../src/auxiliar.php';

$pdo = conectar();

// Obtener el id del producto y el porcentaje de descuento
$id = obtener_post('id');
$descuento = obtener_post('rebaja');

// Calcular el nuevo precio del producto
$sent = $pdo->prepare("SELECT precio FROM articulos WHERE id = :id");
$sent->execute(['id' => $id]);
$linea = $sent->fetch(PDO::FETCH_ASSOC);
$precio_inicial = $linea['precio'];
$cantidad_descuento = $precio_inicial * ($descuento / 100);
$nuevo_precio = $precio_inicial - $cantidad_descuento;

// Actualizar el precio del producto en la base de datos
$sent = $pdo->prepare("UPDATE articulos
                            SET descuento = :descuento
                            WHERE id = :id");
$resultado = $sent->execute([
    'descuento' => $descuento,
    'id' => $id
]);

if ($resultado) {
    $_SESSION['exito'] = 'Se ha aplicado correctamente el descuento al producto.';
} else {
    $_SESSION['exito'] = 'Error al aplicar el descuento al producto.';
}

return volver_admin();
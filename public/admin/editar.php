
<?php
session_start();
require '../../src/auxiliar.php';

$id = obtener_post('id');
$codigo= obtener_post('codigo');
$descripcion = obtener_post('descripcion');
$precio = obtener_post('precio');
$stock = obtener_post('stock');

$pdo = conectar();
$set = [];
$execute = [];
$where = [];
if (isset($codigo) && $codigo != '') {
    $set[] = 'codigo = :codigo';
    $execute[':codigo'] = $codigo;
} 
if (isset($descripcion) && $descripcion != '') {
    $set[] = 'descripcion = :descripcion';
    $execute[':descripcion'] = $descripcion;
}
if (isset($precio) && $precio != '') {
    $set[] = 'precio = :precio';
    $execute[':precio'] = $precio;
}
if (isset($stock) && $stock != '') {
    $set[] = 'stock = :stock';
    $execute[':stock'] = $stock;
}
$set= !empty($set) ? 'SET ' . implode(' , ', $set) : '';

$sent = $pdo->prepare("UPDATE articulos
                        $set
                       WHERE id = $id");

var_dump($execute);
var_dump($where);
$sent->execute($execute);

var_dump($sent);

$_SESSION['exito'] = "Artículo modificado con éxito.";
return volver_admin();


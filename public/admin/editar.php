
<?php
session_start();

require '../../src/auxiliar.php';
$id = obtener_post('id');
$codigo= obtener_post('codigo');
$descripcion = obtener_post('descripcion');
$precio = obtener_post('precio');
$stock = obtener_post('stock');

$pdo = conectar();
$sent = $pdo->prepare("UPDATE articulos
                        SET codigo = :codigo, 
                        descripcion = :descripcion, 
                        precio = :precio, 
                        stock = :stock
                       WHERE id = :id");
$sent->execute([
    ':id' => $id,
    ':codigo' => $codigo,
    ':descripcion' => $descripcion,
    ':precio' => $precio,
    ':stock' => $stock
]);
$_SESSION['exito'] = "Artículo modificado con éxito.";
return volver_admin();
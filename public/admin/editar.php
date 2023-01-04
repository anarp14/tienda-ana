
<?php
session_start();
require '../../src/auxiliar.php';

$id = obtener_post('id');
$codigo= obtener_post('codigo');
$descripcion = obtener_post('descripcion');
$precio = obtener_post('precio');
$stock = obtener_post('stock');
$categoria_id= obtener_post('categoria_id');
$visible= obtener_post('visible');


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
if (isset($categoria_id) && $categoria_id != '') {
    $set[] = 'categoria_id = :categoria_id';
    $execute[':categoria_id'] = $categoria_id;
}
if (isset($visible) && $visible != '') {
    $set[] = 'visible = :visible';
    $execute[':visible'] = $visible;
}

$set= !empty($set) ? 'SET ' . implode(' , ', $set) : '';
$sent = $pdo->prepare("UPDATE articulos
                        $set
                       WHERE id = $id");

$sent->execute($execute);

$_SESSION['exito'] = "Artículo modificado con éxito.";
return volver_admin();


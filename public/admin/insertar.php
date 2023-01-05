<?php
session_start();

require "../../src/auxiliar.php";

$id = obtener_post('id');
$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$precio_rebajado = $_POST['precio_rebajado'];
$stock = $_POST['stock'];
$categoria_id = $_POST['categoria_id'];
$visible = $_POST['visible'];

var_dump($precio_rebajado);
// Conecta con la base de datos
$pdo = conectar();

// Recoge los valores actuales del registro
$sent = $pdo->prepare("SELECT codigo, descripcion, precio, precio_rebajado, stock, visible, categoria_id, visible
                        FROM articulos
                        WHERE id = :id");
$sent->execute([':id' => $id]);
$origin = $sent->fetch(PDO::FETCH_ASSOC);

$sent = $pdo->prepare("INSERT INTO articulos (codigo, descripcion, precio, precio_rebajado, stock, categoria_id, visible) VALUES (:codigo, :descripcion, :precio, :precio_rebajado, :stock, :categoria_id, :visible)");
$sent->execute([
    ':codigo' => $codigo,
    ':descripcion' => $descripcion,
    ':precio' => $precio,
    ':precio_rebajado' => $precio_rebajado ?: $origin['precio_rebajado'],
    ':stock'  => $stock,
    ':categoria_id'  => $categoria_id,
    ':visible'  => $visible
]);

$_SESSION['exito'] = 'El artículo se ha añadido correctamente.';


return volver_admin();
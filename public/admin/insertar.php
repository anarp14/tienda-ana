<?php
session_start();

require "../../src/auxiliar.php";

$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$categoria_id = $_POST['categoria_id'];

$pdo = conectar();
$sent = $pdo->prepare("INSERT INTO articulos (codigo, descripcion, precio, stock, categoria_id) VALUES (:codigo, :descripcion, :precio, :stock, :categoria_id)");
$sent->execute([
    ':codigo' => $codigo,
    ':descripcion' => $descripcion,
    ':precio' => $precio,
    ':stock'  => $stock,
    ':categoria_id'  => $categoria_id
]);

$_SESSION['exito'] = 'El artículo se ha añadido correctamente.';


return volver_admin();
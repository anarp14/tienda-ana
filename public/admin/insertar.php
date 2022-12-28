<?php
session_start();

require "../../src/auxiliar.php";

$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$pdo = conectar();
$sent = $pdo->prepare("INSERT INTO articulos (codigo, descripcion, precio, stock) VALUES (:codigo, :descripcion, :precio, :stock)");
$sent->execute([
    ':codigo' => $codigo,
    ':descripcion' => $descripcion,
    ':precio' => $precio,
    ':stock'  => $stock
]);

$_SESSION['exito'] = 'El artículo se ha añadido correctamente.';


return volver_admin();
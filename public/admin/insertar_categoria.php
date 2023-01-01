<?php
session_start();

require "../../src/auxiliar.php";

$categoria = $_POST['categoria'];


$pdo = conectar();
$sent = $pdo->prepare("INSERT INTO categorias(categoria) VALUES (:categoria)");
$sent->execute([
    ':categoria' => $categoria
]);

$_SESSION['exito'] = 'La categoria se ha añadido correctamente.';


return volver_categorias();
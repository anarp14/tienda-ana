<?php
session_start();

require '../../vendor/autoload.php';


$categoria= obtener_post('categoria');



// Conecta con la base de datos
$pdo = conectar();


$sent = $pdo->prepare("INSERT INTO categorias (categoria) VALUES (:categoria)");
$sent->execute([
    ':categoria' => $categoria
]);


$_SESSION['exito'] = "La categoria se ha añadido correctamente.";
return volver_categoria();


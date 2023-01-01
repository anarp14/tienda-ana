
<?php
session_start();
require '../../src/auxiliar.php';

$id = obtener_post('id');
$categoria= obtener_post('categoria');



$pdo = conectar();

$sent = $pdo->prepare("UPDATE categorias
                        SET categoria = :categoria
                       WHERE id = :id");


$sent->execute([
    ':id' => $id,
    ':categoria' => $categoria
]);

$_SESSION['exito'] = "La categoria se ha modificado con éxito.";
return volver_categorias();

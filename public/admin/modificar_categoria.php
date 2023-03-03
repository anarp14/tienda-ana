<?php
session_start();

require '../../vendor/autoload.php';


$id = obtener_post('id');
$categoria= obtener_post('categoria');


$pdo = conectar();



$sent = $pdo->prepare("UPDATE categorias
                        SET categoria = :categoria
                       WHERE id = :id");

$sent->execute([
    ':id' => $id,
    ':categoria'  => $categoria
]);

if ($sent->rowCount() === 0) {
    echo "No se encontraron registros para actualizar.";
} else {
    echo "Se actualizó la categoría con éxito.";
}

$_SESSION['exito'] = "La categoria se ha modificado con éxito.";
return volver_categoria();


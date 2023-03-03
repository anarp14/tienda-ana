<?php
session_start();

require '../../vendor/autoload.php';


$id = obtener_post('id');
$visible= obtener_post('visible');


$pdo = conectar();



$sent = $pdo->prepare("UPDATE articulos
                        SET visible = NOT visible
                       WHERE id = :id");

$sent->execute([
    ':id' => $id
]);

return volver_admin();

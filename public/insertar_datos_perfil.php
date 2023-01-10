<?php
session_start();
require_once '../vendor/autoload.php';
require_once "../src/auxiliar.php";

$id = \App\Tablas\Usuario::logueado()->id;
var_dump($id);
$nombre = obtener_post('nombre');
$apellidos = obtener_post('apellidos');
$email = obtener_post('email');
$telefono= obtener_post('telefono');

$set = [];
$execute = [];
$where = [];

if (isset($nombre) && $nombre != '') {
    $set[] = 'nombre = :nombre';
    $execute[':nombre'] = $nombre;
}
if (isset($apellidos) && $apellidos != '') {
    $set[] = 'apellidos = :apellidos';
    $execute[':apellidos'] = $apellidos;
} 
if (isset($email) && $email != '') {
    $set[] = 'email = :email';
    $execute[':email'] = $email;
}
if (isset($telefono) && $telefono != '') {
    $set[] = 'telefono = :telefono';
    $execute[':telefono'] = $telefono;
} 


// Conecta con la base de datos
$pdo = conectar();

// Recoge los valores actuales del registro
$set= !empty($set) ? 'SET ' . implode(' , ', $set) : '';
$sent = $pdo->prepare("UPDATE usuarios
                        $set
                       WHERE id = $id");

$sent->execute($execute);

$_SESSION['exito'] = 'El perfil del usuario se ha añadido correctamente.';


return(volver());
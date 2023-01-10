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
    if (!preg_match("/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/", $email)) {
        $_SESSION['error'] = "El email es inválido";
        return (volver_a("/perfil.php"));
    }
    $set[] = 'email = :email';
    $execute[':email'] = $email;
}
if (isset($telefono) && $telefono != '') { if (!preg_match("/^\d{9}$/", $telefono)) {
    $_SESSION['error'] = "El teléfono debe contener 9 dígitos y sólo puede contener números";
    return (volver_a("/perfil.php"));
}
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


return (volver_a("/perfil.php"));
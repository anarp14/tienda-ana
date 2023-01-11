<?php
session_start();

require '../vendor/autoload.php';


// Recive los datos mediante POST
$password = obtener_post('password');
$newpassword = obtener_post('newpassword');
$passwordrepeat = obtener_post('passwordrepeat');

// Conecta con la base de datos
$pdo = conectar();

$usuario = \App\Tablas\Usuario::logueado();
$id = $usuario->obtenerId();


// Recoge la contraseña actual
$sent = $pdo->prepare("SELECT password
                        FROM usuarios
                        WHERE id = :id");
$sent->execute([':id' => $id]);
$origin = $sent->fetchColumn();


//Actualiza la contraseña del usuario
if(password_verify($password, $origin)) {
    if($newpassword == $passwordrepeat) {
        $usuario->cambiar_password($usuario, $newpassword, $pdo);
        $_SESSION['exito'] = 'La contraseña se ha modificado correctamente.';

        return volver_a("/perfil.php");
    }
}

//Establecer un mensaje de éxito y vuelve a la página de perfil
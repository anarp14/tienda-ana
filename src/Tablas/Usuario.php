<?php
namespace App\Tablas;

use PDO;

class Usuario extends Modelo
{
    protected static string $tabla = 'usuarios';

    public $id;
    public $usuario;
    public $validado;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->usuario = $campos['usuario'];
        $this->validado = $campos['validado'];
    }

    public function es_admin(): bool
    {
        return $this->usuario == 'admin';
    }

    public static function esta_logueado(): bool
    {
        return isset($_SESSION['login']);
    }

    // devuelve el usuario actual si hay una sesión de "login" activa, de lo contrario devuelve null.
    
    public static function logueado(): ?static
    {
        return isset($_SESSION['login']) ? unserialize($_SESSION['login']) : null;
    }

    public static function comprobar($login, $password, ?PDO $pdo = null)
    {
        $pdo = $pdo ?? conectar();

        $sent = $pdo->prepare('SELECT *
                                 FROM usuarios
                                WHERE usuario = :login');
        $sent->execute([':login' => $login]);
        $fila = $sent->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            return false;
        }

        return password_verify($password, $fila['password'])
            ? new static($fila)
            : false;
    }

    public static function existe($login, ?PDO $pdo = null): bool
    {
        return $login == '' ? false :
            !empty(static::todos(
                ['usuario = :usuario'],
                [':usuario' => $login],
                $pdo
            ));
    }

    public static function registrar($login, $password, ?PDO $pdo = null)
    {
        $sent = $pdo->prepare('INSERT INTO usuarios (usuario, password, validado)
                               VALUES (:login, :password, false)');
        $sent->execute([
            ':login' => $login,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function obtenerId()
    {
        return $this->id;
    }

    public function cambiar_password($user, $password, ?PDO $pdo = null)
    {
        $sent = $pdo->prepare("UPDATE usuarios
                                SET password = :password
                                WHERE id = :id");
        $sent->execute([
            ':id' => $user->obtenerId(),
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }
}


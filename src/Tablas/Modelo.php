<?php

namespace App\Tablas;

use PDO;

class Modelo
{
    protected static string $tabla;

    public static function obtener(int $id, ?PDO $pdo = null): ?static
    {
        $pdo = $pdo ?? conectar();
        $tabla = static::$tabla;
        $sent = $pdo->prepare("SELECT *
                                 FROM $tabla
                                WHERE id = :id");
        $sent->execute([':id' => $id]);
        $fila = $sent->fetch(PDO::FETCH_ASSOC);

        return $fila ? new static($fila) : null;
    }

    public static function todos(
        array $where = [],
        array $execute = [],
        ?PDO $pdo = null
    ): array
    {
        $pdo = $pdo ?? conectar();
        $tabla = static::$tabla;
        $where = !empty($where)
            ? 'WHERE ' . implode(' AND ', $where)
            : '';
        $sent = $pdo->prepare("SELECT * FROM $tabla $where");
        $sent->execute($execute);
        $filas = $sent->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($filas as $fila) {
            $res[] = new static($fila);
        }
        return $res;
    }
}

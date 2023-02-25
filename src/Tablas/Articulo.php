<?php

namespace App\Tablas;


use App\Tablas\Categoria;
use PDO;

class Articulo extends Modelo
{
    protected static string $tabla = 'articulos';

    public $id;
    private $codigo;
    private $descripcion;
    private $precio;
    private $descuento;
    private $stock;
    private $categoria_id;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->codigo = $campos['codigo'];
        $this->descripcion = $campos['descripcion'];
        $this->precio = $campos['precio'];
        $this->descuento = $campos['descuento'];
        $this->stock = $campos['stock'];
        $this->categoria_id = $campos['categoria_id'];
    }

    public static function existe(int $id, ?PDO $pdo = null): bool
    {
        return static::obtener($id, $pdo) !== null;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getDescuento()
    {
        return $this->descuento;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCategoriaNombre(?PDO $pdo = null)
    {
        return Categoria::obtener($this->categoria_id, $pdo)->categoria;
    }
}

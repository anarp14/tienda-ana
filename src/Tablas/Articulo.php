<?php

namespace App\Tablas;

use PDO;

class Articulo extends Modelo
{
    protected static string $tabla = 'articulos';

    public $id;
    private $codigo;
    private $descripcion;
    private $precio;
    private $cantidad_descuento;
    private $descuento;
    private $stock;
    private $categoria_id;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->codigo = $campos['codigo'];
        $this->descripcion = $campos['descripcion'];
        $this->precio = $campos['precio'];
        $this->cantidad_descuento = $campos['cantidad_descuento'];
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
    public function getCantidadDescuento()
    {
        return $this->cantidad_descuento;
    }
    public function getDescuento()
    {
        return $this->descuento;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getCategoria_id()
    {
        return $this->categoria_id;
    }
    public function getId()
    {
        return $this->id;
    }
}

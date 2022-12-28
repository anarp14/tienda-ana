<?php

namespace App\Tablas;

use App\Tablas\Articulo;

class Linea extends Modelo
{
    private Articulo $articulo;
    private int $cantidad;

    public function __construct(array $campos)
    {
        $this->articulo = Articulo::obtener($campos['articulo_id']);
        $this->cantidad = $campos['cantidad'];
    }

    public function getArticulo(): Articulo
    {
        return $this->articulo;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }
}

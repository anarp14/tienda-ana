<?php

namespace App\Generico;

use App\Tablas\Articulo;
use ValueError;

class Carrito extends Modelo
{
    private array $lineas;

    public function __construct()
    {
        $this->lineas = [];
    }

    public function insertar($id)
    {
        if (!($articulo = Articulo::obtener($id))) {
            throw new ValueError('El artículo no existe.');
        }

        if (isset($this->lineas[$id])) {
            $this->lineas[$id]->incrCantidad();
        } else {
            $this->lineas[$id] = new Linea($articulo);
        }
    }

    public function eliminar($id)
    {
        if (isset($this->lineas[$id])) {
            $this->lineas[$id]->decrCantidad();
            if ($this->lineas[$id]->getCantidad() == 0) {
                unset($this->lineas[$id]);
            }
        } else {
            throw new ValueError('Artículo inexistente en el carrito');
        }
    }

    public function vacio(): bool
    {
        return empty($this->lineas);
    }

    public function getLineas(): array
    {
        return $this->lineas;
    }

    public function getIds(): array
    {
        return array_keys($this->lineas);
    }

    public function getLinea($id): Linea
    {
        return $this->lineas[$id];
    }
}

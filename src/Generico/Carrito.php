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
    // Este método recibe un parámetro $id y verifica si existe un artículo con ese id en la tabla "Articulo" utilizando el método "obtener()". 
    // Si no existe, lanza una excepción "ValueError" con el mensaje "El artículo no existe.". Si existe, verifica si ya existe una línea en el carrito con ese id, si es así,
    // llama al método "incrCantidad()" para incrementar la cantidad en 1, sino, crea una nueva línea con el artículo y la inserta en el carrito.

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

    // Este método recibe un parámetro $id y verifica si existe una línea en el carrito con ese id, si es así, llama al método "decrCantidad()" para decrementar la cantidad en 1, 
    // si la cantidad llega a 0, elimina la línea del carrito, si no existe, lanza una excepción "ValueError" con el mensaje "Artículo inexistente en el carrito".

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

    //Este método devuelve true si el carrito está vacío, false en caso contrario
    public function vacio(): bool
    {
        return empty($this->lineas);
    }

    // Este método devuelve todas las líneas en el carrito.
    public function getLineas(): array
    {
        return $this->lineas;
    }

    //Este método devuelve un array con los ids de todas las líneas en el carrito.
    public function getIds(): array
    {
        return array_keys($this->lineas);
    }

    // Este método recibe un parámetro $id y devuelve la línea en el carrito con ese id.
    public function getLinea($id): Linea
    {
        return $this->lineas[$id];
    }
}

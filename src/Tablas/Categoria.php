<?php

namespace App\Tablas;

use App\Tablas\Modelo;

class Categoria extends Modelo
{
    protected static string $tabla = 'categorias';

    public $id;
    public $categoria;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->categoria = $campos['categoria'];
    }

}

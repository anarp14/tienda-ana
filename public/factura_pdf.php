<?php
session_start();

use App\Tablas\Factura;

require '../vendor/autoload.php';

if (!($usuario = \App\Tablas\Usuario::logueado())) {
    return volver();
}

$id = obtener_get('id');


if (!isset($id)) {
    return volver();
}

$pdo = conectar();

$factura = Factura::obtener($id, $pdo);
$cupon = $factura->getCupon();

if (!isset($factura)) {
    return volver();
}

if ($factura->getUsuarioId() != $usuario->id) {
    return volver();
}

$filas_tabla = '';
$total = 0;

foreach ($factura->getLineas($pdo) as $linea) {
    $articulo = $linea->getArticulo(); //codigo
    $codigo = $articulo->getCodigo();  //precio
    $descripcion = $articulo->getDescripcion(); //descripcion
    $cantidad = $linea->getCantidad(); //cantidad
    $precio = $articulo->getDescuento() > 0 ? $articulo->getPrecio() - ($articulo->getPrecio() * ($articulo->getDescuento()/100)) : $articulo->getPrecio(); //precio del articulo 
    if(isset($cupon)) {
            $pdo = conectar();
            $sent = $pdo->prepare("SELECT descuento FROM cupones WHERE cupon = :cupon");
            $sent->execute([':cupon' => $cupon]); 
            foreach($sent as $cupo):
                $descuento = hh($cupo['descuento']);   
                $precio = $precio - ($precio * (hh($cupo['descuento']) / 100));
                $importe = $cantidad * $precio;
                $iva = round($precio*1.21, 2);
            endforeach; } else {
                $importe = $cantidad * $precio;
                $iva = round($precio*1.21, 2);

            }
    // $iva = 1.21*$importe;
    $total += round($importe*1.21, 2); //total de abajo
    
    $filas_tabla .= <<<EOF
        <tr>
            <td>$codigo</td>
            <td>$descripcion</td>
            <td>$cantidad</td>
            <td>$precio € </td>
            <td>$precio €</td>
            <td>$iva €</td>
        </tr>
    EOF;
}


if(!isset($cupon)) {
    $cupon = "Ninguno";
}

$res = <<<EOT
<p>Factura número: {$factura->id}</p>

<table border="1" class="font-sans mx-auto">
    <tr>
        <th>Código</th>
        <th>Descripción</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Importe</th>
        <th>Con IVA </th>
    </tr>
    <tbody>
        $filas_tabla
    </tbody>
</table>

<p>Total: $total € </p>
<p>Cupon utilizado: $cupon</p>

EOT;

// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf();

// Write some HTML code:
$mpdf->WriteHTML(file_get_contents('css/output.css'), \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($res, \Mpdf\HTMLParserMode::HTML_BODY);

// Output a PDF file directly to the browser
$mpdf->Output();

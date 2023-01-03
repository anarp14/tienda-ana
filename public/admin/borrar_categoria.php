<?php
session_start();

require '../../src/auxiliar.php';


$id = obtener_post('id');



// if (!comprobar_csrf()) {
//     return volver_admin();
// }

if (!isset($id)) {
    return volver_categorias();
}

// TODO: Validar id //DONE:
if(!ctype_digit($id) || !isset($id)){
    return volver_categorias();
} else {
    $pdo = conectar();
    $sent = $pdo->prepare("SELECT COUNT(categoria) FROM categorias WHERE id = :id AND id NOT in (SELECT categoria_id FROM articulos);");
    $sent->execute([':id' => $id]);
    if ($sent->fetchColumn() != 0) {
        $sent = $pdo->prepare("DELETE FROM categorias WHERE id = :id");
        $sent->execute([':id' => $id]);
        $_SESSION['exito'] = 'La categoria se ha borrado correctamente.';
    } else{
        $_SESSION['error'] = 'La categoria está vinculada a un artículo.';
    }
   
}

volver_categorias();



<?php

require_once __DIR__ . "/../app/Services/GrupoServices.php";

$service = new GrupoService();
$id = intval($_GET["id"] ?? 0);

if ($id > 0 && $service->eliminar($id)) {
    header("Location:/tarea_corta_2/index.php?pagina=grupos&mensaje=eliminado");
    exit;
}

header("Location:/tarea_corta_2/index.php?pagina=grupos&mensaje=error");
exit;

?>

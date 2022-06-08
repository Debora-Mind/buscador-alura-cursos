<?php

require_once 'vendor\autoload.php';
require_once 'src\Buscador.php';

use DeboraMind\BuscadorCursosAlura\Buscador;

$buscador = new Buscador('taqi', 'smartphones');

$lista = $buscador->getLista();

for ($i = 0; $i < count($lista); $i++) {
    $j = array_values($lista);
    $k = array_keys($lista);
    echo "$k[$i] => $j[$i]" . PHP_EOL;
}

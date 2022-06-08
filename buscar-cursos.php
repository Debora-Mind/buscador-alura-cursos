<?php

require_once 'vendor\autoload.php';

use DeboraMind\BuscadorCursosAlura\BuscadorCurso;

$buscador = new BuscadorCurso('cursos-online-programacao/php');

$buscador->busca();

$buscador->exibeCursos();

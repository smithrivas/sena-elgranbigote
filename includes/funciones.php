<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / normaliza el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa si el usuario está autenticado
function isAuth() : void{
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}
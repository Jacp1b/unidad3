<?php
declare(strict_types=1);
function verificarNumero(int $numero): void
{
    if ($numero % 2 === 0) {
        echo "El número $numero es par.\n";
    } else {
        echo "El número $numero es impar.\n";
    }
}
verificarNumero(4); // llamada reutilizable
verificarNumero(7); // llamada reutilizable
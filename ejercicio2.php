<?php
function sumarFuncion(int $a, int $b): void
{
    echo "La suma de $a y $b es: " . ($a + $b) . "\n";
}
sumarFuncion(5, 6); // llamada reutilizable
sumarFuncion(3, 7); // llamada reutilizable
sumarFuncion(1, 9); // llamada reutilizable

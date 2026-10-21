<?php
declare(strict_types=1);

function calcularIVA(float $precio, float $tasa = 0.19): float {
    return round($precio * (1 + $tasa), 2);
}

function formatearPrecio(float $valor): string {
    return '$' . number_format($valor, 0, ',', '.');

}

echo formatearPrecio(calcularIVA(50000)) . "\n";        // IVA del 19 %
echo formatearPrecio(calcularIVA(50000, 0.05)) . "\n";  // IVA del 5 %

function saludarCliente(string $nombre = "cliente"): string {
    return "Bienvenido, $nombre";
}
echo saludarCliente() . "\n";        // Bienvenido, cliente
echo saludarCliente("Jose") . "\n"; // Bienvenido, Jose
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

function aplicarDescuento(float $precio, float $descuento = 0.1): float {
    return round($precio * (1 - $descuento), 2);
}
function totalCarrito(float ...$precios): float {
    return array_sum($precios);
}

function descontarStock(int &$stock, int $cantidad): void {
    $stock -= $cantidad;
}

function promedioPrecios(float ...$precios): float {
    $total = array_sum($precios);
    $cantidad = count($precios);
    return $cantidad > 0 ? round($total / $cantidad, 2) : 0.0;
}
$stockCuadernos = 10;
descontarStock($stockCuadernos, 3);

echo aplicarDescuento(100000) . "\n";        // Descuento del 10 %

echo aplicarDescuento(100000, 0.2) . "\n";   // Descuento del 20 %

echo formatearPrecio(totalCarrito(15000, 22000, 8000)) . "\n";

echo "Quedan $stockCuadernos cuadernos\n";

echo "Promedio de precios: " . formatearPrecio(promedioPrecios(15000, 22000, 14500, 24000, 8000)) . "\n"; // Promedio de precios

$productos = [
    ['nombre' => 'Cuaderno', 'precio' => 8000,  'stock' => 20],
    ['nombre' => 'Lápiz',    'precio' => 1500,  'stock' => 0],
    ['nombre' => 'Mochila',  'precio' => 95000, 'stock' => 4],
];

$nombres = array_column($productos, 'nombre');
echo "Catálogo: " . implode(', ', $nombres) . "\n";
echo "Total de productos: " . count($productos) . "\n";

$disponibles = array_filter($productos, fn($p) => $p['stock'] > 0);
echo "Con stock: " . count($disponibles) . "\n";

$precios = array_column($productos, 'precio');
echo "El más caro cuesta " . formatearPrecio(max($precios)) . "\n";
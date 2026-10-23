<?php
declare(strict_types=1);

// --- PARTE 1: Funciones basicas ---
function calcularIVA(float $precio, float $tasa = 0.19): float {
    return round($precio * (1 + $tasa), 2);
}

function formatearPrecio(float $valor): string {
    return '$' . number_format($valor, 0, ',', '.');
}

function saludarCliente(string $nombre = "cliente"): string {
    return "Bienvenido a la tienda, $nombre";
}

// --- PARTE 2: Parametros (nombrados, variable, por referencia) ---
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
    if (count($precios) === 0) {
        throw new InvalidArgumentException('Debe proporcionar al menos un precio.');
    }

    $total = array_sum($precios);
    $cantidad = count($precios);
    return round($total / $cantidad, 2);
}

// --- RETO 3: Nueva Excepcion personalizada para precios invalidos ---
class PrecioInvalidoException extends Exception {
    public function __construct(string $producto, float $precio) {
        parent::__construct("El producto «{$producto}» tiene un precio invalido ($precio). Debe ser mayor a cero.");
    }
}

// Excepcion personalizada de la Parte 4 (Stock agotado)
class ProductoAgotadoException extends Exception {
    public function __construct(string $producto) {
        parent::__construct("No hay unidades suficientes de «{$producto}».");
    }
}

// --- PARTE 3: Catalogo y validacion inicial ---
$productos = [
    ['nombre' => 'Cuaderno', 'precio' => 8000,  'stock' => 20],
    ['nombre' => 'Lapiz',    'precio' => 1500,  'stock' => 0],
    ['nombre' => 'Mochila',  'precio' => 95000, 'stock' => 4],
];

// Validacion de precios (Reto 3)
foreach ($productos as $p) {
    if ($p['precio'] <= 0) {
        throw new PrecioInvalidoException($p['nombre'], $p['precio']);
    }
}

$nombres = array_map(
    static fn(string $nombre): string => strtoupper($nombre),
    array_column($productos, 'nombre')
);

// --- PARTE 4: Busqueda y compra con excepciones ---
function buscarProducto(array $productos, string $nombre): array {
    foreach ($productos as $p) {
        if ($p['nombre'] === $nombre) {
            return $p;
        }
    }
    throw new InvalidArgumentException("El producto «{$nombre}» no existe.");
}

// Reto 1 (Stock real con referencia &$productos) y Reto 2 (Descuento por volumen con argumentos nombrados)
function comprar(array &$productos, string $nombre, int $cantidad): float {
    if ($cantidad <= 0) {
        throw new RangeException("La cantidad debe ser mayor que cero.");
    }

    $encontrado = false;
    foreach ($productos as &$p) {
        if ($p['nombre'] === $nombre) {
            $encontrado = true;
            if ($p['stock'] < $cantidad) {
                throw new ProductoAgotadoException($nombre);
            }
            // Reto 1: Descontar stock real usando la funcion por referencia
            descontarStock($p['stock'], $cantidad);
            $subtotal = $p['precio'] * $cantidad;
            break;
        }
    }
    unset($p); // Romper la referencia del foreach

    if (!$encontrado) {
        throw new InvalidArgumentException("El producto «{$nombre}» no existe.");
    }

    // Reto 2: Si lleva 5 o mas unidades, aplicar 10% de descuento usando argumentos nombrados
    if ($cantidad >= 5) {
        $subtotal = aplicarDescuento(precio: $subtotal, descuento: 0.1);
    }

    return calcularIVA($subtotal);
}

// --- RETO 4: Funcion para generar recibo usando date(), implode() y str_pad() ---
function generarRecibo(array $lineas): string {
    $cabecera = "============================\n" .
                "      RECIBO DE COMPRA      \n" .
                " Fecha: " . date("Y-m-d H:i:s") . "\n" .
                "============================\n";
    $cuerpo = implode("\n", $lineas);
    $pie = "\n============================\n" .
           " ¡Gracias por su compra!   \n" .
           "============================\n";
    return $cabecera . $cuerpo . $pie;
}

// --- PRUEBAS DE LAS PARTES 1, 2 y 3 ---
echo formatearPrecio(calcularIVA(50000)) . "\n";        // IVA del 19 %
echo formatearPrecio(calcularIVA(50000, 0.05)) . "\n";  // IVA del 5 %

echo saludarCliente() . "\n";        // Bienvenido, cliente
echo saludarCliente("Jose") . "\n"; // Bienvenido, Jose

$stockCuadernos = 10;
descontarStock($stockCuadernos, 3);

echo aplicarDescuento(100000) . "\n";        // Descuento del 10 %
echo aplicarDescuento(100000, 0.2) . "\n";   // Descuento del 20 %

echo formatearPrecio(totalCarrito(15000, 22000, 8000)) . "\n";
echo "Quedan $stockCuadernos cuadernos (stock local de prueba)\n";

echo "Promedio de precios: " . formatearPrecio(promedioPrecios(15000, 22000, 14500, 24000, 8000)) . "\n";

try {
    promedioPrecios();
} catch (InvalidArgumentException $e) {
    echo "Error al calcular el promedio: " . $e->getMessage() . "\n";
}

echo "Catalogo: " . implode(', ', $nombres) . "\n";
echo "Total de productos: " . count($productos) . "\n";

$disponibles = array_filter($productos, fn($p) => $p['stock'] > 0);
echo "Con stock: " . count($disponibles) . "\n";

$preciosConIVA = array_map(
    fn(array $producto): float => calcularIVA($producto['precio']),
    $productos
);

echo "Precios con IVA: " . implode(', ', array_map('formatearPrecio', $preciosConIVA)) . "\n";
echo "El mas caro cuesta " . formatearPrecio(max($preciosConIVA)) . "\n\n";

// --- PARTE 5: Todo junto (Simulacion de pedidos y manejo de excepciones) ---
$pedidos = [
    ['Cuaderno', 2],
    ['Lapiz',    1],
    ['Borrador', 3],
    ['Mochila',  0],
    ['Mochila',  5], 
];

$ventaTotal = 0;
$lineasRecibo = [];

foreach ($pedidos as [$nombre, $cantidad]) {
    try {
        $valor = comprar($productos, $nombre, $cantidad);
        $ventaTotal += $valor;
        $lineasRecibo[] = str_pad("$cantidad x $nombre", 22, " ", STR_PAD_RIGHT) . ": " . formatearPrecio($valor);
        echo "OK  - $cantidad x $nombre: " . formatearPrecio($valor) . "\n";
    } catch (ProductoAgotadoException $e) {
        echo "SIN STOCK - " . $e->getMessage() . "\n";
    } catch (InvalidArgumentException | RangeException | PrecioInvalidoException $e) {
        echo "ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\nTotal vendido: " . formatearPrecio($ventaTotal) . "\n\n";

// Imprimir recibo formateado (Reto 4)
echo generarRecibo($lineasRecibo);


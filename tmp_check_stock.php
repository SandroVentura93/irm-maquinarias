<?php

use App\Models\Producto;

require __DIR__ . '/vendor/autoload.php';

// Inicializar la aplicación
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Consultar productos con stock bajo
$productosStockBajo = Producto::whereRaw('stock_actual <= LEAST(stock_minimo, 5)')
    ->where('activo', 1)
    ->get();

if ($productosStockBajo->isEmpty()) {
    echo "No hay productos con stock bajo.\n";
} else {
    echo "Productos con stock bajo:\n";
    foreach ($productosStockBajo as $producto) {
        echo "- {$producto->nombre} (Stock actual: {$producto->stock_actual}, Mínimo: {$producto->stock_minimo})\n";
    }
}
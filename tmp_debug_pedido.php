<?php
// tmp_debug_pedido.php - bootstrap Laravel and print latest Pedido as JSON
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Use the model
    $pedido = App\Models\Pedido::with('detalles.producto')->latest()->first();
    if (! $pedido) {
        echo "No Pedido found\n";
        exit(0);
    }
    echo json_encode($pedido->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

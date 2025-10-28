<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Venta #{{ $venta->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .ticket { width: 300px; margin: auto; }
        .header { text-align: center; margin-bottom: 10px; }
        .details, .products { width: 100%; margin-bottom: 10px; }
        .products th, .products td { padding: 2px 4px; border-bottom: 1px solid #eee; }
        .totals { text-align: right; margin-top: 10px; }
    </style>
</head>
<body>
<div class="ticket">
    <div class="header">
        <h2>Ticket de Venta</h2>
        <small>#{{ $venta->id }}</small>
        <div>{{ $venta->fecha->format('d/m/Y H:i') }}</div>
    </div>
    <div class="details">
        <strong>Cliente:</strong> {{ $venta->cliente->nombre ?? '-' }}<br>
        <strong>Usuario:</strong> {{ $venta->usuario->name ?? '-' }}<br>
        <strong>Tipo:</strong> {{ ucfirst($venta->tipo_venta) }}<br>
        <strong>Comprobante:</strong> {{ ucfirst($venta->tipo_comprobante) }}<br>
        <strong>Serie:</strong> {{ $venta->serie }}-{{ $venta->correlativo }}<br>
        <strong>Moneda:</strong> {{ $venta->moneda->nombre ?? '-' }}<br>
    </div>
    <table class="products">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant</th>
                <th>P.U.</th>
                <th>Desc</th>
                <th>Subt</th>
            </tr>
        </thead>
        <tbody>
        @foreach($venta->detalles as $d)
            <tr>
                <td>{{ $d->producto->descripcion ?? '-' }}</td>
                <td>{{ number_format($d->cantidad, 2) }}</td>
                <td>{{ number_format($d->precio_unitario, 2) }}</td>
                <td>{{ number_format($d->descuento, 2) }}</td>
                <td>{{ number_format($d->subtotal, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="totals">
        <strong>Subtotal:</strong> S/ {{ number_format($venta->subtotal, 2) }}<br>
        <strong>Descuento:</strong> S/ {{ number_format($venta->descuento_total, 2) }}<br>
        <strong>Recargo:</strong> S/ {{ number_format($venta->recargo_total, 2) }}<br>
        <strong>Total:</strong> <span style="font-size:16px">S/ {{ number_format($venta->total, 2) }}</span>
    </div>
    <div class="footer" style="text-align:center; margin-top:10px;">
        <small>Gracias por su compra</small>
    </div>
</div>
</body>
</html>

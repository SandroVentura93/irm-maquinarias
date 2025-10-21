-- Vista: v_alerta_stock_bajo
CREATE OR REPLACE VIEW v_alerta_stock_bajo AS
SELECT id, codigo, nombre, stock_actual, stock_minimo
FROM productos
WHERE activo = 1 AND stock_actual <= stock_minimo;

-- Vista: v_caja_resumen
CREATE OR REPLACE VIEW v_caja_resumen AS
SELECT v.id AS venta_id, v.fecha, v.total, v.estado, c.nombre AS cliente
FROM ventas v
LEFT JOIN clientes c ON v.cliente_id = c.id
WHERE v.estado IN ('pendiente', 'cancelado', 'anulado');

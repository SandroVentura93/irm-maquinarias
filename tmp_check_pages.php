<?php
$pages = ['/clientes','/proveedores','/usuarios','/ventas'];
foreach ($pages as $p) {
    $url = 'http://127.0.0.1:8000' . $p;
    $opts = ['http' => ['method' => 'GET', 'ignore_errors' => true]];
    $context = stream_context_create($opts);
    $body = @file_get_contents($url, false, $context);
    $status = "N/A";
    if (isset($http_response_header) && is_array($http_response_header)) {
        foreach ($http_response_header as $h) {
            if (preg_match('#^HTTP/.*\s(\d{3})#', $h, $m)) { $status = $m[1]; break; }
        }
    }
    echo "$status $url\n";
}

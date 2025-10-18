<?php
$base = 'http://127.0.0.1:8000';
$jar = sys_get_temp_dir() . '/php_cookie_test.txt';
$opts = ['http' => ['header' => "Cookie: ", 'ignore_errors' => true]];

function get($url, &$opts, &$jar) {
    $ctx = stream_context_create($opts['http']);
    $opts2 = $opts;
    $opts2['http']['header'] = isset($opts['http']['header']) ? $opts['http']['header'] : '';
    $s = @file_get_contents($url, false, $ctx);
    $status = 'N/A';
    global $http_response_header;
    if (isset($http_response_header)) {
        foreach ($http_response_header as $h) { if (preg_match('#^HTTP/.*\s(\d{3})#', $h, $m)) { $status = $m[1]; break; } }
    }
    return [$status, $s, $http_response_header ?? []];
}

// Do dev login
list($st,) = get($base . '/_dev_login', $opts, $jar);
echo "_dev_login: $st\n";
$pages = ['/clientes','/proveedores','/usuarios','/ventas'];
foreach ($pages as $p) {
    list($st,) = get($base . $p, $opts, $jar);
    echo "$st $base$p\n";
}

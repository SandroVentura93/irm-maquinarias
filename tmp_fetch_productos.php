<?php
$base = 'http://127.0.0.1:8000';
function fetch($url) {
    $s = @file_get_contents($url);
    if ($s === false) return null;
    return substr($s, 0, 200);
}
// Try dev login (only works if APP_DEBUG=true)
fetch($base . '/_dev_login');
$body = fetch($base . '/productos');
if ($body === null) {
    echo "ERROR fetching /productos\n";
} else {
    echo "OK: /productos loaded (snippet):\n" . $body . "\n";
}

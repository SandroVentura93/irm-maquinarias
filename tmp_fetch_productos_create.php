<?php
$base = 'http://127.0.0.1:8000';
function fetch($url) {
    $s = @file_get_contents($url);
    if ($s === false) return null;
    return substr($s, 0, 300);
}
fetch($base . '/_dev_login');
$body = fetch($base . '/productos/create');
if ($body === null) {
    echo "ERROR fetching /productos/create\n";
} else {
    echo "OK: /productos/create loaded (snippet):\n" . $body . "\n";
}

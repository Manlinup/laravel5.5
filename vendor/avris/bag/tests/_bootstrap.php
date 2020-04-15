<?php
foreach (['', '/..', '/../..', '/../../..', '/../../../..'] as $dir) {
    if (file_exists($autoloader = __DIR__ . $dir . '/vendor/autoload.php')) {
        require_once $autoloader;
        break;
    }
}

foreach (glob(__DIR__ . '/_help/*.php') as $file) {
    require_once $file;
}

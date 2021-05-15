<?php 
$configDir = dirname(__FILE__);
$baseDir = dirname($configDir);
$vendorDir = $baseDir . '/vendor/';
return [
    'Tickets' => $baseDir . '/src/Tickets/src',
    'Gac' => $vendorDir . '/Gac/src',
];

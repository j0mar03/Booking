<?php
// Database configuration for InfinityFree
$db_config = [
    'hostname' => 'sql.infinityfree.com',  // You'll get this from InfinityFree
    'username' => 'if0_39013535,           // You'll get this from InfinityFree
    'password' => 'Gre8DubeQv',        // You'll set this in InfinityFree
    'database' => 'if0_39013535_csbook',          // You'll get this from InfinityFree
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
];

// Save this configuration
$config_file = __DIR__ . '/crbs-core/application/config/database.php';
$config_content = file_get_contents($config_file);
$config_content = preg_replace(
    '/\$db\[\'default\'\]\s*=\s*array\s*\([^)]*\);/s',
    '$db[\'default\'] = ' . var_export($db_config, true) . ';',
    $config_content
);
file_put_contents($config_file, $config_content);

echo "Database configuration updated successfully!\n"; 
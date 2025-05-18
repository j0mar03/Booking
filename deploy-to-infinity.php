<?php
// Deployment script for InfinityFree

// Configuration
$config = [
    'local_path' => __DIR__,
    'exclude_files' => [
        '.git',
        '.gitignore',
        'deploy.php',
        'deploy-to-infinity.php',
        'development.php',
        'README.md',
        '*.log',
        'vendor',
        'node_modules',
        'uploads/*',  // Exclude uploaded files
    ],
    'infinity_config' => [
        'hostname' => 'sql100.infinityfree.com',
        'username' => 'if0_39013535',
        'password' => 'Gre8DubeQv',
        'database' => 'if0_39013535_csbook',
    ]
];

// Function to create deployment package
function createDeploymentPackage($config) {
    $zip = new ZipArchive();
    $zipName = 'deployment_' . date('Y-m-d_H-i-s') . '.zip';
    
    if ($zip->open($zipName, ZipArchive::CREATE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($config['local_path']),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($config['local_path']) + 1);
                
                // Check if file should be excluded
                $exclude = false;
                foreach ($config['exclude_files'] as $pattern) {
                    if (fnmatch($pattern, $relativePath)) {
                        $exclude = true;
                        break;
                    }
                }
                
                if (!$exclude) {
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        
        $zip->close();
        return $zipName;
    }
    
    return false;
}

// Function to update database configuration
function updateDatabaseConfig($config) {
    $dbConfig = [
        'hostname' => $config['infinity_config']['hostname'],
        'username' => $config['infinity_config']['username'],
        'password' => $config['infinity_config']['password'],
        'database' => $config['infinity_config']['database'],
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

    $configFile = __DIR__ . '/crbs-core/application/config/database.php';
    $content = file_get_contents($configFile);
    $content = preg_replace(
        '/\$db\[\'default\'\]\s*=\s*array\s*\([^)]*\);/s',
        '$db[\'default\'] = ' . var_export($dbConfig, true) . ';',
        $content
    );
    file_put_contents($configFile, $content);
}

// Main deployment process
echo "Starting deployment process...\n";

// 1. Create deployment package
echo "Creating deployment package...\n";
$zipFile = createDeploymentPackage($config);
if (!$zipFile) {
    die("Failed to create deployment package\n");
}
echo "Package created: $zipFile\n";

// 2. Update database configuration
echo "Updating database configuration...\n";
updateDatabaseConfig($config);
echo "Database configuration updated\n";

echo "\nDeployment package is ready!\n";
echo "Please upload $zipFile to your InfinityFree hosting using their File Manager.\n";
echo "After uploading, extract the files in the htdocs directory.\n";
echo "Don't forget to backup your database before deploying!\n";

// InfinityFree Deployment Script
class InfinityFreeDeployer {
    private $config;
    private $sourcePath;
    private $targetPath;
    private $tempDir;
    
    public function __construct() {
        $this->config = [
            'hostname' => 'sql.infinityfree.com',
            'username' => 'if0_39013535',
            'password' => 'Gre8DubeQv',
            'database' => 'if0_39013535_csbook',
            'ftp_host' => 'ftpupload.net', // InfinityFree FTP host
            'ftp_user' => 'if0_39013535', // Your InfinityFree FTP username
            'ftp_pass' => 'Gre8DubeQv', // Your InfinityFree FTP password
            'ftp_path' => '' // Empty string for root directory
        ];
        
        $this->sourcePath = __DIR__;
        $this->targetPath = $this->config['ftp_path'];
        $this->tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'deploy_' . time();
    }
    
    public function deploy() {
        echo "Starting deployment to InfinityFree...\n";
        
        // Update database configuration
        $this->updateDatabaseConfig();
        echo "Database configuration updated.\n";

        // Prepare files
        $this->prepareFiles();
        echo "Files prepared for deployment.\n";

        // Connect to FTP
        echo "Connecting to FTP server...\n";
        $conn = ftp_connect($this->config['ftp_host']);
        if (!$conn) {
            echo "Error: Could not connect to FTP server\n";
            return false;
        }

        // Login to FTP
        echo "Logging in to FTP server...\n";
        if (!ftp_login($conn, $this->config['ftp_user'], $this->config['ftp_pass'])) {
            echo "Error: Could not login to FTP server\n";
            ftp_close($conn);
            return false;
        }

        // Enable passive mode
        ftp_pasv($conn, true);
        echo "FTP passive mode enabled.\n";

        // Upload files
        echo "Starting file upload...\n";
        $uploadResult = $this->recursiveUpload($conn, $this->tempDir, $this->targetPath);
        
        // Close FTP connection
        ftp_close($conn);
        
        if ($uploadResult) {
            echo "Deployment completed successfully!\n";
        } else {
            echo "Deployment completed with some errors. Please check the logs above.\n";
        }

        // Cleanup
        $this->cleanup();
        echo "Cleanup completed.\n";
    }
    
    private function updateDatabaseConfig() {
        $dbConfig = [
            'hostname' => $this->config['hostname'],
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'database' => $this->config['database'],
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
        
        $configFile = $this->sourcePath . '/crbs-core/application/config/database.php';
        if (!file_exists($configFile)) {
            throw new Exception("Database configuration file not found at: " . $configFile);
        }
        
        $configContent = file_get_contents($configFile);
        $configContent = preg_replace(
            '/\$db\[\'default\'\]\s*=\s*array\s*\([^)]*\);/s',
            '$db[\'default\'] = ' . var_export($dbConfig, true) . ';',
            $configContent
        );
        file_put_contents($configFile, $configContent);
        
        echo "Database configuration updated.\n";
    }
    
    private function prepareFiles() {
        // Create a temporary directory for deployment
        if (!is_dir($this->tempDir)) {
            if (!mkdir($this->tempDir, 0777, true)) {
                throw new Exception("Failed to create temporary directory: " . $this->tempDir);
            }
        }
        
        // Copy files to temp directory
        $this->recursiveCopy($this->sourcePath, $this->tempDir);
        
        // Remove unnecessary files
        $this->removeUnnecessaryFiles($this->tempDir);
        
        echo "Files prepared for deployment.\n";
    }
    
    private function recursiveCopy($src, $dst) {
        if (!is_dir($src)) {
            throw new Exception("Source directory does not exist: " . $src);
        }
        
        if (!is_dir($dst)) {
            if (!mkdir($dst, 0777, true)) {
                throw new Exception("Failed to create directory: " . $dst);
            }
        }
        
        $dir = opendir($src);
        if (!$dir) {
            throw new Exception("Failed to open directory: " . $src);
        }
        
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $srcFile = $src . DIRECTORY_SEPARATOR . $file;
                $dstFile = $dst . DIRECTORY_SEPARATOR . $file;
                
                if (is_dir($srcFile)) {
                    $this->recursiveCopy($srcFile, $dstFile);
                } else {
                    if (!copy($srcFile, $dstFile)) {
                        throw new Exception("Failed to copy file: " . $srcFile);
                    }
                }
            }
        }
        closedir($dir);
    }
    
    private function recursiveUpload($conn, $src, $dst) {
        $dir = opendir($src);
        if (!$dir) {
            echo "Error: Cannot open source directory: $src\n";
            return false;
        }

        // Clean up destination path
        $dst = str_replace(['\\', '//'], '/', $dst); // Convert backslashes to forward slashes and remove double slashes
        $dst = trim($dst, '/'); // Remove leading and trailing slashes

        // Skip hidden files and directories
        if (strpos(basename($src), '.') === 0) {
            return true;
        }

        // Try to change to the directory, create it if it doesn't exist
        if (!empty($dst) && !@ftp_chdir($conn, $dst)) {
            echo "Creating directory: $dst\n";
            if (!@ftp_mkdir($conn, $dst)) {
                echo "Error: Failed to create directory: $dst\n";
                return false;
            }
        }

        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $srcFile = $src . DIRECTORY_SEPARATOR . $file;
                $dstFile = empty($dst) ? $file : $dst . '/' . $file; // Use forward slash for remote path
                $dstFile = str_replace(['\\', '//'], '/', $dstFile); // Clean up path
                $dstFile = trim($dstFile, '/'); // Remove leading and trailing slashes

                // Skip hidden files and directories
                if (strpos($file, '.') === 0) {
                    continue;
                }

                if (is_dir($srcFile)) {
                    echo "Creating directory: $dstFile\n";
                    if (!@ftp_mkdir($conn, $dstFile)) {
                        echo "Error: Failed to create directory: $dstFile\n";
                        continue;
                    }
                    $this->recursiveUpload($conn, $srcFile, $dstFile);
                } else {
                    echo "Uploading file: $dstFile\n";
                    if (!@ftp_put($conn, $dstFile, $srcFile, FTP_BINARY)) {
                        echo "Error: Failed to upload file: $dstFile\n";
                        continue;
                    }
                }
            }
        }
        closedir($dir);
        return true;
    }
    
    private function removeUnnecessaryFiles($dir) {
        $filesToRemove = [
            '.git',
            '.github',
            'deploy.php',
            'deploy-config.php',
            'deploy-to-infinity.php',
            'README.md',
            'LICENSE.txt'
        ];
        
        foreach ($filesToRemove as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->recursiveRemove($path);
            } elseif (file_exists($path)) {
                unlink($path);
            }
        }
    }
    
    private function recursiveRemove($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                $path = $dir . DIRECTORY_SEPARATOR . $object;
                if (is_dir($path)) {
                    $this->recursiveRemove($path);
                } else {
                    unlink($path);
                }
            }
        }
        rmdir($dir);
    }

    private function cleanup() {
        // Clean up temp directory
        if (is_dir($this->tempDir)) {
            $this->recursiveRemove($this->tempDir);
        }
    }
}

// Run the deployment
$deployer = new InfinityFreeDeployer();
$deployer->deploy(); 
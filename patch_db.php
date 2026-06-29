<?php
$dbPath = __DIR__ . '/database/cms.sqlite';
if (!file_exists($dbPath)) {
    echo "DB not found at $dbPath\n";
    exit(0);
}

$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $db->exec("ALTER TABLE posts ADD COLUMN parent_id INTEGER DEFAULT 0");
    echo "Added parent_id\n";
} catch (Exception $e) {
    echo "parent_id already exists or error: " . $e->getMessage() . "\n";
}

try {
    $db->exec("ALTER TABLE posts ADD COLUMN menu_order INTEGER DEFAULT 0");
    echo "Added menu_order\n";
} catch (Exception $e) {
    echo "menu_order already exists or error: " . $e->getMessage() . "\n";
}

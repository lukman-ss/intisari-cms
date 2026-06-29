<?php
declare(strict_types=1);
return new class {
    public function up(): string {
        return "CREATE TABLE IF NOT EXISTS permissions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(50) NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS permission_role (
            permission_id INTEGER NOT NULL,
            role_id INTEGER NOT NULL,
            PRIMARY KEY (permission_id, role_id)
        );";
    }
};

<?php
declare(strict_types=1);
return new class {
    public function up(): string {
        return "CREATE TABLE IF NOT EXISTS terms (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(200) NOT NULL,
            slug VARCHAR(200) NOT NULL UNIQUE,
            taxonomy VARCHAR(32) NOT NULL,
            description TEXT,
            parent_id INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );";
    }
};

<?php
declare(strict_types=1);
return new class {
    public function up(): string {
        return "CREATE TABLE IF NOT EXISTS options (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            option_name VARCHAR(191) NOT NULL UNIQUE,
            option_value TEXT,
            autoload BOOLEAN DEFAULT 1
        );";
    }
};

<?php
declare(strict_types=1);

return new class {
    public function up(): string {
        return "
            CREATE TABLE IF NOT EXISTS redirects (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                source_url TEXT NOT NULL,
                target_url TEXT NOT NULL,
                type INTEGER DEFAULT 301,
                hits INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE INDEX IF NOT EXISTS idx_redirects_source ON redirects(source_url);
        ";
    }
};

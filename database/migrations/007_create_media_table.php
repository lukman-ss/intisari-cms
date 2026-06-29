<?php
declare(strict_types=1);
return new class {
    public function up(): string {
        return "CREATE TABLE IF NOT EXISTS media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            filename VARCHAR(255) NOT NULL,
            mime_type VARCHAR(100),
            size INTEGER,
            metadata TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );";
    }
};

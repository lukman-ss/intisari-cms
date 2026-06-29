<?php
declare(strict_types=1);
return new class {
    public function up(): string {
        return "CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            user_id INTEGER DEFAULT 0,
            author_name VARCHAR(255),
            author_email VARCHAR(255),
            content TEXT NOT NULL,
            status VARCHAR(20) DEFAULT 'approved',
            parent_id INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );";
    }
};

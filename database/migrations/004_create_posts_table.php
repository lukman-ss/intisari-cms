<?php
declare(strict_types=1);
return new class {
    public function up(): string {
        return "CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            author_id INTEGER NOT NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content TEXT,
            excerpt TEXT,
            status VARCHAR(20) DEFAULT 'draft',
            type VARCHAR(20) DEFAULT 'post',
            parent_id INTEGER DEFAULT 0,
            menu_order INTEGER DEFAULT 0,
            comment_status VARCHAR(20) DEFAULT 'open',
            published_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );";
    }
};

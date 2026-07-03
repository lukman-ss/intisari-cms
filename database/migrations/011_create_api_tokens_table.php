<?php

declare(strict_types=1);

return new class {
    public function up(\PDO $db): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS api_tokens (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            name VARCHAR(255) NOT NULL,
            token_hash VARCHAR(255) NOT NULL,
            last_used_at DATETIME NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        $db->exec($sql);
    }

    public function down(\PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS api_tokens");
    }
};

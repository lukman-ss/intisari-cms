<?php
declare(strict_types=1);
return new class {
    public function up(): string {
        return "CREATE TABLE IF NOT EXISTS term_relationships (
            object_id INTEGER NOT NULL,
            term_id INTEGER NOT NULL,
            PRIMARY KEY (object_id, term_id)
        );";
    }
};

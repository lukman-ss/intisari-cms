<?php
declare(strict_types=1);

return new class {
    public function up(): string {
        return "ALTER TABLE posts ADD COLUMN featured_image_id INTEGER DEFAULT NULL;";
    }
};

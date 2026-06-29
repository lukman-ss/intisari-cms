<?php

declare(strict_types=1);

use Lukman\Database\Schema\SchemaBuilder;

return new class {
    public function up(SchemaBuilder $schema): void
    {
        if ($schema->hasTable('media')) {
            return;
        }

        $schema->create('media', function ($table): void {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->string('mime_type');
            $table->integer('size');
            $table->string('created_at')->nullable()->default(null);
            $table->string('updated_at')->nullable()->default(null);
        });
    }
};

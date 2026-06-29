<?php

declare(strict_types=1);

use Lukman\Database\Schema\SchemaBuilder;

return new class {
    public function up(SchemaBuilder $schema): void
    {
        if ($schema->hasTable('roles')) {
            return;
        }

        $schema->create('roles', function ($table): void {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('created_at')->nullable()->default(null);
            $table->string('updated_at')->nullable()->default(null);
        });
    }
};

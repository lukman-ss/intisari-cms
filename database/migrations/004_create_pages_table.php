<?php

declare(strict_types=1);

use Lukman\Database\Schema\SchemaBuilder;

return new class {
    public function up(SchemaBuilder $schema): void
    {
        if ($schema->hasTable('pages')) {
            return;
        }

        $schema->create('pages', function ($table): void {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable()->default(null);
            $table->string('status')->default('draft');
            $table->string('created_at')->nullable()->default(null);
            $table->string('updated_at')->nullable()->default(null);
        });
    }
};

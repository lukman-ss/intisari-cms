<?php

declare(strict_types=1);

use Lukman\Database\Schema\SchemaBuilder;

return new class {
    public function up(SchemaBuilder $schema): void
    {
        if ($schema->hasTable('settings')) {
            return;
        }

        $schema->create('settings', function ($table): void {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable()->default(null);
            $table->string('created_at')->nullable()->default(null);
            $table->string('updated_at')->nullable()->default(null);
        });
    }
};

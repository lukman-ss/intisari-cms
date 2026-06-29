<?php

declare(strict_types=1);

use Lukman\Database\Schema\SchemaBuilder;

return new class {
    public function up(SchemaBuilder $schema): void
    {
        if ($schema->hasTable('users')) {
            return;
        }

        $schema->create('users', function ($table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('remember_token')->nullable()->default(null);
            $table->string('status')->default('active');
            $table->string('created_at')->nullable()->default(null);
            $table->string('updated_at')->nullable()->default(null);
        });
    }
};

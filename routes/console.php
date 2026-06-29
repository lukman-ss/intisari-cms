<?php

declare(strict_types=1);

/** @var \Intisari\Application $app */
use Lukman\Console\Input;
use Lukman\Console\Output;
use App\Database\ConnectionFactory;
use App\Database\MigrationRunner;
use App\Database\SeederRunner;

$app->command('about', function (Input $input, Output $output) {
    $output->writeln('Intisari CMS - Console Application');
    return 0;
});

$app->command('env', function (Input $input, Output $output) {
    $output->writeln('Environment: local');
    return 0;
});

$app->command('serve', function (Input $input, Output $output) {
    $output->writeln('Starting PHP development server...');
    return 0;
});

$app->command('test', function (Input $input, Output $output) {
    $output->writeln('Running tests...');
    return 0;
});

$app->command('migrate', function (Input $input, Output $output) {
    $pdo = ConnectionFactory::make();
    $runner = new MigrationRunner($pdo);
    $executed = $runner->run(app()->basePath('database/migrations'));
    
    if (empty($executed)) {
        $output->writeln('Nothing to migrate.');
    } else {
        foreach ($executed as $mig) {
            $output->writeln("Migrated: $mig");
        }
    }
    return 0;
});

$app->command('migrate:fresh', function (Input $input, Output $output) {
    global $argv;
    $force = in_array('--force', $argv, true);
    
    if (!$force) {
        $output->writeln('Are you sure you want to drop all tables? Use --force to proceed.');
        return 1;
    }
    
    $pdo = ConnectionFactory::make();
    $runner = new MigrationRunner($pdo);
    $runner->fresh(app()->basePath('database/migrations'));
    
    $output->writeln('Dropped all tables successfully.');
    $output->writeln('Run migrate to re-run migrations.');
    
    return 0;
});

$app->command('db:seed', function (Input $input, Output $output) {
    $pdo = ConnectionFactory::make();
    $runner = new SeederRunner($pdo);
    $executed = $runner->run(app()->basePath('database/seeders'));
    
    if (empty($executed)) {
        $output->writeln('Nothing to seed.');
    } else {
        foreach ($executed as $seed) {
            $output->writeln("Seeded: $seed");
        }
    }
    return 0;
});

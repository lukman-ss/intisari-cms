<?php

declare(strict_types=1);

namespace Database\Seeders;

use Lukman\Database\Connection;
use Lukman\Database\QueryBuilder;

class AdminSeeder
{
    public function run(Connection $db): void
    {
        $qb = new QueryBuilder($db);
        
        $admin = $qb->table('users')->where('email', 'admin@example.com')->first();
        
        if ($admin === null) {
            $qb->table('users')->insert([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            echo "Admin user created (admin@example.com / password).\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
}

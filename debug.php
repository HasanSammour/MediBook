<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "\n";
echo "========================================\n";
echo "     MEDIBOOK - USER DEBUG TOOL\n";
echo "========================================\n\n";

// Get email from command line or prompt
if (isset($argv[1])) {
    $email = $argv[1];
} else {
    echo "Enter email address to check: ";
    $email = trim(fgets(STDIN));
}

if (empty($email)) {
    echo "❌ No email provided. Exiting...\n";
    exit(1);
}

echo "\n🔍 Searching for: " . $email . "\n\n";

// Search in all users (including soft deleted)
$user = User::withTrashed()->where('email', $email)->first();

if ($user) {
    echo "✅ USER FOUND!\n";
    echo "----------------------------------------\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Phone: " . ($user->phone ?? 'Not set') . "\n";
    echo "Role: " . ($user->roles->first()->name ?? 'No role assigned') . "\n";
    echo "Status: " . ($user->is_active ? 'Active' : 'Inactive') . "\n";
    echo "Deleted At: " . ($user->deleted_at ?? 'Not deleted (active)') . "\n";
    echo "Created At: " . $user->created_at . "\n";
    echo "----------------------------------------\n";
    
    if ($user->deleted_at) {
        echo "\n⚠️  This user is SOFT DELETED!\n";
        echo "   To restore, run: php artisan tinker\n";
        echo "   \$user = App\Models\User::withTrashed()->where('email', '{$email}')->first();\n";
        echo "   \$user->restore();\n";
    }
} else {
    echo "❌ USER NOT FOUND\n";
    echo "----------------------------------------\n";
    echo "No user with email '{$email}' exists in the database\n";
    echo "(including soft-deleted records).\n";
    echo "----------------------------------------\n";
    
    // Show recent users to help debug
    echo "\n📋 Recent users in database (last 5):\n";
    $recentUsers = User::withTrashed()->orderBy('id', 'desc')->limit(5)->get();
    if ($recentUsers->count() > 0) {
        foreach ($recentUsers as $u) {
            $deleted = $u->deleted_at ? ' [DELETED]' : '';
            echo "   - " . $u->email . $deleted . "\n";
        }
    } else {
        echo "   No users found in database.\n";
    }
}

// Additional statistics
echo "\n📊 Database Statistics:\n";
echo "----------------------------------------\n";
echo "Total Users (including soft-deleted): " . User::withTrashed()->count() . "\n";
echo "Active Users: " . User::count() . "\n";
echo "Soft-deleted Users: " . User::onlyTrashed()->count() . "\n";
echo "========================================\n\n";
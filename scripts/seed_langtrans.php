<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Seed main translations from LangTrans class
try {
    \App\Classes\LangTrans::seedMainTranslations();
    echo "LangTrans seeding completed.\n";
} catch (\Exception $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}

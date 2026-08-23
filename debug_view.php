<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    view("public.show", ["content" => \App\Models\Content::factory()->create()])->render();
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    if (method_exists($e, "getFile")) {
        echo "FILE: " . $e->getFile() . "\n";
        echo "LINE: " . $e->getLine() . "\n";
        if (str_contains($e->getFile(), "storage\framework\views") || str_contains($e->getFile(), "storage/framework/views")) {
            echo "COMPILED CONTENT:\n";
            echo file_get_contents($e->getFile());
        }
    }
}

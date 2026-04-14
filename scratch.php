<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sp = App\Models\SimplePurchase::find(9);
if (!$sp) die("Not found\n");
try {
    $resp = app(App\Http\Controllers\SimplePurchaseController::class)->midtransCharge($sp);
    echo "RESPONSE:\n";
    echo $resp->getContent() . "\n";
} catch (\Exception $e) {
    echo "EXCEPTION:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

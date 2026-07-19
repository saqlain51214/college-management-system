<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\Illuminate\Support\Facades\Auth::login(\App\Models\User::first());
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$fails = []; $ok = 0;
foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
    if (! in_array('GET', $route->methods())) continue;
    $uri = $route->uri();
    if (str_contains($uri, '{') || !str_starts_with($uri, 'admin')) continue;
    if (str_contains($uri, 'livewire') || str_contains($uri,'logout')) continue;
    try {
        $res = $kernel->handle(\Illuminate\Http\Request::create('/'.$uri, 'GET'));
        $code = $res->getStatusCode();
        if ($code >= 500) $fails[] = "$uri => $code"; else $ok++;
    } catch (\Throwable $e) { $fails[] = "$uri => ".substr($e->getMessage(),0,90); }
}
echo "ADMIN pages OK: $ok | FAILURES: ".count($fails)."\n";
foreach ($fails as $f) echo "  X $f\n";

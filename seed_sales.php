<?php

use App\Models\Ventas;
use App\Models\Productos;
use App\Models\Usuarios;
use Carbon\Carbon;

// Ensure we have products and users
$products = Productos::where('estado', 'Activo')->where('stock', '>', 0)->get();
$users = Usuarios::where('estado', 'Activo')->get();

if ($products->isEmpty()) {
    echo "Error: No active products found. Create some products first.\n";
    exit(1);
}

if ($users->isEmpty()) {
    echo "Error: No active users found. Create some users first.\n";
    exit(1);
}

echo "Seeding 15 dummy sales...\n";

for ($i = 0; $i < 15; $i++) {
    $product = $products->random();
    $user = $users->random();
    
    $qty = rand(1, min(5, $product->stock)); // Avoid overselling
    $totalPrice = $product->precio * $qty;
    $date = Carbon::now()->subDays(rand(0, 30)); // Random date in last 30 days

    Ventas::create([
        'cliente' => $user->nombres . ' ' . $user->apellidos,
        'vendedor' => 'Admin Sistema', // Dummy vendor
        'producto_id' => $product->id,
        'cantidad' => $qty,
        'precio' => $totalPrice,
        'fecha_venta' => $date,
        'pagado' => true, // Assuming paid
        'fecha_pago' => $date,
    ]);

    // Decrement stock
    $product->decrement('stock', $qty);
    
    echo "Created Sale: {$qty}x {$product->nombre} for {$user->nombres} ($$totalPrice)\n";
}

echo "Done.\n";

<?php

// Script pour compléter toutes les migrations RAPIDOTEX importantes

$basePath = __DIR__ . '/database/migrations/';

// Définition des migrations avec leur contenu
$migrations = [
    'cart_items' => "
            \$table->id();
            \$table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->integer('qty');
            \$table->decimal('unit_price', 10, 2);
            \$table->decimal('total_price', 10, 2);
            \$table->json('options')->nullable();
            \$table->timestamps();
            
            \$table->index('cart_id');
            \$table->index('product_id');",
    
    'quotes' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->enum('status', ['pending', 'accepted', 'rejected']);
            \$table->decimal('total_amount', 12, 2);
            \$table->timestamps();
            
            \$table->index('user_id');
            \$table->index('status');",
    
    'quote_items' => "
            \$table->id();
            \$table->foreignId('quote_id')->constrained('quotes')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->integer('qty');
            \$table->decimal('unit_price', 10, 2);
            \$table->decimal('total_price', 10, 2);
            \$table->json('options')->nullable();
            \$table->timestamps();
            
            \$table->index('quote_id');
            \$table->index('product_id');",
    
    'orders' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->foreignId('quote_id')->nullable()->constrained('quotes')->onDelete('set null');
            \$table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            \$table->decimal('subtotal', 12, 2);
            \$table->decimal('tax', 12, 2)->default(0);
            \$table->decimal('shipping_fee', 12, 2)->default(0);
            \$table->decimal('total', 12, 2);
            \$table->string('payment_method')->nullable();
            \$table->string('transaction_id')->nullable();
            \$table->timestamps();
            
            \$table->index('user_id');
            \$table->index('status');
            \$table->index('quote_id');",
    
    'order_items' => "
            \$table->id();
            \$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->integer('qty');
            \$table->decimal('unit_price', 10, 2);
            \$table->decimal('total_price', 10, 2);
            \$table->json('options')->nullable();
            \$table->timestamps();
            
            \$table->index('order_id');
            \$table->index('product_id');",
    
    'file_uploads' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->string('path');
            \$table->enum('type', ['artwork', 'bat', 'template', 'other']);
            \$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            \$table->timestamps();
            
            \$table->index('user_id');
            \$table->index('type');
            \$table->index('status');",
    
    'payments' => "
            \$table->id();
            \$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            \$table->string('provider');
            \$table->string('provider_payment_id');
            \$table->decimal('amount', 12, 2);
            \$table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            \$table->timestamp('paid_at')->nullable();
            \$table->timestamps();
            
            \$table->index('order_id');
            \$table->index('status');",
    
    'settings' => "
            \$table->string('key')->primary();
            \$table->text('value');
            \$table->boolean('autoload')->default(false);",
    
    'notifications' => "
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->string('type');
            \$table->json('data');
            \$table->timestamp('read_at')->nullable();
            \$table->timestamps();
            
            \$table->index('user_id');
            \$table->index('type');
            \$table->index('read_at');"
];

// Fonction pour trouver le fichier de migration
function findMigrationFile($tableName, $basePath) {
    $files = glob($basePath . "*create_{$tableName}_table.php");
    return !empty($files) ? basename($files[0]) : null;
}

// Traitement de chaque migration
foreach ($migrations as $tableName => $schema) {
    $fileName = findMigrationFile($tableName, $basePath);
    
    if ($fileName) {
        $filePath = $basePath . $fileName;
        $content = file_get_contents($filePath);
        
        // Pattern pour remplacer le contenu du schema
        $pattern = "/Schema::create\('{$tableName}', function \(Blueprint \\\$table\) \{.*?\\\$table->timestamps\(\);\s*\}\);/s";
        $replacement = "Schema::create('{$tableName}', function (Blueprint \$table) {{$schema}\n        });";
        
        $newContent = preg_replace($pattern, $replacement, $content);
        
        if ($newContent && $newContent !== $content) {
            file_put_contents($filePath, $newContent);
            echo "✓ Migration {$tableName} updated successfully\n";
        } else {
            echo "✗ Failed to update {$tableName} - pattern not found\n";
        }
    } else {
        echo "✗ Migration file not found for {$tableName}\n";
    }
}

echo "\n🎉 All migrations implementation completed!\n";
?>

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\DeliveryNote;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    /**
     * Recherche globale dans l'admin panel
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return response()->json([
                'products' => [],
                'orders' => [],
                'invoices' => [],
                'deliveryNotes' => [],
                'total' => 0
            ]);
        }

        // Recherche dans les produits
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('category')
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => number_format($product->price, 2) . ' DH',
                    'category' => $product->category->name ?? 'Sans catégorie',
                    'status' => $product->is_active ? 'Actif' : 'Inactif',
                    'url' => route('admin.products.edit', $product->id),
                    'type' => 'product'
                ];
            });

        // Recherche dans les commandes
        $orders = Order::where('order_number', 'like', "%{$query}%")
            ->orWhereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhere('guest_name', 'like', "%{$query}%")
            ->orWhere('guest_email', 'like', "%{$query}%")
            ->with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer' => $order->user ? $order->user->name : ($order->guest_name ?? 'Invité'),
                    'total' => number_format($order->total, 2) . ' DH',
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'date' => $order->created_at->format('d/m/Y'),
                    'url' => route('admin.orders.show', $order->id),
                    'type' => 'order'
                ];
            });

        // Recherche dans les factures
        $invoices = Invoice::where('invoice_number', 'like', "%{$query}%")
            ->orWhereHas('order.user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->with('order.user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'customer' => $invoice->order->user ? $invoice->order->user->name : ($invoice->order->guest_name ?? 'Invité'),
                    'total' => number_format($invoice->total, 2) . ' DH',
                    'status' => $invoice->status,
                    'date' => $invoice->invoice_date->format('d/m/Y'),
                    'url' => route('admin.invoices.show', $invoice->id),
                    'type' => 'invoice'
                ];
            });

        // Recherche dans les bons de livraison
        $deliveryNotes = DeliveryNote::where('delivery_number', 'like', "%{$query}%")
            ->orWhere('tracking_number', 'like', "%{$query}%")
            ->orWhere('carrier_name', 'like', "%{$query}%")
            ->orWhereHas('order.user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->with('order.user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($deliveryNote) {
                return [
                    'id' => $deliveryNote->id,
                    'delivery_number' => $deliveryNote->delivery_number,
                    'tracking_number' => $deliveryNote->tracking_number ?? 'N/A',
                    'customer' => $deliveryNote->order->user ? $deliveryNote->order->user->name : ($deliveryNote->order->guest_name ?? 'Invité'),
                    'carrier' => $deliveryNote->carrier_name ?? 'N/A',
                    'status' => $deliveryNote->status,
                    'date' => $deliveryNote->delivery_date->format('d/m/Y'),
                    'url' => route('admin.delivery-notes.show', $deliveryNote->id),
                    'type' => 'delivery_note'
                ];
            });

        $total = $products->count() + $orders->count() + $invoices->count() + $deliveryNotes->count();

        return response()->json([
            'products' => $products,
            'orders' => $orders,
            'invoices' => $invoices,
            'deliveryNotes' => $deliveryNotes,
            'total' => $total,
            'query' => $query
        ]);
    }
}

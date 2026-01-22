<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\InvoiceService;
use App\Services\DeliveryNoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderController extends Controller
{
    protected $orderService;
    protected $invoiceService;
    protected $deliveryNoteService;

    public function __construct(
        OrderService $orderService,
        InvoiceService $invoiceService,
        DeliveryNoteService $deliveryNoteService
    ) {
        $this->orderService = $orderService;
        $this->invoiceService = $invoiceService;
        $this->deliveryNoteService = $deliveryNoteService;
        
        $this->middleware('permission:view_orders,admin')->only(['index']);
        $this->middleware('permission:show_orders,admin')->only(['show']);
        $this->middleware('permission:update_order_status,admin')->only(['updateStatus']);
        $this->middleware('permission:update_payment_status,admin')->only(['updatePaymentStatus']);
        $this->middleware('permission:cancel_orders,admin')->only(['cancel']);
        $this->middleware('permission:generate_invoices,admin')->only(['generateInvoice']);
        $this->middleware('permission:generate_delivery_notes,admin')->only(['generateDeliveryNote']);
        $this->middleware('permission:export_orders,admin')->only(['export']);
    }

    public function index(Request $request)
    {
        $orders = Order::with('user')->latest()->get();
        $stats = $this->orderService->getSalesStats();

        $ordersPending = Order::with('user')->where('status', 'pending')->latest()->get();
        $ordersProcessing = Order::with('user')->where('status', 'processing')->latest()->get();
        $ordersShipped = Order::with('user')->where('status', 'shipped')->latest()->get();
        $ordersDelivered = Order::with('user')->where('status', 'delivered')->latest()->get();
        $ordersCancelled = Order::with('user')->where('status', 'cancelled')->latest()->get();

        return view('admin.orders.index', compact(
            'orders', 
            'stats', 
            'ordersPending', 
            'ordersProcessing', 
            'ordersShipped', 
            'ordersDelivered', 
            'ordersCancelled'
        ));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.product.images',
            'items.variant',
            'promotion',
            'coupon',
            'invoice',
            'deliveryNote'
        ]);

        $stats = $this->orderService->getOrderStats($order->id);

        return view('admin.orders.show', compact('order', 'stats'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            ]);

            $this->orderService->updateOrderStatus($order->id, $validated['status']);

            return redirect()->back()
                ->with('success', 'Statut de la commande mis à jour avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut de commande', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'payment_status' => 'required|in:pending,paid,failed',
            ]);

            $this->orderService->updatePaymentStatus($order->id, $validated['payment_status']);

            return redirect()->back()
                ->with('success', 'Statut de paiement mis à jour avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut de paiement', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        try {
            $this->orderService->cancelOrder($order->id, true);

            return redirect()->back()
                ->with('success', 'Commande annulée avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'annulation de la commande', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function generateInvoice(Order $order)
    {
        try {
            $invoice = $this->invoiceService->generateInvoice($order->id);

            return redirect()->back()
                ->with('success', "Facture {$invoice->invoice_number} générée avec succès");

        } catch (Exception $e) {
            Log::error('Erreur lors de la génération de la facture', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function generateDeliveryNote(Order $order)
    {
        try {
            $deliveryNote = $this->deliveryNoteService->generateDeliveryNote($order->id);

            return redirect()->back()
                ->with('success', "Bon de livraison {$deliveryNote->delivery_number} généré avec succès");

        } catch (Exception $e) {
            Log::error('Erreur lors de la génération du bon de livraison', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    // public function stats(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     $stats = $this->orderService->getSalesStats($startDate, $endDate);
    //     $bestSelling = $this->orderService->getBestSellingProducts(10, $startDate, $endDate);
    //     $recentOrders = $this->orderService->getRecentOrders(10);

    //     return view('admin.orders.stats', compact('stats', 'bestSelling', 'recentOrders'));
    // }

    public function export(Request $request)
    {
        try {
            $filters = [
                'status' => $request->input('status'),
                'payment_status' => $request->input('payment_status'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ];

            $orders = $this->orderService->searchOrders(array_merge($filters, ['per_page' => 10000]))->items();

            $filename = 'orders_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://output', 'w');

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            fputcsv($handle, [
                'Numéro commande',
                'Client',
                'Email',
                'Date',
                'Statut',
                'Statut paiement',
                'Sous-total',
                'Livraison',
                'Réduction',
                'Total',
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->user ? $order->user->name : 'Invité',
                    $order->user ? $order->user->email : '',
                    $order->created_at->format('d/m/Y H:i'),
                    $order->status,
                    $order->payment_status,
                    $order->subtotal,
                    $order->shipping_cost,
                    $order->discount,
                    $order->total,
                ]);
            }

            fclose($handle);
            exit;

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'export des commandes', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['items.product.images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('front-office.orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        $order = Auth::user()->orders()
            ->with([
                'items.product.images',
                'items.variant',
                'user',
                'promotion',
                'coupon',
                'invoice',
                'deliveryNote'
            ])
            ->findOrFail($orderId);

        return view('front-office.orders.show', compact('order'));
    }

    public function downloadInvoice($orderId)
    {
        try {
            $order = Auth::user()->orders()->findOrFail($orderId);

            if (!$order->invoice) {
                return redirect()->back()
                    ->with('error', 'Aucune facture disponible pour cette commande');
            }

            $invoice = $order->invoice;

            if (!$invoice->pdf_path) {
                return redirect()->back()
                    ->with('error', 'Le PDF de la facture n\'est pas encore généré');
            }

            $filePath = storage_path("app/public/{$invoice->pdf_path}");

            if (!file_exists($filePath)) {
                return redirect()->back()
                    ->with('error', 'Le fichier de facture est introuvable');
            }

            return response()->download($filePath, "facture_{$invoice->invoice_number}.pdf");

        } catch (Exception $e) {
            Log::error('Erreur lors du téléchargement de la facture', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du téléchargement de la facture');
        }
    }

    public function trackOrder($orderId)
    {
        $order = Auth::user()->orders()
            ->with(['deliveryNote'])
            ->findOrFail($orderId);

        $timeline = $this->getOrderTimeline($order);

        return view('front-office.orders.track', compact('order', 'timeline'));
    }

    protected function getOrderTimeline($order)
    {
        $timeline = [];

        $timeline[] = [
            'status' => 'ordered',
            'label' => 'Commande passée',
            'date' => $order->created_at,
            'completed' => true,
        ];

        $timeline[] = [
            'status' => 'processing',
            'label' => 'En cours de traitement',
            'date' => $order->status !== 'pending' ? $order->updated_at : null,
            'completed' => !in_array($order->status, ['pending']),
        ];

        $timeline[] = [
            'status' => 'shipped',
            'label' => 'Expédiée',
            'date' => $order->shipped_at,
            'completed' => in_array($order->status, ['shipped', 'delivered']),
            'tracking' => $order->deliveryNote ? $order->deliveryNote->tracking_number : null,
        ];

        $timeline[] = [
            'status' => 'delivered',
            'label' => 'Livrée',
            'date' => $order->delivered_at,
            'completed' => $order->status === 'delivered',
        ];

        return $timeline;
    }

    public function cancel(Request $request, $orderId)
    {
        try {
            $order = Auth::user()->orders()->findOrFail($orderId);

            if (!in_array($order->status, ['pending', 'processing'])) {
                return redirect()->back()
                    ->with('error', 'Cette commande ne peut plus être annulée');
            }

            $validated = $request->validate([
                'reason' => 'nullable|string|max:500',
            ]);

            // Here you would typically call OrderService->cancelOrder
            // For now, just update the status
            $order->update(['status' => 'cancelled']);

            return redirect()->route('orders.index')
                ->with('success', 'Commande annulée avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'annulation de la commande', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation de la commande');
        }
    }
}

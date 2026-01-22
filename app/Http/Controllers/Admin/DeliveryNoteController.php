<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use App\Services\DeliveryNoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DeliveryNoteController extends Controller
{
    protected $deliveryNoteService;

    public function __construct(DeliveryNoteService $deliveryNoteService)
    {
        $this->deliveryNoteService = $deliveryNoteService;
        
        $this->middleware('permission:view_delivery_notes,admin')->only(['index', 'show']);
        $this->middleware('permission:generate_delivery_notes,admin')->only(['generatePDF']);
        $this->middleware('permission:download_delivery_notes,admin')->only(['downloadPDF']);
        $this->middleware('permission:manage_delivery_notes,admin')->only(['updateStatus', 'updateTracking', 'markAsDelivered']);
    }

    public function index(Request $request)
    {
        $deliveryNotes = DeliveryNote::with('order.user')->latest()->paginate(20);
        $stats = $this->deliveryNoteService->getDeliveryNoteStats();

        $notesPending = DeliveryNote::with('order.user')->where('status', 'pending')->latest()->get();
        $notesShipped = DeliveryNote::with('order.user')->where('status', 'shipped')->latest()->get();
        $notesDelivered = DeliveryNote::with('order.user')->where('status', 'delivered')->latest()->get();
        $notesFailed = DeliveryNote::with('order.user')->where('status', 'failed')->latest()->get();

        return view('admin.delivery-notes.index', compact(
            'deliveryNotes', 
            'stats', 
            'notesPending', 
            'notesShipped', 
            'notesDelivered', 
            'notesFailed'
        ));
    }

    public function show(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load([
            'order.user',
            'order.items.product'
        ]);

        return view('admin.delivery-notes.show', compact('deliveryNote'));
    }

    public function downloadPDF(DeliveryNote $deliveryNote)
    {
        try {
            // Generate PDF if not exists
            if (!$deliveryNote->pdf_path) {
                $this->deliveryNoteService->generateDeliveryNotePDF($deliveryNote->id);
                $deliveryNote->refresh();
            }

            $filePath = storage_path("app/public/{$deliveryNote->pdf_path}");

            if (!file_exists($filePath)) {
                throw new Exception("Le fichier PDF n'existe pas");
            }

            return response()->download($filePath, "bon_livraison_{$deliveryNote->delivery_number}.pdf");

        } catch (Exception $e) {
            Log::error('Erreur lors du téléchargement du bon de livraison', [
                'error' => $e->getMessage(),
                'delivery_note_id' => $deliveryNote->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function generatePDF(DeliveryNote $deliveryNote)
    {
        try {
            $this->deliveryNoteService->generateDeliveryNotePDF($deliveryNote->id);

            return redirect()->back()
                ->with('success', 'PDF du bon de livraison généré avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la génération du PDF', [
                'error' => $e->getMessage(),
                'delivery_note_id' => $deliveryNote->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, DeliveryNote $deliveryNote)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,in_transit,delivered,failed,returned',
            ]);

            $this->deliveryNoteService->updateDeliveryNoteStatus($deliveryNote->id, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Statut du bon de livraison mis à jour avec succès',
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut', [
                'error' => $e->getMessage(),
                'delivery_note_id' => $deliveryNote->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateTracking(Request $request, DeliveryNote $deliveryNote)
    {
        try {
            $validated = $request->validate([
                'carrier_name' => 'required|string|max:255',
                'tracking_number' => 'required|string|max:255',
            ]);

            $this->deliveryNoteService->updateTrackingInfo(
                $deliveryNote->id,
                $validated['carrier_name'],
                $validated['tracking_number']
            );

            return redirect()->back()
                ->with('success', 'Informations de suivi mises à jour avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du suivi', [
                'error' => $e->getMessage(),
                'delivery_note_id' => $deliveryNote->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function markAsDelivered(Request $request, DeliveryNote $deliveryNote)
    {
        try {
            $validated = $request->validate([
                'recipient_name' => 'nullable|string|max:255',
                'signature_image' => 'nullable|string',
            ]);

            $this->deliveryNoteService->markAsDelivered(
                $deliveryNote->id,
                $validated['recipient_name'] ?? null,
                $validated['signature_image'] ?? null
            );

            return redirect()->back()
                ->with('success', 'Livraison confirmée avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la confirmation de livraison', [
                'error' => $e->getMessage(),
                'delivery_note_id' => $deliveryNote->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}

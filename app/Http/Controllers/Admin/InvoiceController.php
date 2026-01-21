<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $invoices = Invoice::with('order.user')->latest()->paginate(20);
        $stats = $this->invoiceService->getInvoiceStats();

        $invoicesDraft = Invoice::with('order.user')->where('status', 'draft')->latest()->get();
        $invoicesSent = Invoice::with('order.user')->where('status', 'sent')->latest()->get();
        $invoicesPaid = Invoice::with('order.user')->where('status', 'paid')->latest()->get();
        $invoicesCancelled = Invoice::with('order.user')->where('status', 'cancelled')->latest()->get();

        return view('admin.invoices.index', compact(
            'invoices', 
            'stats', 
            'invoicesDraft', 
            'invoicesSent', 
            'invoicesPaid', 
            'invoicesCancelled'
        ));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load([
            'order.user',
            'order.items.product',
            'order.promotion',
            'order.coupon'
        ]);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function downloadPDF(Invoice $invoice)
    {
        try {
            // Generate PDF if not exists
            if (!$invoice->pdf_path) {
                $this->invoiceService->generateInvoicePDF($invoice->id);
                $invoice->refresh();
            }

            $filePath = storage_path("app/public/{$invoice->pdf_path}");

            if (!file_exists($filePath)) {
                throw new Exception("Le fichier PDF n'existe pas");
            }

            return response()->download($filePath, "facture_{$invoice->invoice_number}.pdf");

        } catch (Exception $e) {
            Log::error('Erreur lors du téléchargement de la facture', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function generatePDF(Invoice $invoice)
    {
        try {
            $this->invoiceService->generateInvoicePDF($invoice->id);

            return redirect()->back()
                ->with('success', 'PDF de la facture généré avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de la génération du PDF', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:draft,sent,paid,cancelled',
            ]);

            $this->invoiceService->updateInvoiceStatus($invoice->id, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Statut de la facture mis à jour avec succès',
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut de facture', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function markAsPaid(Request $request, Invoice $invoice)
    {
        try {
            $validated = $request->validate([
                'payment_date' => 'nullable|date',
            ]);

            $this->invoiceService->markAsPaid($invoice->id, $validated['payment_date'] ?? null);

            return redirect()->back()
                ->with('success', 'Facture marquée comme payée');

        } catch (Exception $e) {
            Log::error('Erreur lors du marquage de la facture comme payée', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function cancel(Invoice $invoice)
    {
        try {
            $this->invoiceService->cancelInvoice($invoice->id);

            return redirect()->back()
                ->with('success', 'Facture annulée avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'annulation de la facture', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function sendEmail(Invoice $invoice)
    {
        try {
            $this->invoiceService->sendInvoiceByEmail($invoice->id);

            return redirect()->back()
                ->with('success', 'Facture envoyée par email avec succès');

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'envoi de la facture par email', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}

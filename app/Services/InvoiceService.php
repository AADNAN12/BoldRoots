<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\CompanyInfo;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Exception;

class InvoiceService
{
    /**
     * Generate invoice from order
     */
    public function generateInvoice($orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::with(['user', 'items.product'])
                ->findOrFail($orderId);

            // Check if order is paid
            if ($order->payment_status !== 'paid') {
                throw new Exception("La commande doit être payée avant de générer une facture");
            }

            // Check if invoice already exists
            if ($order->invoice_generated) {
                $existingInvoice = Invoice::where('order_id', $orderId)->first();
                if ($existingInvoice) {
                    return $existingInvoice;
                }
            }

            // Create invoice
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $order->subtotal,
                'discount' => $order->discount,
                'shipping_cost' => $order->shipping_cost,
                'total' => $order->total,
                'status' => 'draft',
                'payment_date' => now(),
                'notes' => null,
            ]);

            // Mark order as invoice generated
            $order->update(['invoice_generated' => true]);

            DB::commit();

            return $invoice;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate unique invoice number
     */
    public function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        
        do {
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $invoiceNumber = "{$prefix}-{$date}-{$random}";
        } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }

    /**
     * Generate PDF for invoice
     */
    public function generateInvoicePDF($invoiceId)
    {
        try {
            set_time_limit(120);
            
            $invoice = Invoice::with([
                'order.user',
                'order.items.product',
                'order.promotion',
                'order.coupon'
            ])->findOrFail($invoiceId);

            $companyInfo = CompanyInfo::first();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 60,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);

            // En-tête personnalisé
            $mpdf->SetHTMLHeader('
                <div style="width: 100%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 60%; vertical-align: top;">
                                <div>
                                    ' . ($companyInfo->logo_path ? '<img src="' . public_path('storage/' . $companyInfo->logo) . '" alt="Logo" width="200">' : '') . '
                                </div>
                            </td>
                            <td style="width: 40%; text-align: right; vertical-align: top;">
                                <div>
                                    <h3 style="margin: 0;">' . ($companyInfo->name ?? '') . '</h3>
                                    <p style="font-size: 12px; margin: 2px 0;">' . ($companyInfo->address ?? '') . '</p>
                                    <p style="font-size: 12px; margin: 2px 0;">' . ($companyInfo->city ?? '') . ', ' . ($companyInfo->postal_code ?? '') . '</p>
                                    ' . ($companyInfo->phone ? '<p style="font-size: 12px; margin: 2px 0;">Tél: ' . $companyInfo->phone . '</p>' : '') . '
                                    ' . ($companyInfo->email ? '<p style="font-size: 12px; margin: 2px 0;">Email: ' . $companyInfo->email . '</p>' : '') . '
                                    ' . ($companyInfo->tax_number ? '<p style="font-size: 12px; margin: 2px 0;">N° TVA: ' . $companyInfo->tax_number . '</p>' : '') . '
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            ');

            // Pied de page personnalisé
            $mpdf->SetHTMLFooter('
                <table style="width: 100%; margin-bottom: 10px;">
                    <tr>
                        <td style="width: 100%; text-align: center;">
                            <p style="font-size: 12px;">Page {PAGENO} sur {nbpg}</p>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; background-color: #f0f0f4;">
                    <tr>
                        <td style="width: 100%; padding: 5px 10px; background-color: #f0f0f4;">
                            <p style="font-size: 11px; margin: 2px 0;"><strong>' . ($companyInfo->name ?? '') . '</strong> - ' . ($companyInfo->address ?? '') . ', ' . ($companyInfo->city ?? '') . '</p>
                            ' . ($companyInfo->phone ? '<p style="font-size: 11px; margin: 2px 0;">Tél: ' . $companyInfo->phone . ' - Email: ' . ($companyInfo->email ?? '') . '</p>' : '') . '
                        </td>
                    </tr>
                </table>
            ');

            $data = [
                'invoice' => $invoice,
                'order' => $invoice->order,
                'company' => $companyInfo,
            ];

            // Rendre la vue en HTML
            $html = view('admin.invoices.pdf.invoice', $data)->render();

            // Écrire le HTML dans le PDF
            $mpdf->WriteHTML($html);

            // Sauvegarder le PDF
            $fileName = "invoice_{$invoice->invoice_number}.pdf";
            $path = "invoices/{$fileName}";
            $fullPath = storage_path("app/public/{$path}");

            // Créer le dossier si nécessaire
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $mpdf->Output($fullPath, 'I');

            // Mettre à jour la facture avec le chemin du PDF
            $invoice->update(['pdf_path' => $path]);

            return $path;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Update invoice status
     */
    public function updateInvoiceStatus($invoiceId, $status)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $validStatuses = ['draft', 'sent', 'paid', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Statut invalide");
        }

        $invoice->update(['status' => $status]);

        return $invoice;
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid($invoiceId, $paymentDate = null)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $invoice->update([
            'status' => 'paid',
            'payment_date' => $paymentDate ?? now(),
        ]);

        return $invoice;
    }

    /**
     * Cancel invoice
     */
    public function cancelInvoice($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        if ($invoice->status === 'paid') {
            throw new Exception("Une facture payée ne peut pas être annulée");
        }

        $invoice->update(['status' => 'cancelled']);

        return $invoice;
    }

    /**
     * Send invoice by email
     */
    public function sendInvoiceByEmail($invoiceId)
    {
        $invoice = Invoice::with('order.user')->findOrFail($invoiceId);

        // Generate PDF if not exists
        if (!$invoice->pdf_path) {
            $this->generateInvoicePDF($invoiceId);
        }

        // Send email logic here
        // Mail::to($invoice->order->user->email)->send(new InvoiceMail($invoice));

        $invoice->update(['status' => 'sent']);

        return true;
    }

    /**
     * Get invoice statistics
     */
    public function getInvoiceStats($startDate = null, $endDate = null)
    {
        $query = Invoice::query();

        if ($startDate) {
            $query->where('invoice_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('invoice_date', '<=', $endDate);
        }

        $totalInvoices = $query->count();
        $draftInvoices = (clone $query)->where('status', 'draft')->count();
        $sentInvoices = (clone $query)->where('status', 'sent')->count();
        $paidInvoices = (clone $query)->where('status', 'paid')->count();
        $cancelledInvoices = (clone $query)->where('status', 'cancelled')->count();
        $totalAmount = (clone $query)->where('status', 'paid')->sum('total');

        return [
            'total_invoices' => $totalInvoices,
            'draft_invoices' => $draftInvoices,
            'sent_invoices' => $sentInvoices,
            'paid_invoices' => $paidInvoices,
            'cancelled_invoices' => $cancelledInvoices,
            'total_amount' => round($totalAmount, 2),
        ];
    }

    /**
     * Search invoices
     */
    public function searchInvoices($filters = [])
    {
        $query = Invoice::with(['order.user']);

        if (!empty($filters['invoice_number'])) {
            $query->where('invoice_number', 'like', '%' . $filters['invoice_number'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('invoice_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('invoice_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('invoice_date', 'desc')->paginate($filters['per_page'] ?? 20);
    }
}
